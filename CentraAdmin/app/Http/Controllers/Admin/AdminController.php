<?php

namespace App\Http\Controllers\Admin;

use App\Models\Erc20Token;
use App\Models\PairModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ExhFee;
use App\User;
use DB;

class AdminController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }
    public function index() {
        $ExhFeeDatas = ExhFee::all()->toArray();
        $ret_arr = array();
        foreach($ExhFeeDatas as $data) {
            $user_data = User::find($data['admin_user']);
            $data['admin_name'] = $user_data->name;
            $data['admin_email'] = $user_data->email;
            $ret_arr[] = $data;
        }
        return view('admin.index')->with(['exhfee_datas'=>$ret_arr]);
    }
    public function pairManagement() {
        return view('admin.pairmng');
    }
    public function pairRegister() {
        $status = request()->get('status');
        $want_asset = request()->get('want_asset');
        $offer_asset = request()->get('offer_asset');
        $register_user_id = \Auth::user()->_id;
        $pairModel = new PairModel();
        if ( $status == 'insert' ) {
            $pairModel->want_asset = $want_asset;
            $pairModel->offer_asset = $offer_asset;
            $pairModel->user = $register_user_id;
            $pairModel->save();
        }
        else {
            $pairModel->where('want_asset', $want_asset)->where('offer_asset', $offer_asset)->where('user', $register_user_id )->delete();
        }
    }
    public function getOfferAsset( $want_asset ) {
        $pairModel = new PairModel();
        $register_user_id = \Auth::user()->_id;
        $offer_asset_data = $pairModel->select('offer_asset')->where('want_asset', $want_asset)->where('user', $register_user_id )->get()->toArray();
        echo json_encode($offer_asset_data);
    }
    public function showErc20tokenForm() {
        return view('admin.registtokeninfo');
    }
    public function registerErc20token() {
        $name = request()->get('name');
        $decimal = request()->get('decimal');
        $addr = request()->get('addr');

        $erc20TokenModel = new Erc20Token();

        $data = $erc20TokenModel->where('name', $name)->where('decimal', $decimal)->where('addr', $addr)->get()->toArray();
        if ( $data ) {
            $data->name = $name;
            $data->addr = $addr;
            $data->decimal = $decimal;
            $data->save();
        }
        else {
            $erc20TokenModel->name = $name;
            $erc20TokenModel->decimal = $decimal;
            $erc20TokenModel->addr = $addr;
            $erc20TokenModel->save();
        }
        echo 'success';
    }
    public function getErc20Tokens() {
        $erc20Model = new Erc20Token();
        $datas = $erc20Model->all()->toArray();
        echo json_encode($datas);
    }
}
