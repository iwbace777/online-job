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
					<span>Excel Import</span>
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
			<i class="fa fa-pencil-square-o"></i> Excel Import
		</div>
	</div>
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('backend.business.doImport') }}" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-sm-2 control-label">Category</label>
                <div class="col-sm-10">
                    <input type="text" name="category" class="form-control">
                </div>
            </div>
                    
            <div class="form-group">
                <label class="col-sm-2 control-label">Excel File</label>
                <div class="col-sm-10">
                    <input type="file" name="excel" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('backend.business') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
        </form>
        <hr/>
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('backend.business.doImport2') }}" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-sm-2 control-label">Sub Category</label>
                <div class="col-sm-10">
                    <input type="text" name="sub_category" class="form-control">
                </div>
            </div>
                    
            <div class="form-group">
                <label class="col-sm-2 control-label">Excel File</label>
                <div class="col-sm-10">
                    <input type="file" name="excel" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('backend.business') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
        </form>        
    </div>
</div>
@stop

@stop
