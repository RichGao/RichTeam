@extends('layouts.centra')

@section('content')

    <link href="{{ asset('css/cryptocoins.css') }}" rel="stylesheet">
    <link href="{{ asset('js/user/account.css') }}" rel="stylesheet">

    <link id="stylesheet" rel="stylesheet" type="text/css" href="{{ asset('./assets/ext/css/zInput_default_stylesheet.css') }}">
    <script src="{{ asset('./assets/ext/zInput.js') }}"></script>

    {{--<div class="row">--}}
        {{--<div class="col-md-12">--}}
            {{--<ul class="nav nav-tabs">--}}
                {{--<li class="active"><a data-toggle="tab" href="#centralized_tab" class="centra-a">Centralized</a></li>--}}
                {{--<li><a data-toggle="tab" href="#decentralized_tab" class="centra-a">Decentralized</a></li>--}}
            {{--</ul>--}}

            {{--<div class="tab-content">--}}
                {{--<div id="centralized_tab" class="tab-pane fade in active">--}}
                    <div class="ch-container">
                        <div class="row">
                            <!-- left menu starts -->
                            <div class="col-sm-6 col-lg-6 centra-asset-pair-container">
                                <div id="pair1" class="pair1">
                                    <input type="radio" name="set2" title="Centra" coin="ctr" src="{{ asset('./images/CTR.png') }}">
                                    <input type="radio" name="set2" title="Bitcoin" coin="btc" src="{{ asset('./images/btc.png') }}">
                                    <input type="radio" name="set2" title="Ethereum" coin="eth" src="{{ asset('./images/ethereum.png') }}">
                                    <input type="radio" name="set2" title="Litecoin" coin="xrp" src="{{ asset('./images/ripple.png') }}">
                                    <input type="radio" name="set2" title="Ripple" coin="ltc" src="{{ asset('./images/litecoin.png') }}">
                                    <input type="radio" name="set2" title="Dash" coin="dash" src="{{ asset('./images/dash.png') }}">
                                    <input type="radio" name="set2" title="Monero" coin="xmr" src="{{ asset('./images/xmr.png') }}">
                                    <input type="radio" name="set2" title="Zcash" coin="zec" src="{{ asset('./images/zec.png') }}">
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-6 centra-asset-pair-container">
                                <div id="pair2" class="pair2">
                                    <input type="radio" name="set3" title="Centra" coin="ctr" src="{{ asset('./images/CTR.png') }}">
                                    <input type="radio" name="set3" title="Bitcoin" coin="btc" src="{{ asset('./images/btc.png') }}">
                                    <input type="radio" name="set3" title="Ethereum" coin="eth" src="{{ asset('./images/ethereum.png') }}">
                                    <input type="radio" name="set3" title="Litecoin" coin="xrp" src="{{ asset('./images/ripple.png') }}">
                                    <input type="radio" name="set3" title="Ripple" coin="ltc" src="{{ asset('./images/litecoin.png') }}">
                                    <input type="radio" name="set3" title="Dash" coin="dash" src="{{ asset('./images/dash.png') }}">
                                    <input type="radio" name="set3" title="Monero" coin="xmr" src="{{ asset('./images/xmr.png') }}">
                                    <input type="radio" name="set3" title="Zcash" coin="zec" src="{{ asset('./images/zec.png') }}">
                                </div>

                            </div>
                            <!--/span-->
                            <!-- left menu ends -->
                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-sm-12 centra-asset-pair-container" style="margin-top:20px;">
                                <div class="row">
                                    <div class="col-sm-4 text-center">
                                        <img src="" id="img_pair1" class="centra-asset-img" />
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <form id="div_form" class="form-horizontal" action="{{ route('regfee') }}" method="post">
                                            <input type="hidden" name="want_asset" />
                                            <input type="hidden" name="offer_asset" />
                                            <fieldset>
                                                <div class="input-group input-group-lg">
                                                    <span class="input-group-addon"><i class="glyphicon glyphicon-home green"></i></span>
                                                    <input id="fee" type="number" class="form-control text-right"  placeholder="fee" name="fee" min="0" max="1" required autofocus>
                                                </div>
                                                <div class="clearfix"></div><br>
                                                <p class="center col-md-5 input-group-lg">
                                                    <button type="button" class="btn btn-success btn-lg" id="btn_save">Save</button>
                                                </p>
                                                <div class="clearfix"></div><br>
                                            </fieldset>
                                        </form>
                                    </div>
                                    <div class="col-sm-4 text-center">
                                        <img src="" id="img_pair2" class="centra-asset-img"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="alert alert-success" id="div_saving">
                                            <strong>Saving...</strong>
                                        </div>

                                        <div class="alert alert-danger" id="div_alert">
                                            <strong>Alert</strong> Please choise asset to exchange.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 centra-asset-pair-container" style="margin-top:20px;">
                                <div class="row">
                                    <div class="col-md-2 text-center centra-table-hdcell">Want Asset</div>
                                    <div class="col-md-2 text-center centra-table-hdcell">Offer Asset</div>
                                    <div class="col-md-2 text-center centra-table-hdcell">Fee</div>
                                    <div class="col-md-2 text-center centra-table-hdcell">User</div>
                                    <div class="col-md-2 text-center centra-table-hdcell">Updated Date</div>
                                    <div class="col-md-2 text-center centra-table-hdcell">Created Date</div>
                                </div>
                                @if ( $exhfee_datas )
                                    @foreach($exhfee_datas as $data)
                                    <div class="row">
                                        <div class="col-md-2 text-center">{{ $data['want_asset'] }}</div>
                                        <div class="col-md-2 text-center">{{ $data['offer_asset'] }}</div>
                                        <div class="col-md-2 text-center">{{ $data['fee'] }}</div>
                                        <div class="col-md-2 text-center">{{ $data['admin_name'] }}</div>
                                        <div class="col-md-2 text-center">{{ $data['updated_at'] }}</div>
                                        <div class="col-md-2 text-center">{{ $data['created_at'] }}</div>
                                    </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                {{--</div>--}}
                {{--<div id="decentralized_tab" class="tab-pane fade">--}}
                    {{--<div class="ch-container">--}}
                        {{--<div class="row">--}}
                            {{--<!-- left menu starts -->--}}
                            {{--<div class="col-sm-4 col-lg-3 centra-asset-container">--}}
                                {{--<div class="sidebar-nav sidebar-nav-decent">--}}
                                    {{--<div class="nav-canvas centra-scroll">--}}
                                        {{--<div class="nav-sm nav nav-stacked">--}}

                                        {{--</div>--}}
                                        {{--<ul class="nav nav-pills nav-stacked main-menu">--}}
                                            {{--<li class="nav-header">Tokens</li>--}}
                                            {{--<li class="accordion">--}}
                                                {{--<a href="#"><i class="glyphicon glyphicon-plus"></i><span> ERC20 Token</span></a>--}}
                                            {{--</li>--}}

                                        {{--</ul>--}}
                                    {{--</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<div class="col-sm-8 col-lg-9">--}}
                                {{--<div class="panel centra-panel">--}}
                                    {{--<div class="panel-heading">Deposit</div>--}}
                                    {{--<div class="panel-body">--}}
                                        {{--<label >Use the following data to credit your account with <span id="selectedERCCoin_span">PLU</span>:</label>--}}
                                        {{--<div class="row">--}}
                                            {{--<div class="col-md-5 centra-paddiing">--}}
                                                {{--<img class="centra-img-qrcode centra-erc20-img-qrcode" />--}}
                                            {{--</div>--}}
                                            {{--<div class="col-md-7 centra-paddiing">--}}
                                                {{--<form>--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<button type="button" class="btn btn-success" id="btn_erc20_create_new_address">Create New</button>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="form-group">--}}
                                                        {{--<label for="deposit_erc20_wallet_address">Wallet Address</label>--}}
                                                        {{--<input id="deposit_erc20_wallet_address" type="text" class="form-control centra-input" name="deposit_erc20_wallet_address" placeholder="0x1Msd3">--}}
                                                    {{--</div>--}}
                                                {{--</form>--}}

                                            {{--</div>--}}
                                        {{--</div>--}}
                                    {{--</div>--}}
                                    {{--<div class="panel-footer">Panel Footer</div>--}}
                                {{--</div>--}}
                                {{--<div class="panel centra-panel" style="margin-top:10px;">--}}
                                    {{--<div class="panel-heading">Withdraw</div>--}}
                                    {{--<div class="panel-body">--}}
                                        {{--<label >To withdraw <span id="selectedwithdrawCoin_span">Bitcoin</span>, enter your <span id="selectedwithdrawOriCoin_span">Bitcoin</span> address in the form.</label>--}}
                                        {{--<form class="form-horizontal">--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label class="col-sm-2 control-label">Amount</label>--}}
                                                {{--<div class="col-sm-6">--}}
                                                    {{--<input class="form-control centra-input text-right" id="withdraw_amount" type="number" placeholder="amount...">--}}
                                                {{--</div>--}}
                                                {{--<label class="col-sm-4 control-label text-left" style="text-align: left;">You will receive : <span id="real_receive_amount"></span></label>--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label class="col-sm-4 control-label">flat fee  -<span id="flat_fee"></span><span id="coin_unit"></span></label>--}}
                                            {{--</div>--}}
                                            {{--<div class="form-group">--}}
                                                {{--<label class="col-sm-2 text-right" for="withdraw_wallet_address">Wallet Address</label>--}}
                                                {{--<div class="col-sm-7">--}}
                                                    {{--<input id="withdraw_wallet_address" type="text" class="form-control centra-input" name="withdraw_wallet_address" placeholder="0x1Msd3">--}}
                                                {{--</div>--}}
                                                {{--<button type="button" class="btn btn-danger col-sm-2">Withdraw</button>--}}
                                            {{--</div>--}}
                                        {{--</form>--}}

                                    {{--</div>--}}
                                    {{--<div class="panel-footer">Panel Footer</div>--}}
                                {{--</div>--}}
                            {{--</div>--}}
                            {{--<!--/span-->--}}
                            {{--<!-- left menu ends -->--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    <script>

        $("#pair1").zInput();
        $("#pair2").zInput();
    </script>
    <script src="{{ asset('./js/exchangemng/feemng.js') }}" language="JavaScript" />
@endsection