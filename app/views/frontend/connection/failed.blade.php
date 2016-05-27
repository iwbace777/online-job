@extends('frontend.layout')

@section('custom-styles')
    <style>
    .header {
        box-shadow: 0 1px 3px #ddd;
        margin-bottom: 23px;
    }
    </style>
@stop

@section('main')
    <div class="container">
        <div class="row margin-top-sm">
            <div class="col-sm-3">
                <h2>&nbsp;</h2>
                @include("frontend.leftmenu")
            </div>
            <div class="col-sm-9">
                <div class="text-center">
                    <h2 class="color-blue"><i class="fa fa-cube" style="font-size: 30px;"></i>&nbsp;&nbsp;{{ trans('connection.purchase_bids') }}</h2>
                </div>
                
                <div class="alert alert-danger margin-top-lg">
				    <strong>{{ trans('connection.failed_purchase_bids') }}</strong>
				</div>
            </div>
        </div>
    </div>
@stop

@stop
