<?php

namespace App\Models;

use App\Models\BaseModel;

class CommissionTable extends BaseModel
{
    //
    protected $collection = 'commission';

    public function setCollection( $want_asset, $offer_asset ) {
        $this->collection .= '_'.$want_asset.'_'.$offer_asset;
    }

}
