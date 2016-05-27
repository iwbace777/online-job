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
                    <h2 class="color-blue"><i class="fa fa-comments" style="font-size: 30px;"></i>&nbsp;&nbsp;{{ trans('message.messages') }}</h2>
                </div>
                
                <div class="portlet box yellow margin-top-normal">
                    <div class="portlet-title">
                		<div class="caption">
                			<i class="fa fa-navicon"></i> {{ trans('message.message_list') }}
                		</div>
                	</div>
                    <div class="portlet-body ">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>{{ trans('message.job') }}</td>
                                    <td>{{ trans('message.contractor') }}</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($messages as $key => $message)
                                <tr>
                                    <td style="position: relative;">
                                        {{ $key + 1 }}
                                    </td>
                                    <td>
                                        <a href="{{ URL::route('job.detail', $message->job->slug) }}">{{ $message->job->name }}</a>
                                        @if ($message->count_new > 0)
                                        &nbsp;&nbsp;&nbsp;
                                        <span class="badge badge-danger badge-roundless">{{ $message->count_new }}</span>
                                        @endif

                                        <button class="btn btn-default btn-sm pull-right" data-toggle="tooltip" data-placement="bottom" title="Overview" id="js-btn-overview">
                                            {{ trans('common.overview') }}
                                        </button>                                        
                                    </td>
                                    <td>
                                        @if ($message->is_sender == 1)
                                        <a href="{{ URL::route('user.detail', $message->receiver->slug) }}" data-toggle="tooltip" data-placement="bottom" data-html=true 
                                            title="{{ trans('common.name') }} : {{ $message->receiver->name }}<br/>
                                                   {{ trans('common.city') }} : {{ isset($message->receiver->city) ? $message->receiver->city->name : '---' }}<br/>
                                                   {{ trans('common.description') }} : {{ $message->receiver->description }}">
                                           {{ $message->receiver->name }}
                                       </a>
                                        @else
                                        <a href="{{ URL::route('user.detail', $message->sender->slug) }}" data-toggle="tooltip" data-placement="bottom" data-html=true
                                            title="{{ trans('common.name') }} : {{ $message->sender->name }}<br/>
                                                   {{ trans('common.city') }} : {{ isset($message->sender->city) ? $message->sender->city->name : '---' }}<br/>
                                                   {{ trans('common.description') }} : {{ $message->sender->description }}">
                                           {{ $message->sender->name }}
                                       </a>
                                        @endif
                                    </td>
                                    <td style="width: 100px;" class="text-center">
                                        @if ($message->is_sender == 1)
                                        <a href="{{ URL::route('message.detail', array($message->job->id, $message->sender_id, $message->receiver_id)) }}" class="btn yellow btn-sm">
                                            <i class="fa fa-comments"></i>                                        
                                            {{ trans('message.message_history') }}
                                        </a>
                                        @else
                                        <a href="{{ URL::route('message.detail', array($message->job_id, $message->receiver_id, $message->sender_id)) }}" class="btn yellow btn-sm">
                                            <i class="fa fa-comments"></i>
                                            {{ trans('message.message_history') }}
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                <?php $job = $message->job;?>
                                <tr style="display: none;">
                                    <td colspan="4">
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
                
            </div>
        </div>
    </div>
@stop

@section('custom-scripts')
{{ HTML::script('/assets/js/bootstrap-tooltip.js') }}
@include('js.frontend.message.history')
@stop

@stop
