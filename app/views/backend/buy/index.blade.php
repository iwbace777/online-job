@extends('backend.layout')

@section('custom-styles')
{{ HTML::style('/assets/metronic/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}
@stop

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Bids Purchase</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Purchase History</span>
					<i class="fa fa-angle-right"></i>
				</li>
			</ul>
		</div>
	</div>    
@stop
                    
@section('content')                    
<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Bids Purchase Requests
		</div>
	</div>
    <div class="portlet-body ">
    
        <div class="row margin-bottom-xs">
            <form method="get" action="{{ URL::route('backend.buy.history') }}">
                <div class="col-sm-3 col-sm-offset-8"><input type="text" name="q" class="form-control input-sm" placeholder="Search By Invoice No" value="{{ $q }}"></div>
                <div class="col-sm-1"><button class="btn btn-sm blue"><i class="fa fa-search"></i></button></div>
            </form>
        </div>    
    
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Invoice No</th>
                    <th>Count</th>
                    <th style="width: 170px;">Due At</th>
                    <th style="width: 140px;">Add Bids</th>                    
                    <th class="th-action">Paid</th>
                    <th class="th-action">Invoice</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($buys as $key => $value)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td><a href="{{ URL::route('backend.user.edit', $value->user_id) }}">{{ $value->user->name }}</a></td>
                        <td>{{ $value->invoice_no }}</td>                        
                        <td>{{ $value->count }}</td>
                        <td>
                            <input type="text" class="form-control pull-left input-sm" style="width: 100px;" value="{{ $value->due_at }}" id="js-text-due-at">
                            <button class="btn btn-sm btn-success pull-left" style="margin-left: 5px;" id="js-btn-due-at" data-id="{{ $value->id }}">
                                <i class="fa fa-save"></i>
                            </button>                            
                        </td>
                        <td>
                            <form method="post" action="{{ URL::route('backend.buy.add') }}">
                                <input type="hidden" value="{{ $value->id }}" name="buy_id">
                                <input type="text" class="form-control pull-left input-sm" style="width: 75px;" name="count_connection">
                                <button class="btn btn-sm btn-info pull-left" style="margin-left: 5px;">
                                    <i class="fa fa-plus"></i>
                                </button>
                            </form>                                                                                    
                        </td>                        
                        <td>
                            <a href="{{ URL::route('backend.buy.paid', $value->id) }}" class="btn btn-sm {{ $value->is_paid ? 'btn-info' : 'btn-danger' }} ">
                                {{ $value->is_paid ? 'Paid' : 'Unpaid' }}
                            </a>
                        </td>
                        <td>
                            @if ($value->is_sent_invoice)
                                <button class="btn btn-info btn-sm">Invoice Sent</button>
                            @else
                            <a href="{{ URL::route('backend.buy.sent', $value->id) }}" class="btn btn-sm btn-success">
                                Send Now
                            </a>
                            @endif
                        </td>
                        <td>
                            <a href="{{ URL::route('backend.buy.delete', $value->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $buys->links() }}</div>
        <div class="clearfix"></div>
    </div>
</div>    
@stop

@section('custom-scripts')
{{ HTML::script('/assets/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
{{ HTML::script('/assets/metronic/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
<script>
$(document).ready(function() {
    $('input#js-text-due-at').datepicker({format: 'yyyy-mm-dd'});
    $("button#js-btn-due-at").click(function() {
        var buy_id = $(this).attr('data-id');
        var due_at = $(this).prev().val();
        $.ajax({
            url: "{{ URL::route('async.buy.update.due-at') }}",
            dataType : "json",
            type : "POST",
            data : { buy_id : buy_id, due_at : due_at },
            success : function(result){
                if (result.result == 'success') {
                    bootbox.alert(result.msg);
                    window.setTimeout(function(){
                        bootbox.hideAll();
                    }, 2000);                        
                }
            }
        });

    });
});

</script>
@stop

@stop
