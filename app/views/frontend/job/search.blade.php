@extends('frontend.layout')

@section('custom-styles')
    {{ HTML::style('/assets/metronic/global/plugins/typeahead/typeahead.css') }}
    @if (Session::get('locale') == 'sk')
    <style>
    ul.ul-project-category a {
        font-size: 12px;
    }    
    </style>
    @endif
        
    <style>
        .input-lg {
            height: 46px !important;
            padding: 6px 30px !important;
            border: 2px solid #FFDE00 !important;
        }
        .input-icon i {
            color: #6C5A01;
        }
    </style>      
@stop

@section('main')
    @if(Session::get('locale') == 'sk')
    <?php $name = 'name2'; ?>
    @else
    <?php $name = 'name'; ?>
    @endif
    <div class="search-container-image">
        <div class="search-container-color">
            <div class="container">
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1" style="background-color: rgba(255, 255, 255, 0.85); padding: 50px; border-radius: 10px !important; padding-top: 30px;">
                        <h2 class="text-center">{{ trans('job.search_jobs_anything') }} ...</h2>
                        <form class="form-horizontal margin-top-normal" method="post" action="{{ URL::route('job.search', $category) }}" id="js-frm-search">
                            <div class="col-sm-4 col-sm-offset-1">
                                <div class="form-group" style="width: 90%; margin-left: 5%;">
                                    <div class="input-icon">
                                        <i class="fa fa-globe" style="margin-top: 16px;"></i>
                                        <input type="text" class="form-control input-lg input-circle" name="keyword" placeholder="{{ trans('common.keyword') }}..." value="{{ $keyword }}">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-sm-4">
                                <div class="form-group" style="width: 90%; margin-left: 5%;">
                                    <div class="input-icon">
                                        <i class="fa fa-tag" style="margin-top: 16px;"></i>
                                        <select class="typeahead form-control input-lg input-circle" name="category" id="category">
                                            <option value=''>{{ trans('common.all_category') }}</option>
                                            @foreach ($categories as $key => $value)
                                                <option value='{{ $value->slug }}' {{ ($value->slug == $category) ? 'selected' : '' }}>{{ $value->{$name} }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>                            
                            </div>                           

                            <div class="col-sm-2 text-center">
                                <div class="form-group">
                                    <button type="button" class="btn btn-primary btn-block btn-circle btn-lg" onclick="validate();">
                                        <i class="fa fa-search" style="font-size: 18px;"></i>&nbsp;{{ trans('common.search') }}
                                    </button>
                                </div>
                            </div>
                        </form>                    
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- START : Projects for you -->
    <div class="container" id="projects">
        <div class="row margin-top-xl text-center">
            <h1 class="color-blue">{{ trans('job.jobs_searched', array('count' => $count_job)) }}</h1>
        </div>
    </div>
    <!-- END : Projects for you -->
    
    <?php if (!isset($category)) { $category = 0; } ?>
    <div class="container">
        <div class="row margin-top-lg">
            <ul class="nav nav-tabs nav-justified ul-project-category" role="tablist">
                <li class="{{ ($category == '') ? 'active' : '' }}">
                    <a href="{{ URL::route('job.search') }}">{{ trans('job.all') }}</a>
                </li>
                @foreach ($categories as $key => $item)
                @if ($key < 6 )
                <li class="{{ ($category == $item->slug) ? 'active' : '' }}">
                    <a href="{{ URL::route('job.search', $item->slug) }}">{{ (strlen($item->{$name}) > 14 ? substr($item->{$name}, 0, 14)."..." : $item->{$name})." (".$count_categories[$key].")" }}</a>
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
                            <th>{{ trans('common.by') }}</th>
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
        </div>
    </div>
@stop

@section('custom-scripts')
    {{ HTML::script('/assets/metronic/global/plugins/typeahead/handlebars.min.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/typeahead/typeahead.bundle.min.js') }}
    {{ HTML::script('/assets/js/bootstrap-tooltip.js') }}    
    @include('js.frontend.job.search')
@stop

@stop
