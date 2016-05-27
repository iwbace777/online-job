@extends('frontend.layout')
@section('custom-styles')
    <style>
    .header {
        box-shadow: 0 1px 3px #ddd;
    }
    
    .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active{
    	color: inherit;
    	background-color: inherit;
    }    
    
    </style>
@stop
@section('main')
    @if(Session::get('locale') == 'sk')
    <?php $name = 'name2'; ?>
    @else
    <?php $name = 'name'; ?>
    @endif
    <div class="container" id="projects">
        <div class="row margin-top-lg">
            <div class="col-sm-12 text-center">
                <h2 class="color-blue">{{ trans('page.post_a_job') }}</h2>
                <div class="margin-top-lg">
                    <form method="post" action="{{ URL::route('job.doPost') }}" enctype='multipart/form-data'>                    
                        
                        <input type="hidden" name="category_id"/>
                        <input type="hidden" name="sub_category_id"/>
                        <div id="js-div-detail">
                        
                        </div>
                        
                        <div class="margin-top-sm padding-bottom-xs">
                            <h3 class="color-blue">{{ trans('common.category') }}</h3>
                            @foreach ($categories as $category)
                                <button type="button" class="btn btn-default btn-answer btn-choice btn-category" id="js-btn-category" data-id="{{ $category->id }}">{{ $category->{$name} }}</button>
                            @endforeach
                            <hr/>
                        </div>
                        
                        <div id="js-div-sub-category">
                        </div>
                        <div id="js-div-question-list" style="min-height: 300px;">
    
                        </div>
                    </form>
                </div>
                
            </div>
        </div>
    </div>
    
<div id="select-js-div-question-item" class="hide" data-selectable=1 data-multiple=1 data-notable=1 data-optional=0>
    <h3 class="color-blue" id="js-h3-question-name"></h3>
    <div class="margin-top-sm padding-bottom-xs" id="js-div-answer-list">
    
    </div>
    <div id="js-div-note-list">
    
    </div>
    <hr/>
</div>

    <div id="clone-js-div-answer-item" class='hide'>
        <button type="button" class="btn btn-default btn-answer btn-choice btn-category" id="js-btn-answer" onclick="onClickSubCategory(this)"></button>
    </div>
    <div id="clone-js-div-note-item" class='hide'>
        <div class="row margin-top-sm">
            <span class="col-sm-3 col-sm-offset-2 form-control-static text-right font-weight-bold" id="js-span-note-item"></span>
            <div class="col-sm-6">
                <textarea class="form-control" rows="3" id="js-textarea-note-item"></textarea>
            </div>
        </div>
    </div>

<div id="text-js-div-question-item" class="hide">
    <div class="col-sm-3 text-right">
        <h3 class="color-blue form-control-static" id="js-h3-question-name"></h3>
    </div>
    <div class="col-sm-6" id="js-div-question-area">
        <input type="text" class="form-control" id="js-text-answer" onfocus="autoScroll(this)"/>
    </div>
    <div class="clearfix"></div>
    <hr/>
</div>

<div id="clone-js-div-name" class="hide">
    <div class="col-sm-3 text-right">
        <h3 class="color-blue form-control-static" id="js-h3-question-name">{{ trans('job.job_name') }} *</h3>
    </div>
    <div class="col-sm-6">
        <input type="text" class="form-control" name="name" onfocus="autoScroll(this)"/>
    </div>
    <div class="clearfix"></div>
    <hr/>
</div>

<div id="clone-js-div-file" class="hide">
    <div class="col-sm-3 text-right">
        <h3 class="color-blue form-control-static">{{ trans('common.attachment') }}</h3>
    </div>
    <div class="col-sm-6">
        <input type="file" class="form-control" name="attachment"/>
    </div>
    <div class="clearfix"></div>
    <hr/>
</div>

<div id="clone-js-div-city" class="hide">
    <div class="col-sm-3 text-right">
        <h3 class="color-blue form-control-static">{{ trans('common.city') }}</h3>
    </div>
    <div class="col-sm-6">
        <select name="city_id" class="form-control">
            <option class="option-city" value="">{{ trans('user.select_city') }}</option>
            @foreach ($cities as $city)
            <option class="option-city" value="{{ $city->id }}">{{ $city->name }}</option>
                @foreach ($city->districts as $district)
                <option class="option-district" value="{{ $city->id.'-'.$district->id }}">&nbsp;-&nbsp;{{ $district->name }}</option>
                @endforeach
            @endforeach
        </select>  
    </div>
    <div class="clearfix"></div>
    <hr/>
</div>

<div id="clone-js-div-submit" class="hide padding-bottom-xl">
    <div class="col-sm-2 col-sm-offset-5" >
        <button class="btn btn-lg blue btn-block" onclick="return validate();" style="width: 180px;">{{ trans('common.submit') }}</button>
    </div>
</div>

@stop

@section('custom-scripts')
    @include('js.frontend.job.post')
@stop

@stop
