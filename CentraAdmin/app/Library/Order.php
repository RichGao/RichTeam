<?php
namespace App\Library;

use App\Library\Common;

use App\Library\Orderbook;
use App\Models\OrderTable;
use App\Models\AssetBalanceTable;

class Order {
    private $customer_id;
    private $type;
    private $side;
    private $want_asset;
    private $offer_asset;
    private $quantity;
    private $price;  //optional
    private $limit_price;  //optional
    private $stop_price;  //optional
    private $time_in_force;  //optional
    private $expiration_date;  //optional
    private $filled_quantity;  //optional
    private $remaining_quantity;
    private $updated_at;
    private $created_at;
    private $status='';
    public function __construct($order_data) {
        foreach( $order_data as $key=>$val ) {
            $this->$key = $val;
        }
        $this->remaining_quantity = $order_data['quantity'];
        $this->filled_quantity = 0;
        $this->updated_at = Common::current_utc_date();
        $this->created_at = Common::current_utc_date();
    }
    public function addNewOrder($order_data_arr) {
        if ( $order_data_arr ) {
            $order_table = new OrderTable();
            $order_table->setCollection( $order_data_arr['want_asset'], $order_data_arr['offer_asset'] );
            foreach( $order_data_arr as $key=>$val ) {
                $order_table->$key = $val;
            }
            $order_table->created_at = Common::current_utc_date();
            $order_table->updated_at = Common::current_utc_date();
            $resp = $order_table->save();
            return true;
        }
        return false;
    }
    public function setStatus($status) {
        $this->status = $status;
    }
    public function getArrayData(){
        $ret_arr = array();
        $ret_arr['customer_id'] = $this->customer_id;
        $ret_arr['type'] = $this->type;
        $ret_arr['side'] = $this->side;
        $ret_arr['want_asset'] = $this->want_asset;
        $ret_arr['offer_asset'] = $this->offer_asset;
        $ret_arr['quantity'] = $this->quantity*1;
        if ( isset($this->price) ) $ret_arr['price'] = $this->price*1;
        if ( isset($this->limit_price) ) $ret_arr['limit_price'] = $this->limit_price*1;
        if ( isset($this->stop_price) ) $ret_arr['stop_price'] = $this->stop_price*1;
        if ( isset($this->time_in_force) ) $ret_arr['time_in_force'] = $this->time_in_force;
        if ( isset($this->expiration_date) ) $ret_arr['expiration_date'] = $this->expiration_date;
        if ( isset($this->filled_quantity) ) $ret_arr['filled_quantity'] = $this->filled_quantity*1;
        if ( isset($this->remaining_quantity) ) $ret_arr['remaining_quantity'] = $this->remaining_quantity*1;
        if ( $this->status != '' ) $ret_arr['status'] = $this->status;
        return $ret_arr;
    }

    public function getAssetBalanceOfOrder() {
        $order = self::getArrayData();
        $customer_id = $order['customer_id'];
        $want_asset = $order['want_asset'];
        $offer_asset = $order['offer_asset'];
        $side = $order['side'];
        $quantity = $order['quantity'];
        $trade_price = $order['price'];

        $fee = 0.2;
        $assetbalanceModel = new AssetBalanceTable();
        if ( $side == 'buy' ) {
            $offer_asset_balance = $assetbalanceModel->getAssetBalance($customer_id, $offer_asset);
            $funds = (1+$fee/100)*$quantity*$trade_price;
            if ( $offer_asset_balance > $funds ) return 'enough';
        }
        if ( $side == 'sell' ) {
            $funds = $quantity;
            $want_asset_balance = $assetbalanceModel->getAssetBalance($customer_id, $want_asset);
            if ( $want_asset_balance > $funds ) return 'enough';
        }
        return 'insufficient';
    }
    public function getOrtderExecutable() {
        $trade_price = Common::getTradePrice($this->customer_id, $this->want_asset, $this->offer_asset, $this->side);
        $orderbookInterface = new Orderbook($this->want_asset, $this->offer_asset);
        $orderbook_data = $orderbookInterface->getOrderBookList($this->customer_id, $this->want_asset, $this->offer_asset, $this->side);
        if ( count($orderbook_data) > 0 ) {
            if ( $this->type == 'market' ) return 'active';
            if ( $this->type == 'limit' ) {
                if ( $this->side == 'sell' ){
                    if ( $this->limit_price <= $trade_price ) return 'active'; else return 'open';
                }
                if ( $this->side == 'buy' ) {
                    if ( $this->limit_price >= $trade_price ) return 'active'; else return 'open';
                }
            }
            if ( $this->type == 'stop' ) {
                if ( $this->side == 'sell' ){
                    if ( $this->stop_price >= $trade_price ) return 'active'; else return 'pending';
                }
                if ( $this->side == 'buy' ) {
                    if ( $this->stop_price <= $trade_price ) return 'active'; else return 'pending';
                }
            }
            if ( $this->type == 'stoplimit' ) {
                if ( $this->side == 'sell' ){
                    if ( $this->stop_price >= $trade_price ) {
                        if ( $this->limit_price <= $trade_price ) return 'active'; else return 'open';
                    }
                    else return 'pending';
                }
                if ( $this->side == 'buy' ) {
                    if ( $this->stop_price <= $trade_price ) {
                        if ( $this->limit_price >= $trade_price ) return 'active'; else return 'open';
                    }
                    else return 'pending';
                }
            }
        }
        else {
            if ( $this->type == 'market' ) return 'canceled';
            if ( $this->type == 'limit' ) return 'open';
            if ( $this->type == 'stop' || $this->type == 'stoplimit' ) return 'pending';
        }
        return 'canceled';
    }
    public static function getOrderOfUser($customer_id, $want_asset, $offer_asset) {
        $orderTable = new OrderTable();
        $orderTable->setCollection( $want_asset, $offer_asset );
        return $orderTable->getOrderOfUser($customer_id, $want_asset, $offer_asset);
    }
}