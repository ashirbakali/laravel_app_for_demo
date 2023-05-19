<?php

namespace App\Helpers;

use App\Models\Ledger;
use Illuminate\Http\Request;

class Helper {

    public static function file_upload(Request $request, string $field_name, string $dir)
    {
        return "storage/app/".$request->file($field_name)->storePubliclyAs(
            $dir,$request->file($field_name)->hashName()
        );
    }

    public static function reqValue($key): string
    {
        if(empty(\request()->toArray()[$key])){
            return "";
        }
        return \request()->toArray()[$key];
    }
    public static function dates_month($month, $year,$format='d-M-Y') {
        $num = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates_month = array();

        for ($i = 1; $i <= $num; $i++) {
            $mktime = mktime(0, 0, 0, $month, $i, $year);
            $date = date($format, $mktime);
            $dates_month[$i] = $date;
        }

        return $dates_month;
    }


    public static function rangeMonth (): array
    {
        $months = [];
        for ($m=1; $m<=12; $m++) {
            $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
            $months[]= $month;
        }
        return $months;
    }

    public static function getBalance($natureId,$nature){
        $balance = Ledger::where("nature_id",$natureId)->where('nature',$nature)->orderBy('id', 'desc')->get('balance')->first()['balance'];
        return (!empty($balance) ? $balance : 0);
    }

    public static function price($amount): string
    {
        $format = getenv('MIX_APP_PRICE_FORMAT');
        return str_ireplace("#AMOUNT",number_format(floatval($amount)),$format);
    }

    public function phoneNumberFormat($number = null)
    {
        if($number)
        {
            $total_chars = str_split($number, 3);
            $number = "(".$total_chars[0]??"";
            $number .= ") ";
            $number .= $total_chars[1]??"";
            $number .= "-".$total_chars[2]??"";
            $number .= $total_chars[3]??"";
        }

        return $number;

    }
}

?>
