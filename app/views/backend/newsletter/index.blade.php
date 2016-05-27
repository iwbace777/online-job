@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Newsletter Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Newsletter Subscribers</span>
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

@if ($errors->has())
<div class="alert alert-danger alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    @foreach ($errors->all() as $error)
		{{ $error }}		
	@endforeach
</div>
@endif
                    
<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Newsletter Subscribers
		</div>
		<div class="actions">
		    <a href="{{ URL::route('backend.newsletter.send') }}" class="btn btn-default btn-sm">
		        <span class="fa fa-envolope"></span>&nbsp;Send Newsletter
		    </a>								    
	    </div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Email</th>
                    <th>Created At</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subscribers as $key => $value)
                    <tr>
                        <td>{{ ((Input::has('page') ? Input::get('page') : 1) - 1 ) * PAGINATION_SIZE + ($key + 1) }}</td>
                        <td>{{ $value->email }}</td>
                        <td>{{ $value->created_at }}</td>
                        <td>
                            <a href="{{ URL::route('backend.newsletter.delete', $value->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="pull-right">{{ $subscribers->links() }}</div>
        <div class="clearfix"></div>
    </div>
</div>    
@stop

@stop
