@extends('layouts.centra')

@section('content')
    <link id="stylesheet" rel="stylesheet" type="text/css" href="{{ asset('./assets/ext/css/zInput_default_stylesheet.css') }}">
    <link href="./assets/erc20/css/black.css" rel="stylesheet" id="stylesheet" />
    <link href="./assets/erc20/css/extend-centra.css" rel="stylesheet" type="text/css" />
    <div class="row centra-padding-5">
        <!-- left menu starts -->
        <div class="col-sm-6 centra-asset-pair-container centra-padding-5">
            <div id="pair1" class="pair1">
                <input type="radio" name="set2" title="Centra" coin="ctr" src="{{ asset('./images/CTR.png') }}">
                <input type="radio" name="set2" title="Bitcoin" coin="btc" src="{{ asset('./images/btc.png') }}">
                <input type="radio" name="set2" title="Ethereum" coin="eth" src="{{ asset('./images/ethereum.png') }}">
                <input type="radio" name="set2" title="Litecoin" coin="ltc" src="{{ asset('./images/ripple.png') }}">
                <input type="radio" name="set2" title="Ripple" coin="xrp" src="{{ asset('./images/litecoin.png') }}">
                <input type="radio" name="set2" title="Dash" coin="dash" src="{{ asset('./images/dash.png') }}">
                <input type="radio" name="set2" title="Monero" coin="xmr" src="{{ asset('./images/xmr.png') }}">
                <input type="radio" name="set2" title="Zcash" coin="zec" src="{{ asset('./images/zec.png') }}">
            </div>
        </div>
        <div class="col-sm-6 centra-asset-pair-container centra-padding-5">
            <div id="pair2" class="pair2">
                <input type="checkbox" name="set3" title="Centra" coin="ctr" src="{{ asset('./images/CTR.png') }}">
                <input type="checkbox" name="set3" title="Bitcoin" coin="btc" src="{{ asset('./images/btc.png') }}">
                <input type="checkbox" name="set3" title="Ethereum" coin="eth" src="{{ asset('./images/ethereum.png') }}">
                <input type="checkbox" name="set3" title="Litecoin" coin="ltc" src="{{ asset('./images/ripple.png') }}">
                <input type="checkbox" name="set3" title="Ripple" coin="xrp" src="{{ asset('./images/litecoin.png') }}">
                <input type="checkbox" name="set3" title="Dash" coin="dash" src="{{ asset('./images/dash.png') }}">
                <input type="checkbox" name="set3" title="Monero" coin="xmr" src="{{ asset('./images/xmr.png') }}">
                <input type="checkbox" name="set3" title="Zcash" coin="zec" src="{{ asset('./images/zec.png') }}">
            </div>
        </div>
        <!--/span-->
        <!-- left menu ends -->
    </div>
    <script src="{{ asset('./assets/ext/zInput.js') }}"></script>
    <script>
        $("#pair1").zInput();
        $("#pair2").zInput();
    </script>
    <script src="{{ asset('./js/admin/pairmng.js') }}" language="JavaScript" ></script>
@endsection