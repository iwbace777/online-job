@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Business Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Business</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>List</span>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('content')
<?php if (isset($alert)) { ?>
<div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <p>
        <?php echo $alert['msg'];?>
    </p>
</div>
<?php } ?>
                    
<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Business List
		</div>
		<div class="actions">
		    <a href="{{ URL::route('backend.business.import') }}" class="btn btn-default btn-sm">
		        <i class="fa fa-file-excel-o"></i>&nbsp;Excel Import
		    </a>		
		    <a href="{{ URL::route('backend.business.create') }}" class="btn btn-default btn-sm">
		        <span class="glyphicon glyphicon-plus"></span>&nbsp;Create
		    </a>
	    </div>
	</div>
    <div class="portlet-body">
        <div class="row margin-bottom-xs">
            <form method="get" action="{{ URL::route('backend.business') }}">
                <div class="col-sm-3 col-sm-offset-5">
                    {{ Form::select('category_id'
                       , array('' => 'All Category') + $categories->lists('name', 'id')
                       , $categoryId
                       , array('class' => 'form-control input-sm')) }}
                </div>
                <div class="col-sm-3"><input type="text" name="q" class="form-control input-sm" placeholder="Search By Name or Email" value="{{ $q }}"></div>
                <div class="col-sm-1"><button class="btn btn-sm blue"><i class="fa fa-search"></i></button></div>
            </form>
        </div>    
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>VAT ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>City</th>
                    <th>Type</th>
                    <th class="th-action">Edit</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($businesses as $key => $business)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $business->vat_id }}</td>
                        <td>{{ $business->name }}</td>
                        <td>{{ $business->email }}</td>
                        <td>{{ $business->phone }}</td>
                        <td>{{ ($business->city_id) ? $business->city->name : '---' }}</td>
                        <td>{{ $business->is_subscriber ? 'Subscriber' : 'Registered' }}</td>
                        <td>
                            <a href="{{ URL::route('backend.business.edit', $business->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span>
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('backend.business.delete', $business->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $businesses->appends(Request::input())->links() }}</div>
        <div class="clearfix"></div>
    </div>
</div>    
@stop

@stop
