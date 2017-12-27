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
        #panel_iframe {
            width:100%;
            border:none;
        }
    </style>

    <div class="row">
        <div class="col-md-12">
            <div class="ch-container centra-ch-container">
                <div class="row">

                    <!-- left menu starts -->
                    <div class="col-sm-4 col-lg-3 centra-asset-container">
                        <div class="sidebar-nav centra-scroll">
                            <div class="nav-canvas">
                                <div class="nav-sm nav nav-stacked">

                                </div>
                                <ul class="nav nav-pills nav-stacked main-menu">
                                    <li class="nav-header">Management</li>
                                    <li><a id="usr_mng" class="ajax-link centra-a-link" menu-prop="user-mng"><img src="{{ asset('./images/user-management.png') }}" class="centra-log-img" /> &nbsp;&nbsp;&nbsp;<span>User Management</span></a></li>
                                    <li><a id="pair_mng" chref="{{ route('pairmng') }}" class="ajax-link centra-a-link" menu-prop="exg-mng"><img src="{{ asset('./images/pair-management.png') }}" class="centra-log-img" /> &nbsp;&nbsp;&nbsp;<span>Exchange Pair Management</span></a></li>
                                    <li><a id="fee_mng" chref="{{ route('showfeemngform') }}" class="ajax-link centra-a-link" menu-prop="fee-mng"><img src="{{ asset('./images/fee-management.png') }}" class="centra-log-img" /> &nbsp;&nbsp;&nbsp;<span>Fee Management</span></a></li>
                                    <li><a id="erc20_mng" chref="{{ route('erc20tokenreg') }}" class="ajax-link centra-a-link" menu-prop="fee-mng"><img src="{{ asset('./images/ERC20_btn.png') }}" class="centra-log-img" /> &nbsp;&nbsp;&nbsp;<span>ERC20 Token Management</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-8 col-lg-9" style="padding: 0px 5px 0px 5px;">
                        <div class="panel centra-panel">
                            <div class="panel-heading centra-panel-heading">User Management</div>
                            <div class="panel-body" style="min-height:350px;padding:5px;">
                                <iframe src="" id="panel_iframe"></iframe>
                                {{--@yield('admin_content')--}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('./js/admin/admin.js') }}" language="JavaScript" />
    <script src="{{ asset('./theme/js/charisma.js') }}"></script>
@endsection