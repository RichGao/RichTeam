<?php

namespace App\Http\Controllers;

use App\Models\PairModel;
use Illuminate\Http\Request;

use App\Models\OrderTable;

class IndexController extends Controller
{
    //
    public function index() {
        $erc20_url = env('ERC20_URL');
        $pairModel = new PairModel();
        $pairList = $pairModel->getAssetPairList();

        return view('index.index')->with(['erc20_url'=>$erc20_url, 'pairList'=>$pairList]);
    }
}
