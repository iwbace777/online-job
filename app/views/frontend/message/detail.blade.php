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
                			<i class="fa fa-navicon"></i> {{ trans('message.message_form') }}
                		</div>
                	</div>
                    <div class="portlet-body blog-item padding-top-normal">
                        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('message.send', array($jobId, $senderId, $receiverId)) }}">
                            <div class="form-group">
                                <label class="col-sm-2 control-label">{{ trans('common.message') }}</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" name="message" cols="50" rows="5"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-11">
                                    <button class="pull-right btn btn-primary" onclick="return onValidate();">{{ trans('common.send') }}</button>
                                </div>
                            </div>
                        </form>
                        <hr/>
                        @foreach ($messages as $message)
                            @if ($message->is_sender)
                                <div class="row margin-bottom-xs">
                                    <div class="col-sm-1 col-sm-offset-1 text-center">                                        
                                        <img src="{{ HTTP_USER_PATH.$message->sender_photo }}" alt="" class="media-object img-circle">
                                        <h4 class="media-heading margin-top-xs">
                                            <a href="{{ URL::route('user.detail', $message->sender_slug) }}">{{ $message->sender_name }}</a>
                                        </h4>
                                    </div>
                                    <div class="col-sm-7 margin-top-xs padding-top-xs" style="background: #F4F4F4; border-radius: 2px !important; margin-left: 20px;">
                                        <pre style="padding: 0px; border: none;">{{ $message->message }}</pre>
                                        <i style="color: #555; font-size: 10px;">{{ date(TIME_FORMAT, strtotime($message->created_at)) }}</i>
                                    </div>
                                </div>
                            @else
                                <div class="row margin-bottom-xs">
                                    <div class="col-sm-7 col-sm-offset-3 margin-top-xs padding-top-xs text-right" style="background: #F4F4F4; border-radius: 2px !important;">
                                        <pre style="padding: 0px; border: none;">{{ $message->message }}</pre>
                                        <i style="color: #555; font-size: 10px;">{{ date(TIME_FORMAT, strtotime($message->created_at)) }}</i>                                        
                                    </div>
                                    <div class="col-sm-1 text-center">                                        
                                        <img src="{{ HTTP_USER_PATH.$message->sender_photo }}" alt="" class="media-object img-circle">
                                        <h4 class="media-heading margin-top-xs">
                                            <a href="{{ URL::route('user.detail', $message->sender_slug) }}">{{ $message->sender_name }}</a>
                                        </h4>
                                    </div>                                        
                                </div>                                        
                            @endif
                        @endforeach
                    </div>
                </div>                 
            </div>
        </div>
    </div>
@stop

@section('custom-scripts')
<script>
function onValidate() {
    var msg = $("textarea[name='message']").val();
    if (msg == '') {
        return false;
    } else {
        return true;
    }
}
</script>
@stop

@stop
