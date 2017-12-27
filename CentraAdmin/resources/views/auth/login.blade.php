@extends('layouts.centra')

@section('content')
    <div class="row login-div-rect">

        <div class="row">
            <div class="col-md-12 center login-header">
                <img src="{{ asset('./images/CTR.png') }}" width="84px">
                <div class="form-title">Welcome to Centra</div>
                <h4 style="margin-top: 5px;color: white;">CREATING A WORLD CONNECTED TO CRYPTOCURRENCIES</h4>
            </div>
            <!--/span-->
        </div><!--/row-->

        <div class="row">
            <div class="well col-md-5 center centra-well login-box">
                <form class="form-horizontal" action="{{ route('login') }}" method="post">
                    {{ csrf_field() }}
                    <fieldset>
                        <div class="input-group input-group-lg">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-user red"></i></span>
                            {{--<input type="text" class="form-control" placeholder="Useremail" name="email" value="{{ old('email') }}">--}}
                            <input id="email" type="email" class="form-control"  placeholder="useremail" name="email" value="{{ old('email') }}" required autofocus>
                        </div>
                        <div class="clearfix"></div><br>

                        <div class="input-group input-group-lg">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-lock red"></i></span>
                            {{--<input type="password" class="form-control" placeholder="Password" name="passwd">--}}
                            <input id="password" type="password" class="form-control" name="password" placeholder="password" required>
                        </div>
                        <div class="clearfix"></div>

                        <div class="input-prepend">
                            <label class="remember" for="remember"><input type="checkbox" id="remember"> Remember me</label>
                        </div>
                        <div class="clearfix"></div>

                        <p class="center col-md-5">
                            <button type="submit" class="btn btn-primary">Login</button>
                        </p>
                        <div class="center col-md-12">
                            <h4 style="display: inline-block;color: white;">Need an Account?</h4>
                            <a href="{{ route('register') }}" class="centra-a-link" style="font-size: 19px;font-weight: bold;display: inline-block;" >Sign Up</a>
                        </div>
                    </fieldset>
                </form>
            </div>
            <!--/span-->
        </div><!--/row-->
    </div><!--/fluid-row-->
@endsection