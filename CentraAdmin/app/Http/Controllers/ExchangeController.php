<?php

namespace App\Http\Controllers;

use App\Library\ExchangeEngine;
use App\Models\AssetdepositTable;
use App\Models\AssetBalanceTable;
use App\Models\AssetwithdrawTable;
use App\Models\UserWalletInfo;

use App\Models\TradehistoryTable;
use Illuminate\Http\Request;
use App\Library\Order;
use App\Library\Common;
use App\Library\Orderbook;

use Jenssegers\Mongodb;

class ExchangeController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    public function addOrder() {

        $customer_id = 1; // you must set this field as user login information

        $want_asset = request()->get('want_asset');
        $offer_asset = request()->get('offer_asset');
        $side = request()->get('order_side');
        $type = request()->get('order_type');
        $quantity = request()->get('quantity');
        $limit_price = request()->get('limit_price');
        $stop_price = request()->get('stop_price');
        $time_in_force = request()->get('time_in_force');
        $expiration_date = request()->get('expiration_date');

        $trade_price = Common::getTradePrice($customer_id, $want_asset, $offer_asset, $side);

        $order_data = array();
        if ( $stop_price != 'NONE' ) $order_data['stop'] = $stop_price;
        if ( $expiration_date != 'NONE' ) $order_data['expiration_date'] = $expiration_date;
        if ( $limit_price != 'NONE' ) $order_data['limit_price'] = $limit_price;
        if ( $time_in_force != 'NONE' ) $order_data['time_in_force'] = $time_in_force;
        $order_data['price'] = $trade_price;
        $order_data['customer_id'] = $customer_id;
        $order_data['want_asset'] = $want_asset;
        $order_data['offer_asset'] = $offer_asset;
        $order_data['side'] = $side;
        $order_data['type'] = $type;
        $order_data['quantity'] = $quantity;

        $exchangeEngine = new ExchangeEngine();
        $exchangeEngine->exchangeProc($order_data);
//        $orderInstance->setStatus($chkAssetBalance);
//
//        if ( $orderInstance->addNewOrder($order_data) ) {
//            echo 'success to insert';
//        }
//        else {
//            echo 'failed to insert';
//        }
    }
    public function assetDeposit($product) {
        $customer_id = 1;//$user->id;
        $product_arr = explode('-',$product);
        $want_asset = $product_arr[0];
        $want_asset_amount = request()->get('want_asset_balance');
        $offer_asset = $product_arr[1];
        $offer_asset_amount = request()->get('offer_asset_balance');

        if ( floatval($want_asset_amount) != 0 ) {
            $created_at =  Common::current_utc_date();
            $asset_row = new AssetdepositTable();
            $asset_row->custmer_id = $customer_id;
            $asset_row->asset = $want_asset;
            $asset_row->balance = $want_asset_amount*1;
            $asset_row->created_at = $created_at;
            $asset_row->updated_at = $created_at;
            $asset_row->save();

            $assetbalanceModel = new AssetBalanceTable();
            $asset_balance_row = $assetbalanceModel->where('customer_id', $customer_id)->where('asset', $want_asset)->first();
            if ( !is_null($asset_balance_row) ) {
                $asset_balance_row->balance += $want_asset_amount*1;
                $asset_balance_row->updated_at = Common::current_utc_date();
                $asset_balance_row->save();
            }
            else {
                $assetbalanceModel->customer_id = $customer_id;
                $assetbalanceModel->asset = $want_asset;
                $assetbalanceModel->balance = $want_asset_amount;
                $assetbalanceModel->created_at = Common::current_utc_date();
                $assetbalanceModel->updated_at = Common::current_utc_date();
                $assetbalanceModel->save();
            }
        }
        if ( floatval($offer_asset_amount) != 0 ) {
            $created_at =  Common::current_utc_date();
            $asset_row = new AssetdepositTable();
            $asset_row->custmer_id = $customer_id;
            $asset_row->asset = $offer_asset;
            $asset_row->balance = $offer_asset_amount*1;
            $asset_row->created_at = $created_at;
            $asset_row->updated_at = $created_at;
            $asset_row->save();

            $assetbalanceModel = new AssetBalanceTable();
            $asset_balance_row = $assetbalanceModel->where('customer_id', $customer_id)->where('asset', $offer_asset)->first();
            if ( !is_null($asset_balance_row) ) {
                $asset_balance_row->balance += $offer_asset_amount*1;
                $asset_balance_row->updated_at = Common::current_utc_date();
                $asset_balance_row->save();
            }
            else {
                $assetbalanceModel->customer_id = $customer_id;
                $assetbalanceModel->asset = $offer_asset;
                $assetbalanceModel->balance = $offer_asset_amount;
                $assetbalanceModel->created_at = Common::current_utc_date();
                $assetbalanceModel->updated_at = Common::current_utc_date();
                $assetbalanceModel->save();
            }
        }
        echo 'ok';
    }
    public function assetWithdraw($product) {
        $customer_id = 1;//$user->id;
        $product_arr = explode('-',$product);
        $want_asset = $product_arr[0];
        $want_asset_amount = request()->get('want_asset_balance');
        $offer_asset = $product_arr[1];
        $offer_asset_amount = request()->get('offer_asset_balance');

        if ( floatval($want_asset_amount) != 0 ) {
            $created_at =  Common::current_utc_date();
            $asset_row = new AssetwithdrawTable();
            $asset_row->custmer_id = $customer_id;
            $asset_row->asset = $want_asset;
            $asset_row->balance = $want_asset_amount*1;
            $asset_row->created_at = $created_at;
            $asset_row->updated_at = $created_at;
            $asset_row->save();

            $assetbalanceModel = new AssetBalanceTable();
            $asset_balance_row = $assetbalanceModel->where('customer_id', $customer_id)->where('asset', $want_asset)->first();
            if ( !is_null($asset_balance_row) ) {
                $asset_balance_row->balance -= $want_asset_amount*1;
                $asset_balance_row->updated_at = Common::current_utc_date();
                $asset_balance_row->save();
            }
            else {
                $assetbalanceModel->customer_id = $customer_id;
                $assetbalanceModel->asset = $want_asset;
                $assetbalanceModel->balance = $want_asset_amount;
                $assetbalanceModel->created_at = Common::current_utc_date();
                $assetbalanceModel->updated_at = Common::current_utc_date();
                $assetbalanceModel->save();
            }
        }
        if ( floatval($offer_asset_amount) != 0 ) {
            $created_at =  Common::current_utc_date();
            $asset_row = new AssetwithdrawTable();
            $asset_row->custmer_id = $customer_id;
            $asset_row->asset = $offer_asset;
            $asset_row->balance = $offer_asset_amount*1;
            $asset_row->created_at = $created_at;
            $asset_row->updated_at = $created_at;
            $asset_row->save();

            $assetbalanceModel = new AssetBalanceTable();
            $asset_balance_row = $assetbalanceModel->where('customer_id', $customer_id)->where('asset', $offer_asset)->first();
            if ( !is_null($asset_balance_row) ) {
                $asset_balance_row->balance -= $offer_asset_amount*1;
                $asset_balance_row->updated_at = Common::current_utc_date();
                $asset_balance_row->save();
            }
            else {
                $assetbalanceModel->customer_id = $customer_id;
                $assetbalanceModel->asset = $offer_asset;
                $assetbalanceModel->balance = $offer_asset_amount;
                $assetbalanceModel->created_at = Common::current_utc_date();
                $assetbalanceModel->updated_at = Common::current_utc_date();
                $assetbalanceModel->save();
            }
        }
        echo 'ok';
    }
    public function getUserAssetBalance($product) {
        header('Content-type:application/json');
        $customer_id = \Auth::user()->id;
        $customer_email = \Auth::user()->email;
        $product_arr = explode('-', $product);
        $want_asset = $product_arr[0];
        $offer_asset = $product_arr[1];

        $userWalletInfo = new UserWalletInfo();
        $user_wallet_info = $userWalletInfo->where('user_id', $customer_id)->where('user_email',$customer_email)->where('coin',$want_asset)->first();
        $want_asset_address = $user_wallet_info['address'];
        $user_wallet_info = $userWalletInfo->where('user_id', $customer_id)->where('user_email',$customer_email)->where('coin',$offer_asset)->first();
        $offer_asset_address = $user_wallet_info['address'];
        $want_asset_balance = Common::getBalanceOfCoin($want_asset, $want_asset_address);
        $offer_asset_balance = Common::getBalanceOfCoin($offer_asset, $offer_asset_address);

//        $want_asset_balance = 0;
//        $assetbalanceModel = new AssetBalanceTable();
//        $asset_balance_row = $assetbalanceModel->where('customer_id', $customer_id)->where('asset', $want_asset)->first();
//        if ( !is_null($asset_balance_row) ) {
//            $want_asset_balance = $asset_balance_row->balance*1;
//        }
//        $offer_asset_balance = 0;
//        $asset_balance_row = $assetbalanceModel->where('customer_id', $customer_id)->where('asset', $offer_asset)->first();
//        if ( !is_null($asset_balance_row) ) {
//            $offer_asset_balance = $asset_balance_row->balance*1;
//        }
        $ret_arr = array( 'want_asset_balance'=>$want_asset_balance, 'offer_asset_balance'=>$offer_asset_balance );
        echo json_encode($ret_arr);
        exit;
    }



    public function getTradeData($product) {
//        header('Access-Control-Allow-Origin: *');
        header('Content-type:application/json');
        $customer_id = 1;
        $tmp = explode('-', $product);
        $want_asset = $tmp[0];
        $offer_asset = $tmp[1];
        $time_scale = request()->get('time_scale');
        if ( is_null($time_scale) ) $time_scale = '1m';
        $open_order_data = array();
        $orderbook_data = array();
        $fills_data = array();
        $tradehistory_data = array();
        $pricechart_data = array();
        $depthchart_data = array();

        $ask_data = Orderbook::getOrderBookList($customer_id, $want_asset, $offer_asset, 'buy');
        $bid_data = Orderbook::getOrderBookList($customer_id, $want_asset, $offer_asset, 'sell');
        $ask_arr = array();
        $cnt = 0;
        foreach( $ask_data as $askdata ) {
            $tmp = array();
            $cnt++;
            $tmp['price'] = $askdata['limit_price'];
            $tmp['size'] = $askdata['quantity'];
            $tmp['num_orders'] = $cnt;
            $ask_arr[] = $tmp;
        }
        $bid_arr = array();
        $cnt = 0;
        foreach( $bid_data as $biddata ) {
            $tmp = array();
            $cnt++;
            $tmp['price'] = $biddata['limit_price'];
            $tmp['size'] = $biddata['quantity'];
            $tmp['num_orders'] = $cnt;
            $bid_arr[] = $tmp;
        }

        (count($bid_data)>0) ? $best_bid_price = $bid_data[0]['limit_price'] : $best_bid_price = 0;
        (count($ask_data)>0) ? $best_ask_price = $ask_data[0]['limit_price'] : $best_ask_price = 0;
        $spread = abs($best_bid_price-$best_ask_price);

        $orderbook_data = array('bid'=>$bid_data, 'ask'=>$ask_data, 'spread'=>$spread, 'best_ask_price'=>$best_ask_price, 'best_bid_price'=>$best_bid_price);
        $depthchart_data = array('bids'=>$bid_arr, 'asks'=>$ask_arr);

        $open_order_data = Orderbook::getOrderOfUser($customer_id, $want_asset, $offer_asset);
//        $user_open_order_data = Order::getOrderOfUser($customer_id, $want_asset, $offer_asset);
        $fills_data = Common::getFillsData($customer_id, $want_asset, $offer_asset);//array_merge_recursive($open_order_data, $user_open_order_data);

        $tradehistoryTable = new TradehistoryTable();
        $tradehistoryTable->setCollection($want_asset, $offer_asset);
        $tradehistory_datas = $tradehistoryTable->get()->toArray();
        foreach( $tradehistory_datas as $d ) {
            $d['time'] = Common::DateFromBSON($d['time']);
            $tradehistory_data[] = $d;
        }
        $pricechart_data = Common::getPriceData($want_asset, $offer_asset, $time_scale);

        $ret_data = array('time_scale'=>$time_scale, 'openorder'=>$open_order_data, 'orderbook'=>$orderbook_data,
                          'fills'=>$fills_data, 'tradehistory'=>$tradehistory_data, 'pricechart'=>$pricechart_data, 'depthchart'=>$depthchart_data);
        echo json_encode($ret_data);
    }
    public function getPriceChartData( $product ) {
        header('Access-Control-Allow-Origin: *');
        header('Content-type:application/json');
        $tmp = explode('-', $product);
        $want_asset = $tmp[0];
        $offer_asset = $tmp[1];
        $time_scale = request()->get('time_scale');
        if ( is_null($time_scale) ) $time_scale = '1d';
        $pricechart_data = array();

        $pricechart_data = Common::getPriceData($want_asset, $offer_asset, $time_scale);
//        $tradepriceInterface = new TradePrice();
//
//        $customer_id = 1;
//        $pricechart_data = $tradepriceInterface->getTradePriceData($time_scale);

        echo json_encode($pricechart_data);
    }
}
