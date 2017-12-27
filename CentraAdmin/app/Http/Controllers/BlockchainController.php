<?php

namespace App\Http\Controllers;

use App\Library\Common;
use App\Library\BlockcypherWalletMng;
use App\Models\UserWalletInfo;
use BitWasp\Bitcoin\Block\Block;
use ctur\sdk\rest\ripple\lib\Enum;
use ctur\sdk\rest\ripple\Ripple;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\ErrorCorrectionLevel;
use Graze\GuzzleHttp\JsonRpc\Client;
use Monero\Wallet;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client as GuzzleClient;

class BlockchainController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    public function generate($coin) {

        $type = request()->get('type');
        $blockcypher_coin_arr = array('btc','ltc','dash');
        $blockModel = new BlockcypherWalletMng();
        $newAddress = null;
        $user = \Auth::user();
//        $coin = strtolower($coin);
        $ret_arr = array();

        $newAddress = Common::genAddressOfCoin($coin);
//        if ( in_array($coin, $blockcypher_coin_arr) ) {  // BTC, LTC, DASH
//            $blockModel->setWalletType($coin);
//            $ret = $blockModel->generateAddress();
////            $wallet = $blockModel->createWallet();
//            $newAddress = $ret['address'];
//        }
//        if ( $coin == 'xmr' ) {  //MONERO
//
//            $monero_host = 'http://127.0.0.1';
//            $port = 18082;
//            $wallet = new Wallet($monero_host, $port);
//                $body = ['method'=>'make_integrated_address'];
//                $addr = $wallet->_request($body);
////                $address = $wallet->integratedAddress();
////            $addr = $wallet->getAddress();
//            var_dump(print_r($addr, true));exit;
//        }
//        if ( $coin == 'xrp' ) {
////            $result = Ripple::factory(Enum::ACCOUNT, 'https://api.ripple.com/v1')->generateWallet();
////            var_dump(print_r($result, true));exit;
//        }
        if ( $newAddress != null ) {
            $qrCode = new QrCode($newAddress);
            $qrCode->setSize(500);
            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
            $store_qrcode_file_name = $user->name.date("YmdHis");
            header('Content-Type: '.$qrCode->getContentType());
            $dir_url = public_path()."/assets/qrcode/{$user->name}/{$type}/";
            $url = $dir_url.$store_qrcode_file_name.".png";
            if (!file_exists($dir_url)) {
                \File::makeDirectory($dir_url, 0777, true);
            }
            $qrCode->writeFile($url);
            $userWalletInfo = new UserWalletInfo();
            $user_wallet_info = $userWalletInfo->where('user_id',$user->_id)->where('user_email',$user->email)->where('coin',$coin)->first();
            if ( is_null($user_wallet_info) ) {
                $userWalletInfo->user_id = $user->_id;
                $userWalletInfo->user_email = $user->email;
                $userWalletInfo->coin = $coin;
                $userWalletInfo->address = $newAddress;
                $userWalletInfo->qrcode_filename = $store_qrcode_file_name;
                $userWalletInfo->save();
                $ret_arr = array('qr_link'=>"/assets/qrcode/{$user->name}/{$type}/{$store_qrcode_file_name}.png", 'address'=>$newAddress);
            }
            else {
                $user_wallet_info->address = $newAddress;
                $user_wallet_info->qrcode_filename = $store_qrcode_file_name;
                $user_wallet_info->save();

                $ret_arr = array('qr_link'=>"/assets/qrcode/{$user->name}/{$type}/{$store_qrcode_file_name}.png", 'address'=>$newAddress);
            }
        }
        else {
            $qrCode = new QrCode("Unalloced Address");
            $qrCode->setSize(500);
            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
            $store_qrcode_file_name = 'qrCode';
            header('Content-Type: '.$qrCode->getContentType());
            $dir_url = public_path()."/assets/qrcode/";
            $url = $dir_url.$store_qrcode_file_name.".png";
            if (!file_exists($dir_url)) {
                \File::makeDirectory($dir_url, 0777, true);
            }
            $qrCode->writeFile($url);
            $ret_arr = array('qr_link'=>"/assets/qrcode/{$store_qrcode_file_name}.png", 'address'=>'FAIL');
        }



        echo json_encode($ret_arr);
    }
    public function getUserWalletInfo($coin) {
        $type = request()->get('type');
        $user = \Auth::user();
        $ret_arr = array();
        $userWalletInfo = new UserWalletInfo();
        $user_wallet_info = $userWalletInfo->where('user_id',$user->_id)->where('user_email',$user->email)->where('coin',$coin)->first();
        if ( is_null($user_wallet_info) ) {
            $qrCode = new QrCode("Unalloced Address");
            $qrCode->setSize(500);
            $qrCode->setErrorCorrectionLevel(ErrorCorrectionLevel::HIGH);
            $store_qrcode_file_name = 'qrCode';
            header('Content-Type: '.$qrCode->getContentType());
            $dir_url = public_path()."/assets/qrcode/";
            $url = $dir_url.$store_qrcode_file_name.".png";
            if (!file_exists($dir_url)) {
                \File::makeDirectory($dir_url, 0777, true);
            }
            $qrCode->writeFile($url);
            $ret_arr = array('qr_link'=>"/assets/qrcode/{$store_qrcode_file_name}.png", 'address'=>'FAIL');
        }
        else {
            $newAddress = $user_wallet_info->address;
            $store_qrcode_file_name = $user_wallet_info->qrcode_filename;
            $ret_arr = array('qr_link'=>"/assets/qrcode/{$user->name}/{$type}/{$store_qrcode_file_name}.png", 'address'=>$newAddress);
        }
        echo json_encode($ret_arr);
    }
}
