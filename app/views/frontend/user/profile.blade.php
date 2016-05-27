@extends('frontend.layout')
@section('custom-styles')
{{ HTML::style('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}
<style>
.header {
    box-shadow: 0 1px 3px #ddd;
    margin-bottom: 23px;
}
</style>
@stop
@section('main')
    @if(Session::get('locale') == 'sk')
    <?php $name = 'name2'; ?>
    @else
    <?php $name = 'name'; ?>
    @endif
    <div class="container">
        <div class="row margin-top-sm margin-bottom-normal">
            <div class="col-sm-3">
                <h2>&nbsp;</h2>
                @include("frontend.leftmenu")
            </div>
            <div class="col-sm-9">
                <div class="text-center margin-bottom-lg">
                    <h2 class="color-blue"><i class="fa fa-user" style="font-size: 30px;"></i>&nbsp;&nbsp;{{ trans('page.my_profile') }}</h2>
                </div>
                <form class="form-horizontal" role="form" method="post" action="{{ URL::route('user.updateProfile') }}" enctype="multipart/form-data">
                    @foreach ([
                        'email' => trans('user.email'),
                        'password' => trans('user.password'),
                        'name'   => trans('user.name'),
                        'email2' => trans('user.email').' 2',
                        'email3' => trans('user.email').' 3',
                        'email4' => trans('user.email').' 4',
                        'email5' => trans('user.email').' 5',
                        'vat_id' => trans('user.vat_id'),
                        'contact' => trans('user.contact'),                        
                        'zip_code' => trans('user.zip_code'),
                        'phone'  => trans('user.phone'),
                        'address' => trans('user.address'),
                        'hourly_rate' => trans('user.hourly_rate'),                        
                        'city_id' => trans('common.city'),
                        'photo'  => trans('common.photo'),
                        'count_connection'  => trans('common.bids'),
                        'description'  => trans('common.description'),
                    ] as $key => $value)
                    <div class="form-group">
                        <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                        <div class="col-sm-10">
                            @if ($key === 'city_id')
                                <select name="city_id" class="form-control">
                                    <option class="option-city" value="">{{ trans('user.select_city') }}</option>
                                    @foreach ($cities as $city)
                                    <option class="option-city" {{ (!$user->district_id && $user->city_id == $city->id) ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                                        @foreach ($city->districts as $district)
                                        <option class="option-district" {{ ($user->district_id && $user->city_id == $city->id && $user->district_id == $district->id) ? 'selected' : '' }} value="{{ $city->id.'-'.$district->id }}">&nbsp;-&nbsp;{{ $district->name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>                 
                            @elseif ($key === 'description')
                                {{ Form::textarea($key, $user->{$key}, ['class' => 'form-control']) }}
                            @elseif ($key === 'photo')
                                <div class="fileinput fileinput-new" data-provides="fileinput">
									<div class="fileinput-new thumbnail" style="width: 80px; height: 80px;">
										<img src="{{ HTTP_USER_PATH.$user->photo }} " alt=""/>
									</div>
									<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 80px; max-height: 80px;"></div>
									<div>
										<span class="btn default btn-file">
										    <span class="fileinput-new">{{ trans('user.select_image') }}</span>
										    <span class="fileinput-exists">{{ trans('user.change') }}</span>
										    <input type="file" name="{{ $key }}">
										</span>
										<a href="#" class="btn red fileinput-exists" data-dismiss="fileinput">{{ trans('user.remove') }}</a>
									</div>
								</div>
                            @elseif ($key === 'count_connection')
                                <p class="form-control-static"><b>{{ $user->{$key} }}</b></p>
                            @elseif ($key === 'email')
                                {{ Form::text($key, $user->{$key}, ['class' => 'form-control', 'readonly' => true]) }}                                                                
                            @else
                                {{ Form::text($key, $user->{$key}, ['class' => 'form-control']) }}
                            @endif
                        </div>
                    </div>
                    @endforeach
                    
                    <div class="form-group" id="js-div-sub-category">
                        <label class="col-sm-2 control-label">{{ trans('common.category') }}</label>
                        <div class="col-sm-10">                            
				            <?php
					        $subCategories = [];
					        foreach ($user->subCategories as $item) {
                                $subCategories[] = $item->sub_category_id;
                            } 
					        ?>
					        
					        @foreach ($categories as $category)
					            <div class="col-md-4">
					                <p><b>{{ $category->{$name} }}</b></p>
					                @foreach ($category->subCategories as $subCategory)
					                <p>
					                    <input type="checkbox" class="form-control" id="js-checkbox-sub-category" value="{{ $subCategory->id }}" 
					                        {{ in_array($subCategory->id, $subCategories) ? 'checked' : '' }}>&nbsp;{{ $subCategory->{$name} }}
				                    </p>
					                @endforeach
					            </div>
					        @endforeach                            
                            
                        </div>
                    </div>                    
                    
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10 text-center">
                            <button class="btn green btn-lg" onclick="return validate()">
                                <span class="glyphicon glyphicon-ok-circle"></span> {{ trans('common.save') }}
                            </button>
                        </div>
                    </div>                
                
                </form>
            </div>
        </div>
    </div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') }}
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
