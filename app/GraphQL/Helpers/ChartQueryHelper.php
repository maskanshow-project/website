<?php

namespace App\GraphQL\Helpers;

trait ChartQueryHelper
{
    public static function change_old_year_month(&$result)
    {
        for ( $i = jdate()->getMonth() + 1; $i <= 12; ++$i )
        {
            $result[$i] = [
                'month' => self::get_month_name( $i ) . self::get_year(true),
                'count' => isset($result[$i]) ? $result[$i] : 0
            ];
        }
    }

    public static function change_new_year_month(&$result)
    {
        for ( $i = 1; $i <= jdate()->getMonth(); ++$i )
        {
            $result[12 + $i] = [
                'month' => self::get_month_name( $i ) . self::get_year(),
                'count' => isset($result[$i]) ? $result[$i] : 0
            ];

            unset( $result[$i] );
        }
    }

    public static function get_year(bool $minus = false)
    {
        $date = jdate();

        if ($minus)
            $date = $date->subYears(1);

        return ' '.substr($date->getYear(), 2);
    }

    public static function get_month_name(int $month)
    {
        switch ( $month )
        {
            case  1: return 'فرودین';
            case  2: return 'اردیبهشت';
            case  3: return 'خرداد';
            case  4: return 'تیر';
            case  5: return 'مرداد';
            case  6: return 'شهریور';
            case  7: return 'مهر';
            case  8: return 'آبان';
            case  9: return 'آذر';
            case 10: return 'دی';
            case 11: return 'بهمن';
            case 12: return 'اسفند';
        }
    }
}