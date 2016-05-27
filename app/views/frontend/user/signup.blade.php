@extends('frontend.layout')

@section('main')
    @if(Session::get('locale') == 'sk')
    <?php $name = 'name2'; ?>
    @else
    <?php $name = 'name'; ?>
    @endif
<div class="main" style="background: url(/assets/img/background.jpg); background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 margin-top-normal margin-bottom-xl">
                <form role="form" method="post" action="{{ URL::route('user.doSignup') }}">
                    <div class="form-group">
                        <div class="row text-center">
                            <p class="form-control-static">
                                <h2 class="text-center text-uppercase">{{ trans('user.become_our_member') }}!</h2>
                            </p>
                        </div>
                    </div>
                    
                    @if ($errors->has())
                    <div class="alert alert-danger alert-dismissibl fade in">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">{{ trans('common.close') }}</span>
                        </button>
                        @foreach ($errors->all() as $error)
                    		<p>{{ $error }}</p>		
                    	@endforeach
                    </div>
                    @endif
                
                    @if (isset($alert))
                    <div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">{{ trans('common.close') }}</span>
                        </button>
                        <p>
                            <?php echo $alert['msg'];?>
                        </p>
                    </div>
                    @endif

                    <div class="margin-top-xs"></div>
                    <div class="form-group">
                        <label class="control-label">{{ trans('user.user_type') }} *</label>
                        <div>
                        <div class="btn-group">
							<button type="button" class="btn btn-default btn-lg" id="js-btn-type" data-type=1 style="width: 150px;">{{ trans('common.business') }}</button>
							<button type="button" class="btn btn-default btn-lg" id="js-btn-type" data-type=0 style="width: 150px;">{{ trans('common.individual') }}</button>
						</div>
						</div>
						<input type="hidden" name="is_business">
                    </div>
                    
                    <div id="js-div-business" style="display: none;">
                        <div class="margin-top-normal"></div>
                        <div class="form-group">
                            <label class="control-label">{{ trans('user.vat_id') }}</label>
                            <input type="text" class="form-control input-lg" placeholder="{{ trans('user.vat_id') }}" name="vat_id">
                        </div>
                    </div>

                    
                    <div class="margin-top-normal"></div>
                    <div class="form-group">
                        <label class="control-label">{{ trans('user.email') }} *</label>
                        <input type="text" class="form-control input-lg" placeholder="{{ trans('user.email') }}" name="email">
                    </div>
                    
                    <div class="margin-top-normal"></div>
                    <div class="form-group">
                        <label class="control-label">{{ trans('user.name') }} *</label>
                        <input type="text" class="form-control input-lg" placeholder="{{ trans('user.name') }}" name="name">
                    </div>                    
                    
                    <div class="margin-top-normal"></div>
                    <div class="form-group">
                        <label class="control-label">{{ trans('user.password') }} *</label>
                        <input type="password" class="form-control input-lg" placeholder="{{ trans('user.password') }}" name="password">
                    </div>
                    
                    <div class="margin-top-normal"></div>
                    <div class="form-group">
                        <label class="control-label">{{ trans('user.password_confirmation') }} *</label>
                        <input type="password" class="form-control input-lg" placeholder="{{ trans('user.password_confirmation') }}" name="password_confirmation">
                    </div>
                    
                    <div id="js-div-business" style="display: none;">
                        @foreach ([
                            'phone'  => trans('user.phone'),
                            'city_id' => trans('common.city'),
                        ] as $key => $value)
                            <div class="margin-top-normal"></div>
                            <div class="form-group">
                                <label class="control-label">{{ Form::label($key, $value) }}</label>
                                @if ($key === 'city_id')
                                    <select name="city_id" class="form-control input-lg">
                                        <option class="option-city" value="">{{ trans('user.select_city') }}</option>
                                        @foreach ($cities as $city)
                                        <option class="option-city" value="{{ $city->id }}">{{ $city->name }}</option>
                                            @foreach ($city->districts as $district)
                                            <option class="option-district" value="{{ $city->id.'-'.$district->id }}">&nbsp;-&nbsp;{{ $district->name }}</option>
                                            @endforeach
                                        @endforeach
                                    </select>                         
                                @else
                                    {{ Form::text($key, null, ['class' => 'form-control input-lg']) }}
                                @endif
                            </div>
                        @endforeach
                        <div class="margin-top-normal"></div>
                        <div class="form-group" id="js-div-sub-category">
                            <label class="control-label">{{ trans('common.category') }}</label>
                            <div class="margin-top-sm">                            
    					        @foreach ($categories as $category)
    					            <div class="col-md-4">
    					                <p><b>{{ $category->{$name} }}</b></p>
    					                @foreach ($category->subCategories as $subCategory)
    					                <p>
    					                    <input type="checkbox" class="form-control" id="js-checkbox-sub-category" value="{{ $subCategory->id }}">&nbsp;{{ $subCategory->{$name} }}
    				                    </p>
    					                @endforeach
    					            </div>
    					        @endforeach                            
                            </div>
                        </div>                                                   
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="text-right">
                                    <button type="submit" class="btn green btn-lg" onclick="return validate();">
                                        {{ trans('user.sign_up') }} <span class="glyphicon glyphicon-ok-circle"></span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>    
            </div>
        </div>
   </div>
</div>
@stop

@section('custom-scripts')
    @include('js.frontend.user.signup')    
@stop

@stop
