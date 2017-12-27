<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class APIController extends Controller
{
    //
    public function getERC20Tokens() {
        header('Access-Control-Allow-Origin:*');
        header('Content-type:application/json');
        $dec_file_link = '/config/erc20_config.json';
        $token_arr = array();
        try{
            $dec_file_contents = json_decode(\File::get(base_path().$dec_file_link), true);
            $token_arr = $dec_file_contents['tokens'];
            array_shift($token_arr);
        }
        catch(Exception $exption) {

        }
        echo json_encode($token_arr);
    }
}
