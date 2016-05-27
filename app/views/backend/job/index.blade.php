@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Job Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Job</span>
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
			<i class="fa fa-navicon"></i> Job List
		</div>
	</div>
    <div class="portlet-body ">
        <div class="row margin-bottom-xs">
            <form method="get" action="{{ URL::route('backend.job') }}">
                <div class="col-sm-3 col-sm-offset-8"><input type="text" name="q" class="form-control input-sm" placeholder="Search By Name..." value="{{ $q }}"></div>
                <div class="col-sm-1"><button class="btn btn-sm blue"><i class="fa fa-search"></i></button></div>
            </form>
        </div>   
            
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Bids</th>
                    <th>Category</th>
                    <th>Avg Bid</th>
                    <th>Created At</th>
                    <th class="th-action">Detail</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jobs as $key => $job)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $job->name }}</td>
                        <td>{{ $job->user->name }}</td>
                        <td>{{ $job->status }}</td>
                        <td>{{ $job->count_view }}</td>
                        <td>{{ count($job->bids) }}</td>
                        <td>{{ $job->category->name }}</td>
                        <td>{{ round($job->bids()->avg('price')) }}</td>
                        <td>{{ $job->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('backend.job.detail', $job->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Detail
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('backend.job.delete', $job->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $jobs->appends(Request::input())->links() }}</div>
        <div class="clearfix"></div>
    </div>
</div>    
@stop

@stop
