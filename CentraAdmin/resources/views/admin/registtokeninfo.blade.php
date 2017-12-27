@extends('layouts.centra')

@section('content')
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
<div class="container centra-container">
    <form class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label">Token Name</label>
            <div class="col-sm-4">
                <input class="form-control" id="name" type="text" value="">
            </div>
            <label class="col-sm-2 control-label">Decimal</label>
            <div class="col-sm-4">
                <input class="form-control" id="decimal" type="number" value="" required>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Token Address</label>
            <div class="col-sm-10">
                <input class="form-control" id="addr" type="text" value="">
            </div>
        </div>
        <div class="form-group">
            <button type="button" id="btn_save" class="col-md-offset-8 col-md-4 btn btn-success right" >Save</button>
        </div>
    </form>
</div>
    <script src="{{ asset('./js/admin/erc20tokenreg.js') }}" language="JavaScript" />
@endsection