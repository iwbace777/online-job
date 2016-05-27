@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Transactions</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Subscribe History</span>
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
			<i class="fa fa-navicon"></i> Transaction List
		</div>
        <div class="actions">
		    <a href="{{ URL::route('backend.purchase.history') }}" class="btn btn-default btn-sm">Purchase</a>
		    <a href="{{ URL::route('backend.subscribe.history') }}" class="btn red btn-sm">Subscribe</a>								    
	    </div>		
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Username</th>
                    <th>Package name</th>
                    <th>Invoice No</th>
                    <th>Amount</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscribes as $key => $value)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $value->user->name }}</td>
                        <td>{{ $value->plan->name }}</td>
                        <td>{{ $value->invoice }}</td>
                        <td>{{ $value->amount }}</td>
                        <td>{{ $value->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $subscribes->links() }}</div>
        <div class="clearfix"></div>
    </div>
</div>    
@stop

@stop
