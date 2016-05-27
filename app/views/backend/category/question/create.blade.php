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
					<span>Question</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Create</span>
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
			<i class="fa fa-pencil-square-o"></i> Create Question
		</div>
	</div>
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('backend.category.question.store') }}">
            <div class="form-group">
                <label class="col-sm-2 control-label">Category Name</label>
                <div class="col-sm-4">
                    <p class="form-control-static">{{ $subCategory->category->name }}</p>
                </div>            
                <label class="col-sm-2 control-label">Sub Category Name</label>
                <div class="col-sm-4">
                    <p class="form-control-static">{{ $subCategory->name }}</p>
                </div>
            </div>        
            
            <input type="hidden" name="sub_category_id" value="{{ $subCategory->id }}"/>
            <div class="form-group">
                <label class="col-sm-2 control-label">Name</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="name">
                </div>
                <label class="col-sm-2 control-label">Name 2</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="name2">
                </div>                
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Order</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="order" value="0">
                </div>                
            </div>            
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Selectable</label>
                <div class="col-sm-4">
                    <select class="form-control" name="is_selectable">
                        <option value=1>Yes</option>
                        <option value=0>No</option>
                    </select>
                </div>
                <label class="col-sm-2 control-label">Multiple</label>
                <div class="col-sm-4">
                    <select class="form-control" name="is_multiple">
                        <option value=1>Yes</option>
                        <option value=0 selected>No</option>
                    </select>                
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Notable</label>
                <div class="col-sm-4">
                    <select class="form-control" name="is_notable">
                        <option value=1>Yes</option>
                        <option value=0 selected>No</option>
                    </select>                
                </div>
                <label class="col-sm-2 control-label">Optional</label>
                <div class="col-sm-4">
                    <select class="form-control" name="is_optional" readonly>
                        <option value=1>Yes</option>
                        <option value=0 selected>No</option>
                    </select>                
                </div>
            </div>
                      
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('backend.category.sub.edit', $subCategory->id) }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@stop

@section('custom-scripts')
    @include('js.backend.category.question.create')
@stop


@stop
