<?php

namespace App\Http\Controllers\Exchange;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PairModel;
use App\Models\OrderTable;

class CentralizedController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }
    public function index() {
        $erc20_url = env('ERC20_URL');
        $pairModel = new PairModel();
        $pairList = $pairModel->getAssetPairList();

        return view('centralized.index')->with(['erc20_url'=>$erc20_url, 'pairList'=>$pairList]);
    }
}
