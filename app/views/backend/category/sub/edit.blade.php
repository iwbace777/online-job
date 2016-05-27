@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Category Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Category</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Sub Category</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Edit</span>
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
			<i class="fa fa-pencil-square-o"></i> Edit Sub Category
		</div>
	</div>
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('backend.category.sub.store') }}">
            <input type="hidden" name="sub_category_id" value="{{ $subCategory->id }}">
            <input type="hidden" name="category_id" value="{{ $subCategory->category->id }}"/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Category Name</label>
                <div class="col-sm-10">
                    <p class="form-control-static">{{ $subCategory->category->name }}</p>
                </div>
            </div>        

            <div class="form-group">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name" value="{{ $subCategory->name }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Name 2</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="name2" value="{{ $subCategory->name2 }}">
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Order</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" name="order" value="{{ $subCategory->order }}">
                </div>
            </div>
          
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>                
                    <a href="{{ URL::route('backend.category.edit', $subCategory->category_id) }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
          </form>
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Question List
		</div>
		<div class="actions">
		    <a href="{{ URL::route('backend.category.question.create', $subCategory->id) }}" class="btn btn-default btn-sm">
		        <span class="glyphicon glyphicon-plus"></span>&nbsp;Create
		    </a>
	    </div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Name 2</th>
                    <th>Questions</th>
                    <th class="th-action">Edit</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($subCategory->questions as $key => $value)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $value->name }}</td>
                        <td>{{ $value->name2 }}</td>
                        <td>{{ count($value->answers) }}</td>
                        <td>
                            <a href="{{ URL::route('backend.category.question.edit', $value->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Edit
                            </a>
                        </td>
                        
                        <td>
                            <a href="{{ URL::route('backend.category.question.delete', $value->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop

@stop
