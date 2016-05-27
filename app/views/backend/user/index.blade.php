@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">User Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>User</span>
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
			<i class="fa fa-navicon"></i> User List
		</div>
		<div class="actions">
		    <a href="{{ URL::route('backend.user.create') }}" class="btn btn-default btn-sm">
		        <span class="glyphicon glyphicon-plus"></span>&nbsp;Create
		    </a>								    
	    </div>
	</div>
    <div class="portlet-body ">
        <div class="row margin-bottom-xs">
            <form method="get" action="{{ URL::route('backend.user') }}">
                <div class="col-sm-3 col-sm-offset-8"><input type="text" name="q" class="form-control input-sm" placeholder="Search By Name or Email" value="{{ $q }}"></div>
                <div class="col-sm-1"><button class="btn btn-sm blue"><i class="fa fa-search"></i></button></div>
            </form>
        </div>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Posted</th>
                    <th>Bids<br/>Left</th>
                    <th>Bids<br/>Done</th>
                    <th>Email<br/>Receive</th>
                    <th>Type</th>
                    <th>Created At</th>
                    <th class="th-action">Edit</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $key => $user)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->phone }}</td>
                        <td>{{ count($user->jobs) }}</td>
                        <td>{{ $user->count_connection }}</td>
                        <td>{{ count($user->bids) }}</td>
                        <td>{{ count($user->newsletters) }}</td>
                        <td>{{ $user->is_business ? 'Business' : 'Individual' }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('backend.user.edit', $user->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Edit
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('backend.user.delete', $user->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $users->appends(Request::input())->links() }}</div>
        <div class="clearfix"></div>
    </div>
</div>    
@stop

@stop
