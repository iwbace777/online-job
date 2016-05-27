@extends('frontend.layout')

@section('custom-styles')
    {{ HTML::style('/assets/slider/css/layerslider.css') }}
    @if (Session::get('locale') == 'sk')
    <style>
    .status-item-desc {
        font-size: 11px;
    }
    ul.ul-project-category a {
        font-size: 12px;
    }    
    </style>
    @endif
@stop    

@section('main')
    @if(Session::get('locale') == 'sk')
    <?php $name = 'name2'; ?>
    @else
    <?php $name = 'name'; ?>
    @endif
    <div class="layer_slider">
        <div id="layerslider-container-fw" style="position:relative;">
            <div id="layerslider" style="width: 100%; height: 500px; margin: 0px auto;">
                <div class="ls-layer" style="slidedirection: right;">
                    <img src="/assets/img/slider00.jpg" class="ls-bg" alt="Slide background">
                </div>
                <div class="ls-layer" style="slidedirection: right;">
                    <img src="/assets/img/slider01.jpg" class="ls-bg" alt="Slide background">
                </div>                
                <div class="ls-layer" style="slidedirection: right;">
                    <img src="/assets/img/slider02.jpg" class="ls-bg" alt="Slide background">
                </div>
    
                <div class="ls-layer" style="slidedirection: right;">
                    <img src="/assets/img/slider03.jpg" class="ls-bg" alt="Slide background">                                
                </div>
            </div>
            <div style="position: absolute; top: 0px; width: 100%;">
                <div class="col-sm-10 col-sm-offset-1 home-searchbox">
                    <div class="row text-center margin-top-normal">
                        <h2 class="search-bar-title color-black"><b>{{ trans('job.post_first_project') }}</b></h2>
                    </div>
                    <div class="row search-bar">
                        @foreach ($categories as $cat)
                            <div class="col-sm-2 text-center">
                                <a href="{{ URL::route('job.post', 'category='.$cat->id) }}" class="color-black text-center">
                                    <img src="/assets/img/category_{{ $cat->id }}.png" style="width: 50%; height: 50%; max-width: 99px; max-height: 99px;" />
                                    <p style="font-size: 11px;"><b>
                                    {{ strlen($cat->{$name}) > 14 ? substr($cat->{$name}, 0, 14)."..." : $cat->{$name} }}
                                    </b></p>
                                </a>
                            </div>
                        @endforeach                                               
                    </div>
                </div>
            </div> 
        </div>
    </div>
    
    <!-- START : How It Works? -->
    <div class="container">
        <div class="row margin-top-lg text-center">
            <h1 class="color-blue"><b>{{ trans('job.how_it_works') }}?</b></h1>
        </div>
        <div class="row">
            <div class="col-sm-2 col-sm-offset-3 color-gray-dark">
                <div class="step-item">
                    <h3 class="color-gray-dark"><b>{{ trans('common.step') }} 1</b></h3>
                    <p>{{ trans('job.post_project') }}</p>
                </div>
            </div>
            <div class="col-sm-2 color-gray-dark">
                <div class="step-item">
                    <h3 class="color-gray-dark"><b>{{ trans('common.step') }} 2</b></h3>
                    <p>{{ trans('job.compare_proposals') }}</p>
                </div>
            </div>
            <div class="col-sm-2 color-gray-dark">
                <div class="step-item">
                    <h3 class="color-gray-dark"><b>{{ trans('common.step') }} 3</b></h3>
                    <p>{{ trans('job.choose_provider') }}</p>
                </div>
            </div>
        </div>
    </div>    
    <!-- END : How It Works? -->
    
    <!-- START : Projects for you -->
    <div class="container" id="projects">
        <div class="row margin-top-xl text-center">
            <h1 class="color-black">{{ trans('job.jobs_for_you', array('count' => $count_job)) }}</h1>
        </div>
    </div>
    <!-- END : Projects for you -->
    
    <?php if (!isset($category)) { $category = 0; } ?>
    <div class="container">
        <div class="row margin-top-lg">
            <ul class="nav nav-tabs nav-justified ul-project-category" role="tablist">
                <li class="{{ ($category == '') ? 'active' : '' }}">
                    <a href="{{ URL::route('home.index') }}">{{ trans('job.all') }}</a>
                </li>
                @foreach ($categories as $key => $item)
                @if ($key < 6 )
                <li class="{{ ($category == $item->slug) ? 'active' : '' }}">
                    <a href="{{ URL::route('home.index', $item->slug) }}">{{ (strlen($item->{$name}) > 14 ? substr($item->{$name}, 0, 14)."..." : $item->{$name})." (".$count_categories[$key].")" }}</a>
                </li>
                @endif
                @endforeach
                <li class="dropdown dropup">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        {{ trans('common.other') }} <i class="fa fa-angle-up"></i>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        @foreach ($categories as $key => $item)
                        @if ($key >= 6 )                        
                        <li>
                            <a href="{{ URL::route('home.index', $item->slug) }}" tabindex="-1">
                                {{ (strlen($item->{$name}) > 14 ? substr($item->{$name}, 0, 14)."..." : $item->{$name})." (".$count_categories[$key].")" }}
                            </a>
                        </li>
                        @endif
                        @endforeach
                    </ul>
				</li>
            </ul>
        </div>
    </div>
    
    <div style="background:#F2F5F7; padding-bottom: 15px;">
        <div class="container">
            <div class="row">
                <table class="table table-job-list margin-bottom-lg" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>{{ trans('job.project_title') }}</th>
                            <th>{{ trans('common.where') }}</th>
                            <th>{{ trans('common.bids2') }}</th>
                            <th>{{ trans('job.posted_date') }}</th>
                            <th class="text-center">{{ trans('common.price') }}</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jobs as $job)
                        <tr>
                            <td>
                                <a href="{{ URL::route('job.detail', $job->slug) }}">{{ $job->name }}</a>
                                <button class="btn btn-default btn-xs pull-right" id="js-btn-overview">{{ trans('common.overview') }}</button>
                            </td>
                            <td>{{ $job->city_id ? ($job->district_id ? $job->city->name."-".$job->district->name : $job->city->name) :"---" }}</td>
                            <td>{{ count($job->bids) }}
                            <td>{{ date(DATE_FORMAT, strtotime($job->created_at)) }}</td>
                            <td class="text-center">{{ (round($job->bids()->avg('price'), 1) == 0) ? '---' : round($job->bids()->avg('price'), 1) }}</td>
                            <td class="text-center">
                                <?php $is_applied = FALSE; ?>
                                @if (Session::has('user_id'))
                                    @foreach ($job->bids as $bid)
                                        @if ($bid->user_id == Session::get('user_id'))
                                            <?php 
                                                $is_applied = TRUE;
                                                continue; 
                                            ?>
                                        @endif
                                    @endforeach
                                @endif

                                <button class="btn {{ $is_applied ? 'red' : 'blue' }}" id="js-btn-bid">
                                    {{ $is_applied ? trans('common.bidded') : '&nbsp;'.trans('common.bid').'&nbsp;' }}
                                </button>
                            </td>
                        </tr>
                        <tr style="display: none;">
                            <td colspan="6">
                                <div class="row">
                                    <div class="col-sm-10 col-sm-offset-1">
                                        <div class="row padding-top-sm">
                                            <div class="col-sm-4">
                                                <div class="row row-no-margin">                                           
                                                    <div class="col-sm-6 text-right">
                                                        <p class="font-weight-bold color-blue">
                                                            {{ trans('common.category') }} : 
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p>
                                                            {{ $job->category->{$name} }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <?php 
                                                    $last1 = floor(count($job->details) / 3) - 1;
                                                    if ($last1 + 1 >= count($job->details)) {
                                                        $last2 = $last1;
                                                    } else {
                                                        while ($job->details[$last1]->question->name == $job->details[$last1+1]->question->name) {
                                                            $last1 = $last1 + 1;
                                                        }
                                                        
                                                        $last2 = floor(count($job->details) / 3 * 2) - 1;
                                                        if ($last2 + 1 >= count($job->details)) {
                                                        
                                                        } else {
                                                            while ($job->details[$last2]->question->name == $job->details[$last2+1]->question->name) {
                                                                $last2 = $last2 + 1;
                                                            }
                                                        }                                                   
                                                    }
                                                ?>
                                                @for ($i = 0; $i <= $last1; $i++)
                                                <div class="row row-no-margin">
                                                    <div class="col-sm-6 text-right">
                                                        <p class="font-weight-bold color-blue">
                                                            @if ($i == 0)
                                                                {{ $job->details[$i]->question->{$name} }} :
                                                            @elseif ($job->details[$i]->question->name != $job->details[$i-1]->question->name) 
                                                                {{ $job->details[$i]->question->{$name} }} :
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p>
                                                            @if (isset($job->details[$i]->answer_id))
                                                                {{ $job->details[$i]->answer->{$name} }}
                                                                @if ($job->details[$i]->value != '')
                                                                    <span class="color-gray-dark font-size-normal">(<i>{{ $job->details[$i]->value }}</i>)</span>
                                                                @endif
                                                            @else
                                                                {{ $job->details[$i]->value }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                @endfor
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <div class="row row-no-margin">                                           
                                                    <div class="col-sm-6 text-right">
                                                        <p class="font-weight-bold color-blue">
                                                            {{ trans('common.sub_category') }} : 
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p>
                                                            {{ isset($job->sub_category_id) ? $job->subCategory->{$name} : '---'}}
                                                        </p>
                                                    </div>
                                                </div>                                            
                                                @for ($i = $last1 + 1; $i <= $last2; $i++)
                                                <div class="row row-no-margin">
                                                    <div class="col-sm-6 text-right">
                                                        <p class="font-weight-bold color-blue">
                                                            @if ($i == 0)
                                                                {{ $job->details[$i]->question->{$name} }} :
                                                            @elseif ($job->details[$i]->question->name != $job->details[$i-1]->question->name) 
                                                                {{ $job->details[$i]->question->{$name} }} :
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p>
                                                            @if (isset($job->details[$i]->answer_id))
                                                                {{ $job->details[$i]->answer->{$name} }}
                                                                @if ($job->details[$i]->value != '')
                                                                    <span class="color-gray-dark font-size-normal">(<i>{{ $job->details[$i]->value }}</i>)</span>
                                                                @endif
                                                            @else
                                                                {{ $job->details[$i]->value }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                @endfor                        
                                            </div>
                                            
                                            <div class="col-sm-4">
                                                <div class="row row-no-margin">                                           
                                                    <div class="col-sm-6 text-right">
                                                        <p class="font-weight-bold color-blue">
                                                            {{ trans('common.city') }} : 
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p>
                                                            {{ isset($job->city_id) ? $job->city->name : '---' }}
                                                        </p>
                                                    </div>
                                                </div>                                             
                                                @for ($i = $last2 + 1; $i < count($job->details); $i++)
                                                <div class="row row-no-margin">
                                                    <div class="col-sm-6 text-right">
                                                        <p class="font-weight-bold color-blue">
                                                            @if ($i == 0)
                                                                {{ $job->details[$i]->question->{$name} }} :
                                                            @elseif ($job->details[$i]->question->name != $job->details[$i-1]->question->name) 
                                                                {{ $job->details[$i]->question->{$name} }} :
                                                            @endif
                                                        </p>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <p>
                                                            @if (isset($job->details[$i]->answer_id))
                                                                {{ $job->details[$i]->answer->{$name} }}
                                                                @if ($job->details[$i]->value != '')
                                                                    <span class="color-gray-dark font-size-normal">(<i>{{ $job->details[$i]->value }}</i>)</span>
                                                                @endif
                                                            @else
                                                                {{ $job->details[$i]->value }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </div>
                                                @endfor                        
                                            </div>                                            
                                        </div>
                                        
                                        @if (count($job->attachments) > 0)
                                            <div class="row row-no-margin">
                                                <div class="col-sm-2 text-right">
                                                    <h5 class="font-weight-bold color-blue">
                                                        {{ trans('common.attachment') }} : 
                                                    </h5>
                                                </div>
                                                <div class="col-sm-8">
                                                    @foreach ($job->attachments as $attachment)
                                                        <a href="{{ HTTP_ATTACHMENT_PATH.$attachment->sys_name }}" class="font-size-lg" target="_blank">
                                                            <i class="icon-paper-clip"></i>
                                                            {{ $attachment->org_name }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>                                
                            </td>
                        </tr>
                        
                        <tr data-id="{{ $job->id }}" style="display: none;">
                            <td colspan="6">
                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-1">
                                        <div class="form-group">
                                            <label>{{ trans('common.description') }}</label>
                                            <textarea class="form-control" rows="5" placeholder="{{ trans('common.description') }}" id="js-txt-description"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label>{{ trans('common.price') }}</label>
                                            <input type="text" class="form-control" style="margin-bottom: 5px;" id="js-txt-price" placeholder="{{ trans('common.price') }}">
                                            <button class="btn btn-info btn-sm btn-block" id="js-btn-submit">{{ trans('common.submit') }}</button>
                                        </div>                                        
                                    </div>
                                </div>                        
                            </td>
                        </tr>
                        @endforeach
                        
                        @if (count($jobs) == 0)
                        <tr>
                            <td class="text-center" colspan="6">{{ trans('job.no_jobs') }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <div class="pull-right">{{ $jobs->links() }}</div>
            </div>        
            <div class="row padding-top-normal padding-bottom-lg">
                <div class="col-sm-6" style="background-image: url('/assets/img/home01.jpg'); background-size: cover; padding: 0px;">
                    <div style="background-color: rgba(255, 222, 0, 0.9); width: 100%; height: 100%; display: inline-block;">
                        <h1 class="color-black text-center" style="margin-top: 0px; padding-top: 30px;">{{ trans('job.for_consumers') }}</h1>
                        <div class="home-desc-step">
                            <div class="pull-left home-desc-step-icon">
                                <span class="glyphicon glyphicon-chevron-right color-white"></span>
                            </div>
                            <div class="pull-left home-desc-step-text">
                                <span class="color-gray-dark"><b>{{ trans('job.msg01') }}</b></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="home-desc-step">
                            <div class="pull-left home-desc-step-icon">
                                <span class="glyphicon glyphicon-chevron-right color-white"></span>
                            </div>
                            <div class="pull-left home-desc-step-text">
                                <span class="color-gray-dark"><b>{{ trans('job.msg02') }}</b></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="home-desc-step">
                            <div class="pull-left home-desc-step-icon">
                                <span class="glyphicon glyphicon-chevron-right color-white"></span>
                            </div>
                            <div class="pull-left home-desc-step-text">
                                <span class="color-gray-dark"><b>{{ trans('job.msg03') }}</b></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>                                        
                    </div>
                </div>
                <div class="col-sm-6" style="background-image: url('/assets/img/home02.jpg'); background-size: cover; padding: 0px;">
                    <div style="background-color: rgba(255, 255, 255, 0.9); width: 100%; height: 100%; display: inline-block;">
                        <h1 class="color-blue text-center" style="margin-top: 0px; padding-top: 30px;">{{ trans('job.for_businesses') }}</h1>
                        <div class="home-desc-step">
                            <div class="pull-left home-desc-step-icon">
                                <span class="glyphicon glyphicon-chevron-right color-white"></span>
                            </div>
                            <div class="pull-left home-desc-step-text">
                                <span class="color-gray-dark"><b>{{ trans('job.msg04') }}</b></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="home-desc-step">
                            <div class="pull-left home-desc-step-icon">
                                <span class="glyphicon glyphicon-chevron-right color-white"></span>
                            </div>
                            <div class="pull-left home-desc-step-text">
                                <span class="color-gray-dark"><b>{{ trans('job.msg05') }}</b></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                        
                        <div class="home-desc-step">
                            <div class="pull-left home-desc-step-icon">
                                <span class="glyphicon glyphicon-chevron-right color-white"></span>
                            </div>
                            <div class="pull-left home-desc-step-text">
                                <span class="color-gray-dark"><b>{{ trans('job.msg06') }}</b></span>
                            </div>
                            <div class="clearfix"></div>
                        </div>                                        
                    </div>
                </div>  
            </div>
        </div>
    </div>
    
    <!-- START : Status -->
    <div class="container">
        <div class="row margin-top-lg">
            <div class="col-sm-3">
                <div class="status-item">
                    <div class="pull-left status-item-border-right">
                        <span class="glyphicon glyphicon-time color-gray-normal status-item-icon padding-top-xs"></span>
                    </div>
                    <div class="pull-left">
                        <div class="color-blue status-item-count"><b>3,500+</b></div>
                        <div class="status-item-desc">{{ trans('job.hours_spent') }}</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="status-item">
                    <div class="pull-left status-item-border-right">
                        <span class="glyphicon glyphicon-star color-gray-normal status-item-icon padding-top-xs"></span>
                    </div>
                    <div class="pull-left">
                        <div class="color-blue status-item-count"><b>{{ number_format($count_feedback) }}+</b></div>
                        <div class="status-item-desc">{{ trans('job.positive_reviews') }}</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="status-item">
                    <div class="pull-left status-item-border-right">
                        <span class="glyphicon glyphicon-save color-gray-normal status-item-icon padding-top-xs"></span>
                    </div>
                    <div class="pull-left">
                        <div class="color-blue status-item-count"><b>{{ number_format($count_posted) }}+</b></div>
                        <div class="status-item-desc">{{ trans('job.project_posted') }}</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
            
            <div class="col-sm-3">
                <div class="status-item">
                    <div class="pull-left status-item-border-right">
                        <span class="glyphicon glyphicon-user color-gray-normal status-item-icon padding-top-xs"></span>
                    </div>
                    <div class="pull-left">
                        <div class="color-blue status-item-count"><b>{{ number_format($count_user) }}+</b></div>
                        <div class="status-item-desc">{{ trans('job.people_use_our_platform') }}</div>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>                 
        </div>
    </div>
    <!-- END : Status -->    
    
    <!-- BEGIN : Why Hestitate? -->
    <div class="container margin-top-lg margin-bottom-normal ">
        <div class="row text-center">
            <h1 class="color-blue h1-big">
                {{ trans('job.msg07') }}
            </h1>
        </div>
        <div class="row text-center">
            <div class="col-sm-4 col-sm-offset-4">
                <a class="btn yellow btn-lg margin-top-lg btn-block" href="<?php echo "job/post"?>">{{ trans('job.start_now') }}!</a>
            </div>
        </div>
    </div>
    <!-- END : Why Hestitate? -->
@stop

@section('custom-scripts')
    {{ HTML::script('/assets/slider/jQuery/jquery-easing-1.3.min.js') }}
    {{ HTML::script('/assets/slider/jQuery/jquery-transit-modified.min.js') }}
    {{ HTML::script('/assets/slider/js/layerslider.transitions.min.js') }}
    {{ HTML::script('/assets/slider/js/layerslider.kreaturamedia.jquery.min.js') }}
    {{ HTML::script('/assets/js/bootstrap-tooltip.js') }}    
    @include('js.frontend.job.home')   
@stop

@stop
