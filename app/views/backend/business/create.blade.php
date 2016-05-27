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
			<i class="fa fa-pencil-square-o"></i> Create Business
		</div>
	</div>
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('backend.business.store') }}">
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
                            <option class="option-city" value="{{ $city->id }}">{{ $city->name }}</option>
                                @foreach ($city->districts as $district)
                                <option class="option-district" value="{{ $city->id.'-'.$district->id }}">&nbsp;-&nbsp;{{ $district->name }}</option>
                                @endforeach
                            @endforeach
                        </select>
                    @elseif ($key === 'description')
                        {{ Form::textarea($key, null, ['class' => 'form-control']) }}                                                                
                    @else
                        {{ Form::text($key, null, ['class' => 'form-control']) }}
                    @endif
                </div>
            </div>
            @endforeach
            
            <div class="form-group" id="js-div-sub-category">
                <label class="col-sm-2 control-label">Category</label>
                <div class="col-sm-10">
			        @foreach ($categories as $category)
			            <div class="col-md-4">
			                <p><b>{{ $category->name }}</b></p>
			                @foreach ($category->subCategories as $subCategory)
			                <p>
			                    <input type="checkbox" class="form-control" id="js-checkbox-sub-category" value="{{ $subCategory->id }}" >&nbsp;{{ $subCategory->name }}
		                    </p>
			                @endforeach
			            </div>
			        @endforeach
                </div>
            </div>               
          
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success" onclick="return validate();">
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
