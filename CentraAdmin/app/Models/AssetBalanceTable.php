<?php

namespace App\Models;

use App\Library\Common;
use App\Models\BaseModel;

class AssetBalanceTable extends BaseModel
{
    //
    protected $collection = 'asset_balance';

    public function getAssetBalance( $customer_id, $asset ) {
        $asset_data = $this->where('customer_id', $customer_id)->where('asset', $asset)->first();
        if (is_null($asset_data)) return 0;
        return $asset_data->balance;
    }

    public function setAssetBalance( $customer_id, $side, $want_asset, $offer_asset, $want_asset_balance, $offer_asset_balance ) {
        $updated_at = Common::current_utc_date();
        if ( $side == 'buy' ) {
            $this->where('customer_id', $customer_id)->where('asset', $want_asset)->increment('balance', $want_asset_balance);
            $this->where('customer_id', $customer_id)->where('asset', $offer_asset)->decrement('balance', $offer_asset_balance);
        }
        else {
            $this->where('customer_id', $customer_id)->where('asset', $want_asset)->decrement('balance', $want_asset_balance);
            $this->where('customer_id', $customer_id)->where('asset', $offer_asset)->increment('balance', $offer_asset_balance);
        }
    }
}
