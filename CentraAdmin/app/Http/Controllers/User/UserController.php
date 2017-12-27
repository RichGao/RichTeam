<?php

namespace App\Http\Controllers\User;

use App\Models\User;
use Endroid\QrCode\ErrorCorrectionLevel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Endroid\QrCode\QrCode;
use App\Http\Controllers\Auth;

class UserController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index() {
        $dec_file_link = '/config/erc20_config.json';
        $token_arr = array();
        try{
            $dec_file_contents = json_decode(\File::get(base_path().$dec_file_link), true);
            $token_arr = $dec_file_contents['tokens'];
            array_shift($token_arr);
        }
        catch(Exception $exption) {

        }
        return view('user.index')->with(['token_arr'=>$token_arr]);
    }
    public function showLoginForm() {
        return view('user.login');
    }
    public function login(Request $request) {
        $request->session()->regenerate();

        $useremail = request()->get('email');
        $userpasswd = request()->get('passwd');

//        $userModel = new User();
//        $data = $userModel->where('email', $useremail)->first();
//        if ( $data ) {
//            if ( $data['password'] == bcrypt($userpasswd) ) {
                return redirect('/home');
//            }
//            return 'invalid Userpassword';
//        }
//        return 'invalid useremail and password';
    }

    public function generateqrcode( Request $request ) {
        $type = $request->type;
        $coin = $request->coin;
        $user = \Auth::user();
        $qrCode = new QrCode($request->wallet_address);
        $qrCode->setSize(500);
        $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);

        $store_qrcode_file_name = date("YmdHis");
        $userWalletInfo = new UserWalletInfo();
        $user_wallet_info = $userWalletInfo->where('user_id',$user->_id)->where('user_email',$user->email)->where('coin',$coin)->first();

        header('Content-Type: '.$qrCode->getContentType());
        $dir_url = public_path()."/assets/qrcode/{$user->name}/{$type}/";
        $url = $dir_url.$request->wallet_address.".png";
        if (!file_exists($dir_url)) {
            \File::makeDirectory($dir_url, 0777, true);
        }
        $qrCode->writeFile($url);
        echo "/assets/qrcode/{$user->name}/{$type}/{$request->wallet_address}.png";
        exit;
    }
}
