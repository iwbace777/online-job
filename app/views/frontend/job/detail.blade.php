@extends('frontend.layout')

@section('meta-seo')
    <meta name="description" content="{{ $job->name }}, {{ $job->category->name }}">
    <meta name="keywords" content="Inquirymall, Service, Job Seeker, Job Provider, Customer, Business, Finland, Helsinki, Finternet, {{ $job->category->name }}, {{ $job->name }}">
    <meta name="author" content="Finternet-Group">
@stop

@section('title')
    {{ $job->slug." | ".$job->category->name." | ".$job->user->name." | ".SITE_NAME }}
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
                <div class="text-uppercase col-sm-5 color-gray-light font-size-lg">{{ trans('job.project_title') }}</div>
                <div class="text-uppercase col-sm-2 color-gray-light font-size-lg">{{ trans('common.by') }}</div>
                <div class="text-uppercase col-sm-2 color-gray-light font-size-lg">{{ trans('job.posted_date') }}</div>
                <div class="text-uppercase col-sm-2 color-gray-light font-size-lg">{{ trans('common.status') }}</div>
            </div>
        </div>
        <div class="container detail-inner job-detail border-bottom-gray padding-bottom-sm">
            <div class="row row-no-margin padding-top-xs padding-bottom-xs border-bottom-gray">
                <div class="col-sm-5">
                    <b>
                    {{ $job->name }}
                    </b>
                </div>
                <div class="col-sm-2">
                    <a href="{{ URL::route('user.detail', $job->user->slug) }}" data-toggle="tooltip" data-placement="bottom" data-html="true"
                        data-title="{{ trans('common.name') }} : {{ $job->user->name }}<br/>{{ trans('common.city') }} : {{ (isset($job->user->city_id) ? $job->user->city->name : '---') }}<br/>{{ trans('common.description') }} : {{ $job->user->description }}">
                        {{ $job->user->name }}
                    </a>
                </div>
                <div class="col-sm-2">{{ date(TIME_FORMAT, strtotime($job->created_at)) }}</div>
                <div class="col-sm-1 text-center">
                    <div class="label-danger label-button text-center">{{ $job->status }}</div>
                </div>
                <div class="col-sm-2">
                    @if ($type == 1)
			            <?php
				        $subCategories = [];
				        foreach ($user->subCategories as $item) {
                            $subCategories[] = $item->sub_category_id;
                        }
                        $subCategory = $job->sub_category_id;
                        $is_open = in_array($subCategory, $subCategories);
				        ?>
                        <button class="btn btn-block blue btn-sm" id="js-btn-bid"><i class="fa fa-gravel"></i>&nbsp;{{ trans('common.bid') }}</button>
                    @elseif ($type == 2)
                        <div class="label-success label-button text-center">{{ trans('common.bidded') }}</div>
                    @elseif ($type == 3)
                        <div class="label-success label-button text-center"><i class="fa fa-heart"></i>&nbsp;{{ trans('job.my_job') }}</div>
                    @elseif ($type == 4)
                        <a class="btn btn-block red btn-sm" href="{{ URL::route('job.complete', $job->id) }}"><i class="fa fa-bookmark-o"></i>&nbsp;{{ trans('job.complete_project') }}</a>
                    @elseif ($type == 5)
                        <button class="btn btn-block blue btn-sm" id="js-btn-feedback"><i class="fa fa-star"></i>&nbsp;{{ trans('job.give_feedback') }}</button>
                    @elseif ($type == 6)
                        <div class="label-danger label-button text-center">{{ trans('job.feedback_provided') }}</div>                        
                    @elseif ($type == 10)
                        <a href="{{ URL::route('user.login') }}" class="btn btn-block red btn-sm">{{ trans('common.login') }}</a>
                    @endif

                </div>
            </div>
            
            @if (isset($alert))
            <div class="row row-no-margin margin-top-normal">
                <div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">{{ trans('common.close') }}</span>
                    </button>
                    <p>{{ $alert['msg'] }}</p>
                </div>
            </div>
            @endif                          
            
            <div class="row row-no-margin thumbnail margin-top-normal hide" id="js-div-submit">
                <form method="POST" action="{{ URL::route('job.doBid') }}">
                    <h3 class="color-blue padding-top-normal" style="padding-left: 20px;">{{ trans('job.submit_proposal') }}</h3>
                    <hr>
                    <input type="hidden" name="job_id" value="{{ $job->id }}"/>
                    <div class="col-sm-7 col-sm-offset-1">
                        <div class="form-group">
                            <label for="description">{{ trans('common.description') }} *</label>
                            <textarea name="description" class="form-control" rows="6"></textarea>
                         </div>                            
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="price">{{ trans('common.price') }} *</label>
                            <input type="text" name="price" class="form-control">
                            <button class="btn btn-lg btn-primary text-uppercase pull-right margin-top-sm" onclick="return validate();">{{ trans('common.submit') }} <span class="glyphicon glyphicon-ok-circle"></span></button>
                         </div>                            
                    </div>
                </form>                
            </div>
            
            <div class="row row-no-margin thumbnail margin-top-normal hide" id="js-div-feedback">
                <form method="POST" action="{{ URL::route('job.giveFeedback') }}">
                    <h3 class="color-blue padding-top-normal" style="padding-left: 20px;">{{ trans('job.give_feedback') }}</h3>
                    <hr>
                    <input type="hidden" name="job_id" value="{{ $job->id }}"/>
                    <div class="col-sm-7 col-sm-offset-1">
                        <div class="form-group">
                            <label for="description">{{ trans('job.comment') }} *</label>
                            <textarea name="description" class="form-control" rows="6"></textarea>
                         </div>                            
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="price">{{ trans('job.rating') }} *</label>
                            <input id="js-number-score" type="number" name="score" class="rating" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xs' value="3">
                            <button class="btn btn-lg btn-primary text-uppercase pull-right margin-top-sm">{{ trans('common.submit') }} <span class="glyphicon glyphicon-ok-circle"></span></button>
                         </div>                            
                    </div>
                </form>                
            </div>            
            
            <div class="row row-no-margin padding-top-xs padding-bottom-xs border-bottom-gray">
                <div class="col-sm-2 text-center font-weight-bold font-size-xl">
                    {{ trans('common.views') }} : <span class="color-blue">{{ $job->count_view }}</span> 
                </div>
                <div class="col-sm-2 text-center font-weight-bold font-size-xl">
                    {{ trans('common.bids2') }} : <span class="color-blue">{{ count($job->bids) }}</span>
                </div>
                <div class="col-sm-2 text-center font-weight-bold font-size-xl">
                    {{ trans('common.avg') }} : <span class="color-blue">{{ round($job->bids()->avg('price'), 1) }}&euro;</span> 
                </div>
                <div class="col-sm-4 text-center font-weight-bold font-size-normal">
                    &nbsp;
                </div>
                <div class="col-sm-2 text-center">
                    <!-- a href="#"><i class="fa fa-facebook" style="width: 40px;"></i></a>
                    <a href="#"><i class="fa fa-twitter" style="width: 40px;"></i></a>
                    <a href="#"><i class="fa fa-google-plus" style="width: 40px;"></i></a -->
                </div>
            </div>
            <div class="row row-no-margin">
                <div class="col-sm-8 padding-bottom-normal" style="border-right: 1px solid #EEE;">
                    <h2 class="color-blue padding-top-sm">{{ trans('job.job_detail') }}</h2>
                    
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
                            <div class="col-sm-9">
                                @foreach ($job->attachments as $attachment)
                                    <a href="{{ HTTP_ATTACHMENT_PATH.$attachment->sys_name }}" class="font-size-lg" target="_blank">
                                        <i class="icon-paper-clip"></i>
                                        {{ $attachment->org_name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <hr/>
                    <h2 class="color-blue">{{ trans('common.bids1') }}</h2>
                    <table class="table table-striped table-hover table-bids">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th style="width: 120px;" class="text-center">{{ trans('common.photo') }}</th>
                                <th class="text-center">{{ trans('common.name') }}</th>
                                <th class="text-center">{{ trans('common.price') }}</th>
                                <th class="text-center">{{ trans('job.bid_at') }}</th>
                                <th style="width: 70px;"></th>
                                <th style="width: 70px;"></th>
                                <th style="width: 70px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($job->bids as $key => $bid)
                                <tr data-id="{{ $bid->id }}">
                                    <td>{{ $key + 1 }}</td>
                                    <td class="text-center"><img src="{{ HTTP_USER_PATH.$bid->user->photo; }}" style="width: 60px; height: 60px;" class="img-circle"/></td>
                                    <td class="text-center">
                                        <a href="{{ URL::route('user.detail', $bid->user->slug) }}" data-toggle="tooltip" data-placement="bottom" data-html="true"
                                            data-title="{{ trans('common.city') }} : {{ $bid->user->name }}<br/>{{ trans('common.city') }} : {{ (isset($bid->city) ? $bid->city->name : '---') }}<br/>{{ trans('common.description') }} : {{ $bid->user->description }}">
                                            {{ $bid->user->name }}
                                        </a>
                                    </td>
                                    <td class="text-center">{{ $bid->price }}&nbsp;&euro;</td>
                                    <td class="text-center">{{ date(DATE_FORMAT, strtotime($bid->created_at)) }}</td>
                                    @if ($job->status == 'OPEN' && Session::has('user_id') && Session::get('user_id') == $job->user_id)
                                    <td><button class="btn btn-default btn-sm" id="js-btn-proposal">{{ trans('job.proposal') }}</button></td>                                    
                                    <td><button class="btn btn-default btn-sm" id="js-btn-message" data-receiver="{{ $bid->user->id }}" data-sender="{{ $job->user_id }}" data-job="{{ $job->id }}">{{ trans('common.message') }}</button></td>
                                    <td><button class="btn btn-default btn-sm" id="js-btn-hire">{{ trans('common.hire') }}</button></td>
                                    @elseif ($job->status == 'PROGRESS')
                                    <td><button class="btn btn-default btn-sm" id="js-btn-proposal">{{ trans('job.proposal') }}</button></td>                                    
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>                                
                                    @else
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    @endif
                                </tr>
                                <tr style="display: none;">
                                    <td colspan="8"><b>{{ trans('job.proposal') }} : </b>{{ $bid->description }}</td>
                                </tr>
                            @endforeach
                            @if (count($job->bids) == 0)
                                <tr>
                                    <td colspan="8" class="text-center">{{ trans('job.no_bidders') }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-4">
                    <div class="row row-no-margin margin-top-normal padding-bottom-xs border-bottom-gray">
                        <div class="col-sm-3 text-center">
                            <img src="{{ HTTP_USER_PATH.$job->user->photo }}" class="img-circle margin-top-xs" style="width: 70px; height: 70px; ">
                        </div>
                        <div class="col-sm-9">
                            <h4>
                                <b>
                                    <a href="{{ URL::route('user.detail', $job->user->slug) }}" data-toggle="tooltip" data-placement="bottom" data-html="true"
                                        data-title="{{ trans('common.name').' : '.$job->user->name }}<br/>{{ trans('common.city') }} : {{ (isset($job->user->city_id) ? $job->user->city->name : '---') }}<br/>{{ trans('common.description') }} : {{ $job->user->description }}">
                                        {{ $job->user->name }}
                                    </a>                                    
                                </b>
                            </h4>
                            <p class="color-gray-light" style="font-size: 11px;">{{ trans('job.member_since') }} : {{ date(DATE_FORMAT, strtotime($job->user->created_at)) }}</p>
                            @if (Session::has('user_id') && Session::get('user_id') != $job->user_id)
                                <button class="btn btn-info btn-xs" id="js-btn-message" data-receiver="{{ $job->user_id }}" data-sender="{{ Session::get('user_id') }}" data-job="{{ $job->id }}">{{ trans('common.send_message') }}</button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="row row-no-margin margin-top-normal margin-bottom-xs border-bottom-gray">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-map-marker"></span></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('job.location') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ isset($job->user->city) ? $job->user->city->name : '---' }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-stats"></span></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('job.total_spent') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>&euro;{{ $sum_spent }}</b></p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-send"></span></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('job.hires') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ $count_hire }}</b></p>
                                </div>
                            </div>
                            <div class="form-group color-gray-dark">
                                <label class="col-sm-1 control-label"><span class="glyphicon glyphicon-briefcase"></span></label>
                                <label class="col-sm-5 control-label" style="font-weight: normal; text-align: left;">{{ trans('job.projects_posted') }} : </label>
                                <div class="col-sm-5">
                                    <p class="form-control-static text-right"><b>{{ count($job->user->jobs) }}</b></p>
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
    <form method="post" action="{{ URL::route('job.hire') }}" id="js-frm-hire">
        <input type="hidden" name="bid_id"/>
    </form>
@stop

@section('custom-scripts')
    {{ HTML::script('/assets/js/bootstrap-tooltip.js') }}
    {{ HTML::script('/assets/js/star-rating.min.js') }}        
    @include('js.frontend.job.detail')
@stop

@stop
