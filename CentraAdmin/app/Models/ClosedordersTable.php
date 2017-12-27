<?php

namespace App\Models;

use App\Models\BaseModel;

class ClosedordersTable extends BaseModel
{
    //
    protected $collection = 'closed_orderbook';

    public function setCollection( $want_asset, $offer_asset ) {
        $this->collection .= '_'.$want_asset.'_'.$offer_asset;
    }

    public function addNewClosedOrderbook( $new_closed_orderbook ) {
        $this->insert($new_closed_orderbook);
    }
}
