<?php

use Illuminate\Support\Facades\Schema;
// use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Helpers\Blueprint;

class CreateFinancialTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = DB::connection()->getSchemaBuilder();

        $schema->blueprintResolver(function($table, $callback) {
            return new Blueprint($table, $callback);
        });

        $schema->create('promocodes', function (Blueprint $table) {
            $table->table([
                'code'              => 30,
                'description'       => 250,
                'cost'              => 'integer',
                'quantity'          => 'integer|nullable',
                'used_count'        => 'integer|default:0',
                'jalali_created_at' => 'datetime|nullable',
                'is_active'         => 'boolean|default:1'
            ], ['users'], 'int', ['expired_at']);
        });

        $schema->create('promocode_translations', function ($table) {
            $table->increments('id');
            $table->integer('promocode_id')->unsigned();
            
            $table->string('title', 50);
            $table->string('description', 255)->nullable();
            $table->string('locale')->index();

            $table->unique(['promocode_id','locale']);
            $table->foreign('promocode_id')->references('id')->on('promocodes')->onDelete('cascade');
        });

        $schema->create('plans', function (Blueprint $table) {
            $table->table([
                'color'                 => '9|comment:Hexadecimal code of the assignment, e.g #43df12',
                'price'                 => 'unsignedInteger',
                'visited_estate_count'  => 'unsignedInteger',
                'registered_estate_count'  => 'unsignedInteger',
                'credit_days_count'     => 'unsignedInteger',

                'jalali_created_at'     => 'datetime|nullable',
                'is_active'             => 'boolean|default:1'
            ], ['users'], 'int');
        });

        $schema->create('plan_translations', function ($table) {
            $table->increments('id');
            $table->integer('plan_id')->unsigned();
            
            $table->string('title', 50);
            $table->string('description', 255)->nullable();
            $table->string('locale')->index();

            $table->unique(['plan_id','locale']);
            $table->foreign('plan_id')->references('id')->on('plans')->onDelete('cascade');
        });

        $schema->create('promocode_plan', function (Blueprint $table) {
            $table->interface('promocodes', 'plans');
        });

        $schema->create('promocode_user', function (Blueprint $table) {
            $table->interface('promocodes', 'users');
        });
        
        $schema->create('payments', function (Blueprint $table) {
            $table->table([
                'code'              => 50,
                'description'       => '250|nullable',
                'amount'            => 'integer',
                'ref_id'            => 100,
                'payment_id'        => 100,
                'tracking_code'     => 100,
                'jalali_created_at' => 'datetime|nullable',
            ], [
                'users',
                'plans',
                'promocodes' => ['nullable', 'set null']
            ], 'int', [
                'expired_at',
                'paid_at'
            ]);
        });

        $schema->table('users', function (Blueprint $table) {
            $table->add_foreign('plans', true, 'unsignedInteger', null, 'set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $schema->dropIfExists('articles');
    }
}
