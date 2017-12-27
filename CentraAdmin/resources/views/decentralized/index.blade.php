@extends('layouts.app')
@section('content')
    <script>
        $('#trading_method').html("Decentralized");
        $('input[name="toggle"]').attr('checked', true);
        $('#trading_method').addClass('dec_color');
    </script>
    <link href="./assets/erc20/images/centra-logo.png" rel="icon" />
    {{--<link href="./assets/erc20/css/bootstrap.min.css" rel="stylesheet" />--}}
    <link href="./assets/erc20/css/font-awesome.min.css" rel="stylesheet" />
    <link href="./assets/erc20/css/ie10-viewport-bug-workaround.css" rel="stylesheet" />
    <link href="./assets/erc20/css/alertify.min.css" rel="stylesheet" />
    <link href="./assets/erc20/css/alertify-bootstrap.min.css" rel="stylesheet" />
    <link href="./assets/erc20/css/black.css" rel="stylesheet" id="stylesheet" />
    <link href="./assets/erc20/css/small.css" rel="stylesheet" media="only screen and (max-device-width: 480px)" />

    <link href="./assets/erc20/css/extend-centra.css" rel="stylesheet" type="text/css" />
    <nav class="navbar navbar-default navbar-centra-erc20">
        <div class="container-fluid top-bar" style="padding-left:0px;">
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1" style="padding-left:0px;">
                <div id="volume_btn" class="nav navbar-nav" >
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </div>
                <ul class="nav navbar-nav">
                    <li class="dropdown" id="tokensDropdown"></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown" id="helpDropdown"></li>
                    <li class="dropdown" id="tokenGuidesDropdown"></li>
                    <li class="dropdown" id="connection"></li>
                    <li class="dropdown" id="languages"></li>
                    <li class="dropdown" id="accounts"></li>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <div class="container page-container" style="padding:1px;">
        <div class="row">
            <div class="col-md-2 no-float row-left panel" style="width:378px;">
                <div class="row-container" >
                    <div id="volume" style="display:none;"></div>
                    <div id="balance"></div>
                    <div class="row-box nav-header">
                        <ul class="nav nav-tabs two columns" role="tablist">
                            <li role="presentation" class="active">
                                <a href="#buy" aria-controls="buy" role="tab" data-toggle="tab" class="trn">buy</a>
                            </li>
                            <li role="presentation">
                                <a href="#sell" aria-controls="sell" role="tab" data-toggle="tab" class="trn">sell</a>
                            </li>
                        </ul>
                    </div>
                    <div class="row-box height2 padding">
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="buy"></div>
                            <div role="tabpanel" class="tab-pane" id="sell"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-7 no-float" >
                <div class="row-box" >
                    <div class="col-md-3 no-float panel">
                        <div style="padding: 5px;border-bottom: 1px solid darkgrey;">Order Book</div>
                        <div class="row-box scroll">
                            <div id="orders"></div>
                        </div>
                    </div>
                    <div class="col-md-5 no-float">
                        <div class="row-container">
                            <div class="row-box nav-header">
                                <ul class="nav nav-tabs two columns" role="tablist">
                                    <li role="presentation" class="active"><a href="#chartPrice" aria-controls="chartPrice" role="tab" data-toggle="tab" class="trn">price</a></li>
                                    <li role="presentation"><a href="#chartDepth" aria-controls="chartDepth" role="tab" data-toggle="tab" class="trn">depth</a></li>
                                </ul>
                            </div>
                            <div class="row-box height2">
                                <div class="tab-content" style="height: 100%;">
                                    <div role="tabpanel" class="tab-pane active" id="chartPrice" style="height: 100%; width: 100%; background:transparent;"></div>
                                    <div role="tabpanel" class="tab-pane" id="chartDepth" style="height: 100%; width: 100%; background: transparent;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row-box">
                    <div class="row-header trn">my_transactions</div>
                    <div class="row-box nav-header">
                        <ul class="nav nav-tabs four columns" role="tablist">
                            <li role="presentation" class="active"><a href="#myTrades" aria-controls="myTrades" role="tab" data-toggle="tab" class="trn">trades</a></li>
                            <li role="presentation"><a href="#myOrders" aria-controls="myOrders" role="tab" data-toggle="tab" class="trn">orders</a></li>
                            <li role="presentation"><a href="#myFunds" aria-controls="myFunds" role="tab" data-toggle="tab" class="trn">funds</a></li>
                        </ul>
                    </div>
                    <div class="row-box">
                        <div class="row-box height2 scroll">
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="myTrades"></div>
                                <div role="tabpanel" class="tab-pane" id="myOrders"></div>
                                <div role="tabpanel" class="tab-pane" id="myFunds"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 no-float">
                <div class="row-container">
                    <div class="row-box panel scroll">
                        <div id="trades"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="buyTrade"></div>
    <div id="sellTrade"></div>
    <div id="importAccount"></div>
    <div id="otherToken"></div>
    <div id="gasPrice"></div>
    <div id="tokenGuide"></div>
    <div id="screencast"></div>
    <div id="ledger"></div>

    <script src="./assets/erc20/js/jquery-1.11.1.min.js" type="text/javascript"></script>
    <script src="./assets/erc20/js/jquery.translate.js" type="text/javascript"></script>
    {{--<script src="./assets/erc20/js/bootstrap.min.js" type="text/javascript"></script>--}}
    <script src="./assets/erc20/js/alertify.min.js" type="text/javascript"></script>
    <script src="./assets/erc20/js/ejs_production.js" type="text/javascript"></script>
    <script src="./assets/erc20/js/chrome-u2f-api.js" type="text/javascript"></script>
    <script src="./assets/erc20/js/ledger.min.js" type="text/javascript"></script>
    <script src="./assets/erc20/js/main.js" type="text/javascript"></script>
    <!-- <script src="/js/555.js" type="text/javascript"></script> -->
    <script>
        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
        })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
        ga('create', 'UA-75000235-4', 'auto');
        ga('send', 'pageview');
    </script>


    <script src="./assets/erc20/js/sidecar.v1.js" async defer></script>

    <script>
        ((window.gitter = {}).chat = {}).options = {
            room: 'etherdelta/etherdelta.github.io',
            activationElement: false
        };

        $(document).ready(function(){
            $('#volume_btn').bind('click', function(){
                ( $('#volume').css('display') == 'none' ) ? $('#volume').fadeIn('slow') : $('#volume').fadeOut('slow');
            })
        });
    </script>
@endsection
