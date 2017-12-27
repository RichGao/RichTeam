<?php
namespace App\Library;

// use App\Models\OrderTransaction;
use App\Library\Orderbook;
use App\Models\CommissionTable;
use App\Models\TradehistoryTable;
use DB;

use App\Library\Order;
use Faker\Provider\zh_CN\DateTime;

class Common
{
    //
    private $side_info = array('buy'=>'ask', 'sell'=>'bid');
    public function __construct() {

    }
    public static function udate($format, $utimestamp = null) {
        date_default_timezone_set("UTC");
        if (is_null($utimestamp))
            $utimestamp = microtime(true);

        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), $timestamp);
    }

    public static function current_utc_date($utimestamp = null){
        date_default_timezone_set("UTC");
        $mongo_date =  new \MongoDB\BSON\UTCDateTime();

        return $mongo_date;
    }
    public static function UTCToTimestamp($utc_datetime_str)
    {
        preg_match_all('/(.+?)T(.+?)\.(.*?)Z/i', $utc_datetime_str, $matches_arr);
        $datetime_str = $matches_arr[1][0]." ".$matches_arr[2][0];

        return strtotime($datetime_str);
    }
    public static function genAddressOfCoin($coin) {
        $apiUrl = env('BLOCKCHAIN_SERVER').'/generate/'.strtoupper($coin);
        $requestHeaders = [
            'Content-type: application/json'
        ];
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    public static function getBalanceOfCoin($coin, $address) {

        $apiUrl = env('BLOCKCHAIN_SERVER').'/getBalance/'.strtoupper($coin).'/'.$address;
        $requestHeaders = [
            'Content-type: application/json'
        ];
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $requestHeaders);

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    public static function DateFromBSON($bsonObj, $format=null) {
        if ( is_null($format) ) $format = 'Y-m-d H:i:s.u';
        return $bsonObj->toDateTime()->format( $format );
    }
    public static function getTradePrice( $customer_id, $want_asset, $offer_asset, $side ) {
        return Orderbook::getBestPrice($customer_id, $want_asset, $offer_asset, $side);
    }
    public static function generateRandomString($length=10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    public static function getFillsData( $customer_id, $want_asset, $offer_asset ) {
        $commissionTable = new CommissionTable();
        $commissionTable->setCollection($want_asset, $offer_asset);
        $datas = $commissionTable->where('taker_customer_id', $customer_id)->where('want_asset', $want_asset)->where('offer_asset', $offer_asset)->get()->toArray();
        $ret_arr = array();
        foreach( $datas as $data ) {
            if ( !isset($data['time']) ) $time = date('Y-m-d H:i:s'); else $time = Common::DateFromBSON($data['time']);
            $ret_arr[] = array('quantity'=>$data['quantity'],'price'=>$data['quantity'], 'time'=>$time,'commission'=>$data['commission'], 'product'=>$want_asset."-".$offer_asset);
        }
        return $ret_arr;
    }

    public static function getPriceData( $want_asset, $offer_asset, $time_scale ) {
        $tradehistoryModel = new TradehistoryTable();
        $arr = array();
        $color_arr = array('buy'=>'#31ff31', 'sell'=>'#f6272b');
        if ( $time_scale == '1m' ) {
            $tradehistoryModel->setCollection($want_asset, $offer_asset);
            $l_date = new \DateTime('now', new \DateTimeZone('UTC'));
            $s_date = new \DateTime('-1 hours', new \DateTimeZone('UTC'));
            $datas = $tradehistoryModel->where( 'time', '>=',$s_date)->get()->toArray();
            $trade_data = array();
            foreach( $datas as $data ) {
                $data['datetime'] = self::DateFromBSON($data['time']);
                $trade_data[] = $data;
            }
            $interval = new \DateInterval('PT1M');
            $period   = new \DatePeriod($s_date, $interval, $l_date);
            $previous = '';
            foreach ($period as $dt) {
                $current = $dt->format("H:i:00");
                if (!empty($previous)) {
                    $d_arr = array();
                    $price_arr = array();
                    $sum = array();
                    if ( count($trade_data)>0 ) {
                        for( $i=0;$i<count($trade_data); $i++ ) {
                            $data = $trade_data[$i];
                            $dd = strtotime($data['datetime']);
                            $prev = strtotime($previous);
                            $curr = strtotime($current);
                            if ( $dd>= $prev && $dd<$curr ) {
                                $d_arr[] = $data;
                                $price_arr[] = $data['price'];
                                $sum[] = $data['quantity']*1;
                            }
                        }
                    }
                    $ar = array();
                    if ( count($d_arr) > 0 ) {
                        $ar['open'] = $d_arr[0]['price'];
                        $ar['close'] = $d_arr[count($d_arr)-1]['price'];
                        $ar['high'] = max($price_arr);
                        $ar['low'] = min($price_arr);
                        $ar['volume'] = array_sum($sum);
                        $ar['time1'] = $previous;
                        $ar['time'] = $dt->format('Y-m-d H:i:s');
                        $ar['side'] = $d_arr[count($d_arr)-1]['side'];
                        $ar['color_field'] = $color_arr[$d_arr[count($d_arr)-1]['side']];
                        $arr[] = $ar;
                    }
                    else {
                        $ar['open'] = 0;
                        $ar['close'] = 0;
                        $ar['high'] = 0;
                        $ar['low'] = 0;
                        $ar['volume'] = 0;
                        $ar['time1'] = $previous;
                        $ar['time'] =  $dt->format('Y-m-d H:i:s');
                        $ar['side'] = 'sell';
                        $ar['color_field'] = $color_arr['sell'];
                        $arr[] = $ar;
                    }


//                    echo "<input name='time' type='radio' value='{$previous}|{$current}'> {$previous}-{$current}<br/>";
                }
                $previous = $current;
            }
        }
        else if ( $time_scale == '5m' ) {
            $tradehistoryModel->setCollection($want_asset, $offer_asset);
            $l_date = new \DateTime('now', new \DateTimeZone('UTC'));
            $s_date = new \DateTime('-6 hours', new \DateTimeZone('UTC'));
            $datas = $tradehistoryModel->where( 'time', '>=',$s_date)->get()->toArray();
            $trade_data = array();
            foreach( $datas as $data ) {
                $data['datetime'] = self::DateFromBSON($data['time']);
                $trade_data[] = $data;
            }
            $interval = new \DateInterval('PT5M');
            $period   = new \DatePeriod($s_date, $interval, $l_date);
            $previous = '';
            foreach ($period as $dt) {
                $current = $dt->format("H:i:00");
                if (!empty($previous)) {
                    $d_arr = array();
                    $price_arr = array();
                    $sum = array();
                    if ( count($trade_data)>0 ) {
                        for( $i=0;$i<count($trade_data); $i++ ) {
                            $data = $trade_data[$i];
                            $dd = strtotime($data['datetime']);
                            $prev = strtotime($previous);
                            $curr = strtotime($current);
                            if ( $dd>= $prev && $dd<$curr ) {
                                $d_arr[] = $data;
                                $price_arr[] = $data['price'];
                                $sum[] = $data['quantity']*1;
                            }
                        }
                    }
                    $ar = array();
                    if ( count($d_arr) > 0 ) {
                        $ar['open'] = $d_arr[0]['price'];
                        $ar['close'] = $d_arr[count($d_arr)-1]['price'];
                        $ar['high'] = max($price_arr);
                        $ar['low'] = min($price_arr);
                        $ar['volume'] = array_sum($sum);
                        $ar['time1'] = $previous;
                        $ar['time'] =  $dt->format('Y-m-d H:i:s');
                        $ar['color_field'] = $color_arr[$d_arr[count($d_arr)-1]['side']];
                        $ar['side'] = $d_arr[count($d_arr)-1]['side'];
                        $arr[] = $ar;
                    }
                    else {
                        $ar['open'] = 0;
                        $ar['close'] = 0;
                        $ar['high'] = 0;
                        $ar['low'] = 0;
                        $ar['volume'] = 0;
                        $ar['time1'] = $previous;
                        $ar['time'] =  $dt->format('Y-m-d H:i:s');
                        $ar['side'] = 'sell';
                        $ar['color_field'] = $color_arr['sell'];
                        $arr[] = $ar;
                    }


//                    echo "<input name='time' type='radio' value='{$previous}|{$current}'> {$previous}-{$current}<br/>";
                }
                $previous = $current;
            }
        }
        else if ( $time_scale == '15m' ) {
            $tradehistoryModel->setCollection($want_asset, $offer_asset);
            $l_date = new \DateTime('now', new \DateTimeZone('UTC'));
            $s_date = new \DateTime('-15 hours', new \DateTimeZone('UTC'));
            $datas = $tradehistoryModel->where( 'time', '>=',$s_date)->get()->toArray();
            $trade_data = array();
            foreach( $datas as $data ) {
                $data['datetime'] = self::DateFromBSON($data['time']);
                $trade_data[] = $data;
            }
            $interval = new \DateInterval('PT15M');
            $period   = new \DatePeriod($s_date, $interval, $l_date);
            $previous = '';
            foreach ($period as $dt) {
                $current = $dt->format("H:i:00");
                if (!empty($previous)) {
                    $d_arr = array();
                    $price_arr = array();
                    $sum = array();
                    if ( count($trade_data)>0 ) {
                        for( $i=0;$i<count($trade_data); $i++ ) {
                            $data = $trade_data[$i];
                            $dd = strtotime($data['datetime']);
                            $prev = strtotime($previous);
                            $curr = strtotime($current);
                            if ( $dd>= $prev && $dd<$curr ) {
                                $d_arr[] = $data;
                                $price_arr[] = $data['price'];
                                $sum[] = $data['quantity']*1;
                            }
                        }
                    }
                    $ar = array();
                    if ( count($d_arr) > 0 ) {
                        $ar['open'] = $d_arr[0]['price'];
                        $ar['close'] = $d_arr[count($d_arr)-1]['price'];
                        $ar['high'] = max($price_arr);
                        $ar['low'] = min($price_arr);
                        $ar['volume'] = array_sum($sum);
                        $ar['time1'] = $previous;
                        $ar['time'] = $dt->format('Y-m-d H:i:s');
                        $ar['side'] = $d_arr[count($d_arr)-1]['side'];
                        $ar['color_field'] = $color_arr[$d_arr[count($d_arr)-1]['side']];
                        $arr[] = $ar;
                    }
                    else {
                        $ar['open'] = 0;
                        $ar['close'] = 0;
                        $ar['high'] = 0;
                        $ar['low'] = 0;
                        $ar['volume'] = 0;
                        $ar['time1'] = $previous;
                        $ar['time'] = $dt->format('Y-m-d H:i:s');
                        $ar['side'] = 'sell';
                        $ar['color_field'] = $color_arr['sell'];
                        $arr[] = $ar;
                    }


//                    echo "<input name='time' type='radio' value='{$previous}|{$current}'> {$previous}-{$current}<br/>";
                }
                $previous = $current;
            }
        }
        else if ( $time_scale == '1h' ) {

        }
        else if ( $time_scale == '6h' ) {

        }
        else if ( $time_scale == '1d' ) {

        }
        return $arr;
    }
    public static function getCommission( $want_asset, $offer_asset ) {
        return 0.2;
    }

    /**
     *** convert  BSON to Array
     */
    public static function OrderToArray( $order ) {
        $ret_arr = array();
        foreach( $order as $key=>$val ) {
            if (!is_null($val) && $val!='null') $ret_arr[$key] = $val;
        }
        return $ret_arr;
    }

    public static function beforeMinutes( $param ) {
        date_default_timezone_set("UTC");
        $date_past = date('Y-m-d H:i:s', time()-$param*60);
        return $date_past;
    }

    public static function std_to_array( $stdObj ) {
        $ret_arr = array();
        foreach( $stdObj as $key=>$val ) {
            $ret_arr[$key] = $val;
        }
        return $ret_arr;
    }
}
