@extends('frontend.layout')

@section('meta-seo')
    <meta name="description" content="{{ $user->description }}">
    <meta name="keywords" content="Inquirymall, Service, Job Seeker, Job Provider, Customer, Business, Finland, Helsinki, Finternet">
    <meta name="author" content="Finternet-Group">
@stop

@section('title')
    {{ $user->name }} | {{ isset($user->city) ? $user->city : "" }}
     @foreach ($user->subCategories as $subCategory)
         {{ $subCategory->subCategory->name." | " }}
     @endforeach
     {{ SITE_NAME }}
@stop

@section('custom-styles')
    <style>
    .header {
        box-shadow: 0 1px 3px #ddd;
        margin-bottom: 23px;
    }
    </style>
    {{ HTML::style('/assets/css/star-rating.min.css') }}    
@stop

@section('main')
    @if(Session::get('locale') == 'sk')
    <?php $name = 'name2'; ?>
    @else
    <?php $name = 'name'; ?>
    @endif
    <div class="container">
        <div class="row margin-bottom-sm">
            <div class="col-sm-12 text-right">
                <a href="{{ URL::route('job.search') }}">{{ trans('common.back_to_search_page') }}</a>
            </div>
        </div>
    </div>
    
    <div style="background: #F2F5F7">
        <div class="container padding-top-sm padding-bottom-sm">
            <div class="row row-no-margin">
                <div class="text-uppercase col-sm-8 color-gray-light font-size-lg">{{ trans('user.about_user') }}</div>
                <div class="text-uppercase col-sm-4 color-gray-light font-size-lg">{{ trans('user.info') }}</div>
            </div>
        </div>
        <div class="container detail-inner job-detail border-bottom-gray padding-bottom-sm">
            <div class="row row-no-margin">
                <div class="col-sm-8 padding-bottom-normal" style="border-right: 1px solid #EEE;">
                    <div class="row padding-top-normal">
                        <div class="col-sm-2 text-center">
                            <img src="{{ HTTP_USER_PATH.$user->photo }}" style="width: 70px; height: 70px;" class="img-circle"/>
                        </div>
                        <div class="col-sm-4 padding-top-xs">
                            <p class="color-blue">{{ $user->name }}</p>
                            <p class="color-gray-normal"><i>{{ $user->is_business ? trans('common.business') : trans('common.individual') }}</i></p>
                        </div>
                        <div class="col-sm-6 text-right padding-top-sm">
                            @foreach ($user->subCategories as $subCategory)
                                <span class="label label-default">{{ $subCategory->subCategory->{$name} }}</span>
                            @endforeach
                        </div>
                    </div>
                    <h2 class="color-blue padding-top-sm">{{ trans('user.overview') }}</h2>
                    <div class="row">
                        <div class="col-sm-12">
                            {{ $user->description }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <hr/>
                        </div>
                    </div>
                    
                    <div class="portlet box yellow margin-top-normal">
                        <div class="portlet-title">
                    		<div class="caption">
                    			<i class="fa fa-navicon"></i> {{ trans('job.feedback_provided') }}
                    		</div>
                    	</div>
                        <div class="portlet-body ">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td style="width: 5%;">#</td>
                                        <td style="width: 15%;">{{ trans('common.user') }}</td>
                                        <td style="width: 25%;">{{ trans('job.rating') }}</td>
                                        <td>{{ trans('common.job') }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->rates as $key => $rate)
                                    <tr>
                                        <td rowspan="2">{{ $key + 1 }}</td>
                                        <td rowspan="2" class="text-center">
                                            <a href="{{ URL::route('user.detail', $rate->rater->slug) }}">
                                                <img style="width: 50px; height: 50px;" src="{{ HTTP_USER_PATH.$rate->rater->photo }}" class="img-circle">
                                                <br/>
                                                {{ $rate->rater->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <input id="js-number-score" type="number" name="score" class="rating" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xs' value="{{ $rate->score }}" readonly=true>
                                        </td>
                                        <td>
                                            <a href="{{ URL::route('job.detail', $rate->slug) }}">
                                                {{ $rate->job->name }}
                                            </a>
                                        </td>
                                        
                                    </tr>
                                    <tr>
                                        <td colspan="2"><i>{{ $rate->description }} ({{ $rate->created_at }})</i></td>
                                    </tr>
                                    @endforeach
                                </tbody>                                
                            </table>
                        </div>
                    </div>                    
                    
                    <div class="portlet box yellow margin-top-normal">
                        <div class="portlet-title">
                    		<div class="caption">
                    			<i class="fa fa-navicon"></i> {{ trans('job.posted_jobs') }}
                    		</div>
                    	</div>
                        <div class="portlet-body ">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>{{ trans('common.title') }}</td>
                                        <td>{{ trans('common.status') }}</td>
                                        <td>{{ trans('common.views') }}</td>
                                        <td>{{ trans('common.bids') }}</td>
                                        <td>{{ trans('common.category') }}</td>
                                        <td>{{ trans('common.min') }}</td>
                                        <td>{{ trans('common.max') }}</td>
                                        <td>{{ trans('common.avg') }}</td>
                                        <td>{{ trans('job.post_at') }}</td>
                                        <td>{{ trans('common.detail') }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->jobs as $key => $job)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ URL::route('job.detail', $job->slug) }}">{{ $job->name }}</a>                                        
                                        </td>
                                        <td>
                                            @if ($job->status == 'OPEN')
                                                <span class="label label-danger">{{ trans('common.'.$job->status) }}</span>
                                            @elseif ($job->status == 'PROGRESS')
                                                <span class="label label-info">{{ trans('common.'.$job->status) }}</span>
                                            @else
                                                <span class="label label-success">{{ trans('common.'.$job->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $job->count_view }}</td>
                                        <td>
                                            <a href="#" data-toggle="tooltip" data-placement="bottom" 
                                                title="
                                                @foreach ($job->bids as $bid)
                                                    {{ $bid->user->name.', ' }}
                                                @endforeach
                                                "
                                            >
                                                {{ count($job->bids) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ URL::route('job.post', 'category='.$job->category->id) }}">{{ $job->category->{$name} }}</a>
                                        </td>
                                        <td>{{ round($job->bids()->min('price'), 1)."&euro;" }}</td>
                                        <td>{{ round($job->bids()->max('price'), 1)."&euro;" }}</td>
                                        <td>{{ round($job->bids()->avg('price'), 1)."&euro;" }}</td>
                                        <td>{{ date(DATE_FORMAT, strtotime($job->created_at)) }}</td>
                                        <td>
                                            <button class="btn red btn-xs" data-toggle="tooltip" data-placement="bottom" title="{{ trans('common.overview') }}" id="js-btn-overview">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <tr style="display: none;">
                                        <td colspan="11">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="row row-no-margin padding-top-sm">                                           
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
                                                            <div class="row row-no-margin padding-top-sm">                                           
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
                                                            <?php 
                                                                $last = floor(count($job->details) / 2);
                                                                if ($last + 1 >= count($job->details)) {
                                        
                                                                } else {
                                                                    while ($job->details[$last]->question->name == $job->details[$last+1]->question->name) {
                                                                        $last = $last + 1;
                                                                    }
                                                                }
                                                            ?>
                                                            @for ($i = 0; $i <= $last; $i++)
                                                            <div class="row row-no-margin padding-top-sm">
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
                                                        
                                                        <div class="col-sm-6">
                                                            <div class="row row-no-margin padding-top-sm">                                      
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
                                                            @for ($i = $last + 1; $i < count($job->details); $i++)
                                                            <div class="row row-no-margin padding-top-sm">
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
                                                        <div class="row row-no-margin padding-top-sm">
                                                            <div class="col-sm-3 text-right">
                                                                <p class="font-weight-bold color-blue">
                                                                    {{ trans('common.attachment') }} : 
                                                                </p>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="portlet box yellow margin-top-normal">
                        <div class="portlet-title">
                    		<div class="caption">
                    			<i class="fa fa-navicon"></i> {{ trans('job.bid_jobs') }}
                    		</div>
                    	</div>
                        <div class="portlet-body ">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <td>#</td>
                                        <td>{{ trans('common.title') }}</td>
                                        <td>{{ trans('common.status') }}</td>
                                        <td>{{ trans('common.views') }}</td>
                                        <td>{{ trans('common.bids') }}</td>
                                        <td>{{ trans('common.category') }}</td>
                                        <td>{{ trans('common.min') }}</td>
                                        <td>{{ trans('common.max') }}</td>
                                        <td>{{ trans('common.avg') }}</td>
                                        <td>{{ trans('job.bid_at') }}</td>
                                        <td>{{ trans('common.detail') }}</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user->bids as $key => $bid)
                                    <?php $job = $bid->job; ?>
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ URL::route('job.detail', $job->slug) }}">{{ $job->name }}</a>                                        
                                        </td>
                                        <td>
                                            @if ($job->status == 'OPEN')
                                                <span class="label label-danger">{{ trans('common.'.$job->status) }}</span>
                                            @elseif ($job->status == 'PROGRESS')
                                                <span class="label label-info">{{ trans('common.'.$job->status) }}</span>
                                            @else
                                                <span class="label label-success">{{ trans('common.'.$job->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $job->count_view }}</td>
                                        <td>
                                            <a href="#" data-toggle="tooltip" data-placement="bottom" 
                                                title="
                                                @foreach ($job->bids as $bid)
                                                    {{ $bid->user->name.', ' }}
                                                @endforeach
                                                "
                                            >
                                                {{ count($job->bids) }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ URL::route('job.post', 'category='.$job->category->id) }}">{{ $job->category->{$name} }}</a>
                                        </td>
                                        <td>{{ round($job->bids()->min('price'), 1)."&euro;" }}</td>
                                        <td>{{ round($job->bids()->max('price'), 1)."&euro;" }}</td>
                                        <td>{{ round($job->bids()->avg('price'), 1)."&euro;" }}</td>
                                        <td>{{ date(DATE_FORMAT, strtotime($bid->created_at)) }}</td>
                                        <td>
                                            <button class="btn red btn-xs" data-toggle="tooltip" data-placement="bottom" title="Overview" id="js-btn-overview">
                                                <i class="fa fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    
                                    <tr style="display: none;">
                                        <td colspan="11">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="row row-no-margin padding-top-sm">                                           
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
                                                            <div class="row row-no-margin padding-top-sm">                                           
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
                                                            <?php 
                                                                $last = floor(count($job->details) / 2);
                                                                if ($last + 1 >= count($job->details)) {
                                        
                                                                } else {
                                                                    while ($job->details[$last]->question->name == $job->details[$last+1]->question->name) {
                                                                        $last = $last + 1;
                                                                    }
                                                                }
                                                            ?>
                                                            @for ($i = 0; $i <= $last; $i++)
                                                            <div class="row row-no-margin padding-top-sm">
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
                                                        
                                                        <div class="col-sm-6">
                                                            <div class="row row-no-margin padding-top-sm">                                      
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
                                                            @for ($i = $last + 1; $i < count($job->details); $i++)
                                                            <div class="row row-no-margin padding-top-sm">
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
                                                                            {{ $job->details[$i]->answer->name }}
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
                                                        <div class="row row-no-margin padding-top-sm">
                                                            <div class="col-sm-3 text-right">
                                                                <p class="font-weight-bold color-blue">
                                                                    {{ trans('common.attachment') }} : 
                                                                </p>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>                         
                </div>
                <div class="col-sm-4">
                    <div class="row row-no-margin margin-top-normal margin-bottom-xs border-bottom-gray">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-envelope-o"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.email') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ $user->email }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-phone"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.phone') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ $user->phone }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-map-marker"></span></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('common.city') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ isset($user->city_id) ? $user->city->name : '---' }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-globe"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.address') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ $user->address }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-flag"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.zip_code') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ $user->zip_code }}</b></p>
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-book"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.vat_id') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ $user->vat_id }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-graduation-cap"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.contact') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ $user->contact }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-gavel"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.bids_count') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ count($user->bids) }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-edit"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.posts_count') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ count($user->jobs) }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><i class="fa fa-clock-o"></i></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('user.joined_on') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ date(DATE_FORMAT, strtotime($user->created_at)) }}</b></p>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="padding-top-sm padding-bottom-xs">
            &nbsp;
        </div>        
    </div>    
@stop

@section('custom-scripts')
{{ HTML::script('/assets/js/bootstrap-tooltip.js') }}
{{ HTML::script('/assets/js/star-rating.min.js') }}  
@include('js.frontend.user.detail')
@stop

@stop
