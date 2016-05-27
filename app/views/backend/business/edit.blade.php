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

<div class="row">
    <div class="col-sm-4 col-sm-offset-1">
        <div class="dashboard-stat blue">
			<div class="visual">
				<i class="icon-diamond"></i>
			</div>
			<div class="details">
				<div class="number">
					 {{ $count_email_sent }}
				</div>
				<div class="desc">
					 Count of Newsletter Sent
				</div>
			</div>
		</div>
    </div>
    
    <div class="col-sm-4 col-sm-offset-2">
        <div class="dashboard-stat blue">
			<div class="visual">
				<i class="fa fa-globe"></i>
			</div>
			<div class="details">
				<div class="number">
					 {{ $count_email_read }}
				</div>
				<div class="desc">
					 Count of Newsletter Read
				</div>
			</div>
		</div>
    </div>    
</div>

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Edit Business
		</div>
	</div>
	
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('backend.business.store') }}">
            <input type="hidden" name="business_id" value="{{ $business->id }}">
            @foreach ([
                'vat_id'  => 'VAT ID',
                'name'   => 'Name',
                'city_id' => 'City',
                'email' => 'Email',
                'email2' => 'Email 2',
                'email3' => 'Email 3',
                'email4' => 'Email 4',
                'email5' => 'Email 5',
                'phone'  => 'Phone',
                'contact'  => 'Contact',
                'zip_code'  => 'Zip Code',                
                'address' => 'Address',
                'description'  => 'Description',
            ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-10">
                    @if ($key === 'city_id')
                        <select name="city_id" class="form-control">
                            <option class="option-city" value="">{{ trans('user.select_city') }}</option>
                            @foreach ($cities as $city)
                            <option class="option-city" {{ (!$business->district_id && $business->city_id == $city->id) ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                                @foreach ($city->districts as $district)
                                <option class="option-district" {{ ($business->district_id && $business->city_id == $city->id && $business->district_id == $district->id) ? 'selected' : '' }} value="{{ $city->id.'-'.$district->id }}">&nbsp;-&nbsp;{{ $district->name }}</option>
                                @endforeach
                            @endforeach
                        </select> 
                    @elseif ($key === 'description')
                        {{ Form::textarea($key, $business->{$key}, ['class' => 'form-control']) }}                                                                
                    @else
                        {{ Form::text($key, $business->{$key}, ['class' => 'form-control']) }}
                    @endif
                </div>
            </div>
            @endforeach
            
            <div class="form-group" id="js-div-sub-category">
                <label class="col-sm-2 control-label">Category</label>
                <div class="col-sm-10">
			        <?php
			        $subCategories = [];
			        foreach ($business->subCategories as $item) {
                        $subCategories[] = $item->sub_category_id;
                    } 
			        ?>
			        
			        @foreach ($categories as $category)
			            <div class="col-md-4">
			                <p><b>{{ $category->name }}</b></p>
			                @foreach ($category->subCategories as $subCategory)
			                <p>
			                    <input type="checkbox" class="form-control" id="js-checkbox-sub-category" value="{{ $subCategory->id }}" 
			                        {{ in_array($subCategory->id, $subCategories) ? 'checked' : '' }}>&nbsp;{{ $subCategory->name }}
		                    </p>
			                @endforeach
			            </div>
			        @endforeach
                </div>
            </div>               
          
            <div class="form-group">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-success" onclick="return validate();">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('backend.business.delete', $business->id) }}" class="btn btn-danger">
                        <i class="fa fa-trash-o"></i> Delete
                    </a>                    
                    <a href="{{ URL::route('backend.business') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
          </form>
    </div>
</div>
@stop

@section('custom-scripts')
<script>
function validate() {
    var objList = $("input#js-checkbox-sub-category:checked");
    for (var i = 0; i < objList.length; i++) {
        $("div#js-div-sub-category").append($("<input type='hidden' name='sub_category[]' value=" + objList.eq(i).val() + ">"));
    }
    return true;
}
</script>
@stop

@stop
