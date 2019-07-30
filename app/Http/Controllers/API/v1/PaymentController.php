<?php

namespace App\Http\Controllers\API\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Financial\Payment;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function pay($payment_code)
    {
        $payment = Payment::whereCode($payment_code)->firstOrFail();

        if ( $payment->paid_at )
        {
            $message = "این پرداخت قبلا انجام شده است";

            return redirect("/panel?message={$message}&message_title=متاسفانه خطایی رخ داد");
        } 
        elseif ( $payment->expired_at->isPast() )
        {
            $message = "متاسفانه پرداخت شما منقضی شده است ، لطفا دوباره اقدام به پرداخت کنید";

            return redirect("/panel?message={$message}&message_title=متاسفانه خطایی رخ داد");
        }

        try {   
            $gateway = \Gateway::make(new \Larabookir\Gateway\Zarinpal\Zarinpal() );
         
            $gateway->price( $payment->amount * 10 )->ready();
            // $gateway->price( 1000 )->ready();
         
            $refId =  $gateway->refId(); // شماره ارجاع بانک
            $transID = $gateway->transactionId(); // شماره تراکنش
         
            if ( $payment->update([
                'ref_id' => $refId,
                'payment_id' => $transID
            ]) ) {
                return $gateway->redirect();
            }
            
            $message = "متاسفانه در ارتباط با سرور خطایی رخ داد ، لطفا دوباره تلاش کنید";
            return redirect("/panel?message={$message}&message_title=متاسفانه خطایی رخ داد");

         } catch (\Exception $e) {
            $message = $e->getMessage();

            return redirect("/panel?message={$message}&message_title=متاسفانه خطایی رخ داد");
         }
    }

    public function verify(Request $request)
    {
        try { 
            $gateway = \Gateway::verify();
            $trackingCode = $gateway->trackingCode();
            $refId = $gateway->refId();
            
            $payment = Payment::whereRefId($refId)->with(['plan', 'promocode', 'payer'])->firstOrFail();

            $payment->update([
                'paid_at' => now(),
                'tracking_code' => $trackingCode
            ]);

            $plan = $payment->plan;
            $promocode = $payment->promocode;
            $payer = $payment->payer;

            $payer->update([
                'plan_id' => $plan->id,
                'validity_end_at' => ( new Carbon( $payer->validity_end_at ) )->addDays( $plan->credit_days_count ),
                'last_payment' => now()
            ]);
            $payer->increment('remaining_hits_count', $plan->visited_estate_count );
            $payer->increment('remaining_registered_count', $plan->registered_estate_count );
            $payer->increment('payments_count');
            $payer->increment('total_payments', $payment->amount);

            if ( $promocode )
            {
                $promocode->increment('used_count');
                $promocode->users()->syncWithoutDetaching([ $payer->id ]);
            }

            $message = "پرداخت شما با موفقیت انجام شد<br/>از حس اعتماد شما به ما سپاس گذاریم<br/>شناسه پرداخت شما : <b>{$trackingCode}</b>";

            return redirect("/panel?message={$message}&message_title=پرداخت انجام شد&message_type=success");
         
        } catch (\Larabookir\Gateway\Exceptions\RetryException $e) {

            $message = "عملیات پرداخت شما انجام شده است ، از رفرش کردن صفحه در هنگاه پرداخت خودداری کنید";

            return redirect("/panel?message={$message}&message_title=پرداخت انجام شده است");
             
        } catch (\Exception $e) {

            $message = $e->getMessage();

            return redirect("/panel?message={$message}&message_title=متاسفانه خطایی رخ داد");
        }
    }
}
