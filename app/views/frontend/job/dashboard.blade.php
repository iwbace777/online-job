@extends('frontend.layout')
@section('custom-styles')
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
        <div class="row margin-top-sm">
            <div class="col-sm-3">
                <h2>&nbsp;</h2>
                @include("frontend.leftmenu")
            </div>
            <div class="col-sm-9">
                <div class="text-center">
                    <h2 class="color-blue"><i class="fa fa-dashboard" style="font-size: 30px;"></i>&nbsp;&nbsp;{{ trans('page.dashboard') }}</h2>
                </div>
                
                <div class="row margin-top-normal margin-bottom-normal">
                    <div class="col-sm-3">
                        <div class="dashboard-stat yellow">
							<div class="visual">
								<i class="fa fa-bar-chart-o"></i>
							</div>
							<div class="details">
								<div class="number">
                                    {{ $count_post['today'] }} /  {{ $count_post['week'] }} / {{ $count_post['month'] }}
								</div>
								<div class="desc font-size-sm font-weight-bold">
									 {{ trans('common.today') }} / {{ trans('common.week') }} / {{ trans('common.month') }}
									 <br/>
									 {{ trans('job.posted_jobs') }}
								</div>
							</div>
						</div>                    
                    </div>
                    
                    <div class="col-sm-3">
                        <div class="dashboard-stat yellow">
							<div class="visual">
								<i class="fa fa-bar-chart-o"></i>
							</div>
							<div class="details">
								<div class="number">
                                    {{ $count_bid['today'] }} /  {{ $count_bid['week'] }} / {{ $count_bid['month'] }}
								</div>
								<div class="desc font-size-sm font-weight-bold">
									 {{ trans('common.today') }} / {{ trans('common.week') }} / {{ trans('common.month') }}
									 <br/>
									 {{ trans('job.bid_jobs') }}
								</div>
							</div>
						</div>                    
                    </div>        
                                
                    <div class="col-sm-3">
                        <div class="dashboard-stat yellow">
							<div class="visual">
								<i class="fa fa-eye"></i>
							</div>
							<div class="details">
								<div class="number">
                                    {{ $worth_active }}&nbsp;&euro;
								</div>
								<div class="desc font-size-sm font-weight-bold">
									 {{ trans('job.worth_active_jobs') }}
								</div>
							</div>
						</div>                    
                    </div>                                
                    
                    <div class="col-sm-3">
                        <div class="dashboard-stat yellow">
							<div class="visual">
								<i class="fa fa-cube"></i>
							</div>
							<div class="details">
								<div class="number">
                                    {{ $worth_awarded }}&nbsp;&euro;
								</div>
								<div class="desc font-size-sm font-weight-bold">
									 {{ trans('job.worth_awarded_jobs') }}
								</div>
							</div>
						</div>                    
                    </div>                     
                </div>
                
                @foreach ($feeds as $feed)
                <div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true" id="js-btn-close-feed" data-id="{{ $feed->id }}"></button>
					@if ($feed->type == 'FD01')
					<strong>{{ trans('job.congratulation') }}!</strong>
					{{ trans('job.msg10') }} <a href="{{ URL::route('job.detail', $feed->job->slug) }}" class="alert-link">{{ $feed->job->name }}</a>.
					@elseif ($feed->type == 'FD02')
					<strong>{{ trans('job.great_news') }}!</strong>
					{{ trans('job.msg11') }} <a href="{{ URL::route('job.detail', $feed->job->slug) }}" class="alert-link">{{ $feed->job->name }}</a>					
					@elseif ($feed->type == 'FD03')
					<strong>{{ trans('job.great_news') }}!</strong>
					{{ trans('job.msg12') }} <a href="{{ URL::route('job.detail', $feed->job->slug) }}" class="alert-link">{{ $feed->job->name }}</a>
					@elseif ($feed->type == 'FD04')
					<strong>{{ trans('job.great_news') }}!</strong>
					{{ trans('job.msg13') }} <a href="{{ URL::route('job.detail', $feed->job->slug) }}" class="alert-link">{{ $feed->job->name }}</a>					
					@endif
					<div class="font-size-sm padding-top-xs">
					    <i>{{ date(TIME_FORMAT, strtotime($feed->created_at)) }}</i>
					    
					</div>
				</div>
				@endforeach
                
                <div class="portlet box yellow margin-top-normal">
                    <div class="portlet-title">
                		<div class="caption">
                			<i class="fa fa-navicon"></i> {{ trans('job.job_list') }}
                		</div>
                	</div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>{{ trans('common.title') }}</td>
                                    <td>{{ trans('common.status') }}</td>
                                    <td>{{ trans('common.views') }}</td>
                                    <td>{{ trans('common.bids2') }}</td>
                                    <td>{{ trans('common.category') }}</td>
                                    <td>{{ trans('common.min') }}</td>
                                    <td>{{ trans('common.max') }}</td>
                                    <td>{{ trans('common.avg') }}</td>
                                    <td>{{ trans('job.post_at') }}</td>
                                    <td></td>                               
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jobs as $key => $job)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        <a href="{{ URL::route('job.detail', $job->slug) }}">{{ $job->name }}</a>                                        
                                    </td>
                                    <td>
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
                                        
                                        @if ($is_applied)
                                            <span class="label label-danger">{{ trans('common.bidded') }}</span>
                                        @else
                                            <span class="label label-info">{{ trans('common.bid') }}</span>
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
                                            </div>
                                        </div>                                
                                    </td>
                                </tr>
                                
                                <tr data-id="{{ $job->id }}" style="display: none;">
                                    <td colspan="11">
                                        <div class="row {{ $job->user_id == Session::get('user_id') ? 'hide' : '' }}">
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
                            </tbody>
                        </table>
                        <div class="pull-right">{{ $jobs->links() }}</div>
                        <div class="clearfix"></div>
                    </div>
                </div>                  

            </div>
        </div>
    </div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/js/bootstrap-tooltip.js') }}
@include('js.frontend.job.dashboard')
@stop

@stop
