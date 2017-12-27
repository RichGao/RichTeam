<?php

namespace App\Models;

use App\Models\BaseModel;

class TradehistoryTable extends BaseModel
{
    //
    protected $collection = 'tradehistory';

    public function setCollection( $want_asset, $offer_asset, $date=null ) {
        if (is_null($date)) $date = date('Y-m-d');
        $this->collection .= '_'.$want_asset.'_'.$offer_asset.'_'.$date;
    }
}
