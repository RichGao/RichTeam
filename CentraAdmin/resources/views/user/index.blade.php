@extends('layouts.app')

@section('content')

<link href="{{ asset('css/cryptocoins.css') }}" rel="stylesheet">
<link href="{{ asset('js/user/account.css') }}" rel="stylesheet">
<link href="./assets/erc20/css/black.css" rel="stylesheet" id="stylesheet" />
<link href="./assets/erc20/css/extend-centra.css" rel="stylesheet" type="text/css" />
<style>
    form{
        /*color:green;*/
    }
</style>
<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#centralized_tab" class="centra-a">Centralized</a></li>
            <li><a data-toggle="tab" href="#decentralized_tab" class="centra-a">Decentralized</a></li>
        </ul>

        <div class="tab-content">
            <div id="centralized_tab" class="tab-pane fade in active">
                <div class="ch-container">
                    <div class="row">

                        <!-- left menu starts -->
                        <div class="col-sm-4 col-lg-3 centra-asset-container">
                            <div class="sidebar-nav">
                                <div class="nav-canvas">
                                    <div class="nav-sm nav nav-stacked">

                                    </div>
                                    <ul class="nav nav-pills nav-stacked main-menu">
                                        <li class="nav-header">Assets</li>
                                        <li><a class="ajax-link centra-a-link" coin="ctr"><img src="{{ asset('./images/CTR.png') }}" class="centra-log-img" /><span> &nbsp;&nbsp;&nbsp;Centra</span><span class="centra-asset-span" id="ctr_asset_balance"><?php echo rand(10,15) ?></span></a></li>
                                        <li><a class="ajax-link centra-a-link" coin="btc"><img src="{{ asset('./images/btc.png') }}" class="centra-log-img" /><span> &nbsp;&nbsp;&nbsp;Bitcoin</span><span class="centra-asset-span" id="ctr_asset_balance"><?php echo rand(1,300) ?></span></a></li>
                                        <li><a class="ajax-link centra-a-link" coin="eth"><img src="{{ asset('./images/ethereum.png') }}" class="centra-log-img" /><span> &nbsp;&nbsp;&nbsp;Ethereum</span><span class="centra-asset-span" id="eth_asset_balance"><?php echo rand(1,300) ?></span></a></li>
                                        <li><a class="ajax-link centra-a-link" coin="xrp"><img src="{{ asset('./images/ripple.png') }}" class="centra-log-img" /><span> &nbsp;&nbsp;&nbsp;Ripple</span><span class="centra-asset-span" id="xrp_asset_balance"><?php echo rand(1,300) ?></span></a></li>
                                        <li><a class="ajax-link centra-a-link" coin="ltc"><img src="{{ asset('./images/litecoin.png') }}" class="centra-log-img" /><span> &nbsp;&nbsp;&nbsp;Litecoin</span><span class="centra-asset-span" id="ltc_asset_balance"><?php echo rand(1,300) ?></span></a></li>
                                        <li><a class="ajax-link centra-a-link" coin="dash"><img src="{{ asset('./images/dash.png') }}" class="centra-log-img" /><span> &nbsp;&nbsp;&nbsp;Dash</span><span class="centra-asset-span" id="dash_asset_balance"><?php echo rand(1,300) ?></span></a></li>
                                        <li><a class="ajax-link centra-a-link" coin="xmr"><img src="{{ asset('./images/xmr.png') }}" class="centra-log-img" /><span> &nbsp;&nbsp;&nbsp;Monero</span><span class="centra-asset-span" id="xmr_asset_balance"><?php echo rand(1,300) ?></span></a></li>
                                        <li><a class="ajax-link centra-a-link" coin="zec"><img src="{{ asset('./images/zec.png') }}" class="centra-log-img" /><span> &nbsp;&nbsp;&nbsp;Zcash</span><span class="centra-asset-span" id="zec_asset_balance"><?php echo rand(1,300) ?></span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-lg-9">
                            <div class="panel centra-panel">
                                <div class="panel-heading">Deposit</div>
                                <div class="panel-body" style="min-height:350px;">
                                    <label >Use the following data to credit your account with <span id="selectedCoin_span">Bitcoin</span>:</label>
                                    <div class="deposit_spin" id="deposit_spin">
                                        <div class="circle">
                                            <div class="borderCircle"></div>
                                            <div class="borderCircle2"></div>
                                            <div class="borderCircle3"></div>
                                            <div class="innerCircle">
                                                <p>Generating...</p>
                                            </div>
                                            <div class="outerCirlce"></div>
                                        </div>
                                    </div>
                                    <div class="row" id="deposit_area" style="display:none;">
                                        <div class="col-md-5 centra-paddiing">
                                            <img class="centra-img-qrcode" />
                                        </div>
                                        <div class="col-md-7 centra-paddiing">
                                            <form>
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-success" id="btn_create_new_address">Create New</button>
                                                </div>
                                                <div class="form-group">
                                                    <label for="deposit_wallet_address">Wallet Address</label>
                                                    <input id="deposit_wallet_address" type="text" class="form-control centra-input" name="deposit_wallet_address" placeholder="">
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                {{--<div class="panel-footer">Panel Footer</div>--}}
                            </div>
                            <div class="panel centra-panel" style="margin-top:10px;">
                                <div class="panel-heading">Withdraw</div>
                                <div class="panel-body">
                                    <label >To withdraw <span id="selectedwithdrawCoin_span">Bitcoin</span>, enter your <span id="selectedwithdrawOriCoin_span">Bitcoin</span> address in the form.</label>
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Amount</label>
                                            <div class="col-sm-6">
                                                <input class="form-control centra-input text-right" id="withdraw_amount" type="number" placeholder="amount...">
                                            </div>
                                            <label class="col-sm-4 control-label text-left" style="text-align: left;">You will receive : <span id="real_receive_amount"></span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">flat fee  -<span id="flat_fee"></span><span id="coin_unit"></span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 text-right" for="withdraw_wallet_address">Wallet Address</label>
                                            <div class="col-sm-7">
                                                <input id="withdraw_wallet_address" type="text" class="form-control centra-input" name="withdraw_wallet_address" placeholder="0x1Msd3">
                                            </div>
                                            <button type="button" class="btn btn-danger col-sm-2">Withdraw</button>
                                        </div>
                                    </form>

                                </div>
                                {{--<div class="panel-footer">Panel Footer</div>--}}
                            </div>
                        </div>
                        <!--/span-->
                        <!-- left menu ends -->
                    </div>
                </div>
            </div>
            <div id="decentralized_tab" class="tab-pane fade">
                <div class="ch-container">
                    <div class="row">
                        <!-- left menu starts -->
                        <div class="col-sm-4 col-lg-3 centra-asset-container">
                            <div class="sidebar-nav sidebar-nav-decent">
                                <div class="nav-canvas centra-scroll">
                                    <div class="nav-sm nav nav-stacked">

                                    </div>
                                    <ul class="nav nav-pills nav-stacked main-menu">
                                        <li class="nav-header">Tokens</li>
                                        @if( $token_arr )
                                            @foreach( $token_arr as $token )
                                                <li>
                                                    <a class="ajax-link centra-a-link erc20" coin="{{ $token['name'] }}" address="{{ $token['addr'] }}">
                                                        <img src="{{ asset('./images/ERC20_btn.png') }}" class="centra-log-img" />
                                                        <span> &nbsp;&nbsp;&nbsp;{{ $token['name'] }}</span>
                                                        <span class="centra-asset-span" id="ctr_asset_balance"><?php echo rand(1,300); ?></span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-lg-9">
                            <div class="panel centra-panel">
                                <div class="panel-heading">Deposit</div>
                                <div class="panel-body">
                                    <label >Use the following data to credit your account with <span id="selectedERCCoin_span">PLU</span>:</label>
                                    <div class="row">
                                        <div class="col-md-5 centra-paddiing">
                                            <img class="centra-img-qrcode centra-erc20-img-qrcode" />
                                        </div>
                                        <div class="col-md-7 centra-paddiing">
                                            <form>
                                                <div class="form-group">
                                                    <button type="button" class="btn btn-success" id="btn_erc20_create_new_address">Create New</button>
                                                </div>
                                                <div class="form-group">
                                                    <label for="deposit_erc20_wallet_address">Wallet Address</label>
                                                    <input id="deposit_erc20_wallet_address" type="text" class="form-control centra-input" name="deposit_erc20_wallet_address" placeholder="">
                                                </div>
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                {{--<div class="panel-footer">Panel Footer</div>--}}
                            </div>
                            <div class="panel centra-panel" style="margin-top:10px;">
                                <div class="panel-heading">Withdraw</div>
                                <div class="panel-body">
                                    <label >To withdraw <span id="selectedwithdrawCoin_span">Bitcoin</span>, enter your <span id="selectedwithdrawOriCoin_span">Bitcoin</span> address in the form.</label>
                                    <form class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Amount</label>
                                            <div class="col-sm-6">
                                                <input class="form-control centra-input text-right" id="withdraw_amount" type="number" placeholder="amount...">
                                            </div>
                                            <label class="col-sm-4 control-label text-left" style="text-align: left;">You will receive : <span id="real_receive_amount"></span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">flat fee  -<span id="flat_fee"></span><span id="coin_unit"></span></label>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 text-right" for="withdraw_wallet_address">Wallet Address</label>
                                            <div class="col-sm-7">
                                                <input id="withdraw_wallet_address" type="text" class="form-control centra-input" name="withdraw_wallet_address" placeholder="0x1Msd3">
                                            </div>
                                            <button type="button" class="btn btn-danger col-sm-2">Withdraw</button>
                                        </div>
                                    </form>

                                </div>
                                {{--<div class="panel-footer">Panel Footer</div>--}}
                            </div>
                        </div>
                        <!--/span-->
                        <!-- left menu ends -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script src="{{ asset('./js/user/user.js') }}" language="JavaScript" />
    <script src="{{ asset('./theme/js/charisma.js') }}"></script>
@endsection