<?php

namespace App\Http\Controllers;

use App\Models\ExhFee;
use App\User;
use Illuminate\Http\Request;
use DB;

class ExchangeMngController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index() {

    }
    public function showFeeRegForm() {
        $ExhFeeDatas = ExhFee::all()->toArray();
        $ret_arr = array();
        foreach($ExhFeeDatas as $data) {
            $user_data = User::find($data['admin_user']);
            $data['admin_name'] = $user_data->name;
            $data['admin_email'] = $user_data->email;
            $ret_arr[] = $data;
        }
        return view('exchangemng.feemng')->with(['exhfee_datas'=>$ret_arr]);
    }
    public function RegFee() {
        $want_asset = request()->get('want_asset');
        $offer_asset = request()->get('offer_asset');
        $fee = request()->get('fee');
        $feeModel = new ExhFee();
        $data = $feeModel->where('want_asset', $want_asset)->where('offer_asset', $offer_asset)->first();
        $data2 = $feeModel->where('want_asset', $offer_asset)->where('offer_asset', $want_asset)->first();
        $adminUser = \Auth::user();
        if ( is_null($data) && is_null($data2) ) {
            $feeModel->want_asset = $want_asset;
            $feeModel->offer_asset = $offer_asset;
            $feeModel->fee = $fee*1;
            $feeModel->admin_user = $adminUser->id;
            $feeModel->save();
        }
        else {
            if ( !is_null($data) ) {
                $data->admin_user = $adminUser->id;
                $data->fee = $fee*1;
                $data->save();
            }
            if ( !is_null($data2) ) {
                $data2->admin_user = $adminUser->id;
                $data2->fee = $fee*1;
                $data2->save();
            }

        }

        return redirect('/showfeemngform');
    }
}
