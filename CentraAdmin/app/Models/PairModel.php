<?php

namespace App\Models;

use App\Models\BaseModel;

class PairModel extends BaseModel
{
    //
    protected $collection = 'exchange_pair_list';

    public function getAssetPairList() {
        return $this->select()->orderBy('want_asset', 'asc')->orderBy('offer_asset', 'asc')->get()->toArray();
    }
}
