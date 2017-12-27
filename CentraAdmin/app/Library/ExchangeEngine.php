<?php

namespace App\Library;

use App\Library\Order;
use App\Library\Orderbook;
use App\Models\AssetBalanceTable;
use App\Models\ClosedordersTable;
use App\Models\CommissionTable;
use App\Models\OrderbookTable;
use App\Models\OrderTable;
use App\Models\TradehistoryTable;

class ExchangeEngine
{
    //
    private $maker_order;
    private $taker_order;

    public function exchangeProc( $order_data ) {
        $orderInstance = new Order( $order_data );

        $orderbookInst = new Orderbook();
        if ( $orderInstance->getAssetBalanceOfOrder() == 'enough' ) {
            $orderInstance->setStatus($orderInstance->getOrtderExecutable());
            $order_data = $orderInstance->getArrayData();
            if ( $order_data['status'] == 'active' ) {
                //TODO ----- Processor to execute order immediatelly
                self::setMakerOrder($order_data['customer_id'], $order_data['want_asset'], $order_data['offer_asset'], $order_data['side']);
                self::setTakerOrder($order_data);
                if ( $order_data['side'] == 'buy' )
                    $this->exchangeForBuy();
                else
                    $this->exchangeForSell();

            }
            else if ( $order_data['status'] == 'open' ) {
                //TODO ----- Processor to store order onto orderbook
                Orderbook::addNewOrderBook($order_data);
            }
        }
        else {
            echo 'rejected';
        }
    }
    private function setMakerOrder($customer_id, $want_asset, $offer_asset, $side) {
        $orderbookInterface = new Orderbook();
        $this->maker_order = $orderbookInterface->getOrderBookList($customer_id, $want_asset, $offer_asset, $side);
    }
    private function setTakerOrder($m_taker_order) {
        $this->taker_order = $m_taker_order;
    }
    private function getFirstMakerPrice() {
        if ( !empty($this->maker_order) ) return $this->maker_order[0]['limit_price'];
        return null;
    }
    private function exchangeForBuy() {
        $assetBalanceTable = new AssetBalanceTable();

        $commissionfee = Common::getCommission($this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        $remaining_quantity = $this->taker_order['remaining_quantity'];
        $trade_price = $this->getFirstMakerPrice();

        /**
         *** definition of array variable for exchange
         **/
        $trade_data = array();
        $trade_history_data = array();
        $removed_makers = array();
        $closed_makers = array();
        $commission_datas = array();
        /****/

        while( $remaining_quantity > 0 && !is_null($trade_price) ) {
            $maker = $this->maker_order[0];
            ( isset( $maker['remaining_quantity'] ) ) ? $maker_quantity = $maker['remaining_quantity'] :
                $maker_quantity = $maker['quantity'];
            $trade_price = $maker['limit_price'];
            $trade_history_data['price'] = $trade_price;

            $commission_data = array();
            $commission_data['taker_customer_id'] = $this->taker_order['customer_id'];
            $commission_data['maker_customer_id'] = $maker['customer_id'];
            $commission_data['want_asset'] = $this->taker_order['want_asset'];
            $commission_data['offer_asset'] = $this->taker_order['offer_asset'];
            $commission_data['time'] = Common::current_utc_date();

            if ( $maker_quantity <= $remaining_quantity ) {
                $removed_maker = array_shift($this->maker_order);
                $removed_maker['filled_quantity'] = $maker_quantity;
                $removed_maker['remaining_quantity'] = 0;
                $removed_maker['created_at'] = Common::current_utc_date();
                $removed_maker['updated_at'] = Common::current_utc_date();
                $removed_makers[] = $removed_maker['_id'];
                $commission_data['quantity'] = $maker_quantity;
                // Asset re-balancing processor for taker and maker order...
                $taker_funds = (1+$commissionfee/100)*$maker_quantity*$trade_price;
                $maker_funds = $maker_quantity*$trade_price;
                $trade_history_data['quantity'] = $maker_quantity;
                unset($removed_maker['_id']);
                $closed_makers[] = $removed_maker;

                $assetBalanceTable->setAssetBalance($this->taker_order['customer_id'], $this->taker_order['side'], $this->taker_order['want_asset'], $this->taker_order['offer_asset'], $maker_quantity, $taker_funds);
                $assetBalanceTable->setAssetBalance($maker['customer_id'], $this->taker_order['side'],$this->taker_order['want_asset'], $this->taker_order['offer_asset'], $maker_quantity, $maker_funds);

                $remaining_quantity -= $maker_quantity*1;
            }
            else {
                $maker['quantity'] -= $remaining_quantity;
                $trade_history_data['quantity'] = $remaining_quantity;
                ( !isset($maker['filled_quantity']) ) ? $f_size = $remaining_quantity : $f_size = $maker['filled_quantity']*1 + $remaining_quantity;

                $r_size = $maker['remaining_quantity']*1 - $remaining_quantity;

                // update filled/remaining quantity of current maker with f_size/r_size when maker quantity is greater than remain taker quantity.
                $_id = $maker['_id'];
                $maker_update_data = array('quantity'=>$r_size, 'remaining_quantity'=>$r_size, 'filled_quantity'=>$f_size, 'updated_at'=>Common::current_utc_date());
                Orderbook::updateOrder($maker['want_asset'], $maker['offer_asset'], $_id, $maker_update_data);

                // asset commission processing for trading...
                $commission_data['quantity'] = $remaining_quantity;
                // Asset rebalacing processor for taker and maker order...
                $taker_funds = (1+$commissionfee/100)*$remaining_quantity*$trade_price;
                $maker_funds = $maker_quantity*$trade_price;

                $assetBalanceTable->setAssetBalance($this->taker_order['customer_id'], $this->taker_order['side'], $this->taker_order['want_asset'], $this->taker_order['offer_asset'], $remaining_quantity, $taker_funds);
                $assetBalanceTable->setAssetBalance($maker['customer_id'], $this->taker_order['side'], $this->taker_order['want_asset'], $this->taker_order['offer_asset'], $remaining_quantity, $maker_funds);

                $taker_order = $this->taker_order;
                $taker_order['remaining_quantity'] = 0;
                $taker_order['filled_quantity'] = $this->taker_order['quantity'];
                $taker_order['created_at'] = Common::current_utc_date();
                $taker_order['updated_at'] = Common::current_utc_date();
                unset($taker_order['price']);
                unset($taker_order['status']);
                $closed_makers[] = $taker_order;

                $remaining_quantity = 0;
            }
            $commission_data['commission'] = $commission_data['quantity']*$trade_price*$commissionfee/100;
            $commission_data['fee'] = $commissionfee;

            // add processor about commssion data
            $commission_datas[] = $commission_data;

            $trade_history_data['want_asset'] = $maker['want_asset'];
            $trade_history_data['offer_asset'] = $maker['offer_asset'];
            $trade_history_data['side'] = $maker['side'];
            $trade_history_data['time'] = Common::current_utc_date();
//            $trade_price = $this->getFirstMakerPrice();
            $trade_data[] = $trade_history_data;
        }

        $tradehistoryTable = new TradehistoryTable();
        $closedorderbookTable = new ClosedordersTable();
        $commissionTable = new CommissionTable();
        $orderbookTable = new OrderbookTable();

        $tradehistoryTable->setCollection($this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        $closedorderbookTable->setCollection($this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        $commissionTable->setCollection($this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        $orderbookTable->setCollection($this->taker_order['want_asset'], $this->taker_order['offer_asset']);

        if ($trade_data )$tradehistoryTable->insert( $trade_data );
        if ($commission_datas) $commissionTable->insert( $commission_datas );
        if ($closed_makers) $closedorderbookTable->insert( $closed_makers );
        if ($removed_makers) $orderbookTable->whereIn('_id', $removed_makers)->delete();

        $orderTable = new OrderTable();
        $orderTable->setCollection($this->taker_order['want_asset'],$this->taker_order['offer_asset']);

        $tmp = $orderTable->getOrderListOnMarketCondition('sell', $trade_price, $this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        if (!is_null($tmp)) $orderbookTable->insert( $tmp );
    }
    private function exchangeForSell() {
        $assetBalanceTable = new AssetBalanceTable();

        $commissionfee = Common::getCommission($this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        $remaining_quantity = $this->taker_order['remaining_quantity'];
        $trade_price = $this->getFirstMakerPrice();

        /**
         *** definition of array variable for exchange
         **/
        $trade_data = array();
        $trade_history_data = array();
        $removed_makers = array();
        $closed_makers = array();
        $commission_datas = array();
        /****/

        while( $remaining_quantity > 0 && !is_null($trade_price) ) {
            $maker = $this->maker_order[0];
            ( isset( $maker['remaining_quantity'] ) ) ? $maker_quantity = $maker['remaining_quantity'] :
                $maker_quantity = $maker['quantity'];
            $trade_price = $maker['limit_price'];
            $trade_history_data['price'] = $trade_price;

            $commission_data = array();
            $commission_data['taker_customer_id'] = $this->taker_order['customer_id'];
            $commission_data['maker_customer_id'] = $maker['customer_id'];
            $commission_data['want_asset'] = $this->taker_order['want_asset'];
            $commission_data['offer_asset'] = $this->taker_order['offer_asset'];
            $commission_data['time'] = Common::current_utc_date();

            if ( $maker_quantity <= $remaining_quantity ) {
                $removed_maker = array_shift($this->maker_order);
                $removed_maker['filled_quantity'] = $maker_quantity;
                $removed_maker['remaining_quantity'] = 0;
                $removed_maker['created_at'] = Common::current_utc_date();
                $removed_maker['updated_at'] = Common::current_utc_date();
                $removed_makers[] = $removed_maker['_id'];
                $commission_data['quantity'] = $maker_quantity;
                // Asset re-balancing processor for taker and maker order...
                $taker_funds = (1+$commissionfee/100)*$maker_quantity*$trade_price;
                $maker_funds = $maker_quantity*$trade_price;
                $trade_history_data['quantity'] = $maker_quantity;
                unset($removed_maker['_id']);
                $closed_makers[] = $removed_maker;

                $assetBalanceTable->setAssetBalance($this->taker_order['customer_id'], $this->taker_order['side'], $this->taker_order['want_asset'], $this->taker_order['offer_asset'], $maker_quantity, $taker_funds);
                $assetBalanceTable->setAssetBalance($maker['customer_id'], $this->taker_order['side'],$this->taker_order['want_asset'], $this->taker_order['offer_asset'], $maker_quantity, $maker_funds);

                $remaining_quantity -= $maker_quantity*1;
            }
            else {
                $maker['quantity'] -= $remaining_quantity;
                $trade_history_data['quantity'] = $remaining_quantity;
                ( !isset($maker['filled_quantity']) ) ? $f_size = $remaining_quantity : $f_size = $maker['filled_quantity']*1 + $remaining_quantity;

                $r_size = $maker['remaining_quantity']*1 - $remaining_quantity;

                // update filled/remaining quantity of current maker with f_size/r_size when maker quantity is greater than remain taker quantity.
                $_id = $maker['_id'];
                $maker_update_data = array('remaining_quantity'=>$r_size, 'filled_quantity'=>$f_size, 'updated_at'=>Common::current_utc_date());
                Orderbook::updateOrder($maker['want_asset'], $maker['offer_asset'], $_id, $maker_update_data);

                // asset commission processing for trading...
                $commission_data['quantity'] = $remaining_quantity;
                // Asset rebalacing processor for taker and maker order...
                $taker_funds = (1+$commissionfee/100)*$remaining_quantity*$trade_price;
                $maker_funds = $maker_quantity*$trade_price;

                $assetBalanceTable->setAssetBalance($this->taker_order['customer_id'], $this->taker_order['side'], $this->taker_order['want_asset'], $this->taker_order['offer_asset'], $remaining_quantity, $taker_funds);
                $assetBalanceTable->setAssetBalance($maker['customer_id'], $this->taker_order['side'], $this->taker_order['want_asset'], $this->taker_order['offer_asset'], $remaining_quantity, $maker_funds);

                $taker_order = $this->taker_order;
                $taker_order['remaining_quantity'] = 0;
                $taker_order['filled_quantity'] = $this->taker_order['quantity'];
                $taker_order['created_at'] = Common::current_utc_date();
                $taker_order['updated_at'] = Common::current_utc_date();
                unset($taker_order['price']);
                unset($taker_order['status']);
                $closed_makers[] = $taker_order;

                $remaining_quantity = 0;
            }
            $commission_data['commission'] = $commission_data['quantity']*$commissionfee/100;
            $commission_data['fee'] = $commissionfee;

            // add processor about commssion data
            $commission_datas[] = $commission_data;

            $trade_history_data['want_asset'] = $maker['want_asset'];
            $trade_history_data['offer_asset'] = $maker['offer_asset'];
            $trade_history_data['side'] = $maker['side'];
            $trade_history_data['time'] = Common::current_utc_date();
//            $trade_price = $this->getFirstMakerPrice();
            $trade_data[] = $trade_history_data;
        }

        $tradehistoryTable = new TradehistoryTable();
        $closedorderbookTable = new ClosedordersTable();
        $commissionTable = new CommissionTable();
        $orderbookTable = new OrderbookTable();

        $tradehistoryTable->setCollection($this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        $closedorderbookTable->setCollection($this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        $commissionTable->setCollection($this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        $orderbookTable->setCollection($this->taker_order['want_asset'], $this->taker_order['offer_asset']);

        if ($trade_data )$tradehistoryTable->insert( $trade_data );
        if ($commission_datas) $commissionTable->insert( $commission_datas );
        if ($closed_makers) $closedorderbookTable->insert( $closed_makers );
        if ($removed_makers) $orderbookTable->whereIn('_id', $removed_makers)->delete();

        $orderTable = new OrderTable();
        $orderTable->setCollection($this->taker_order['want_asset'],$this->taker_order['offer_asset']);

        $tmp = $orderTable->getOrderListOnMarketCondition('buy', $trade_price, $this->taker_order['want_asset'], $this->taker_order['offer_asset']);
        if (!is_null($tmp))
            $orderbookTable->insert( $tmp );
    }
}
