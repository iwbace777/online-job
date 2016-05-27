@extends('frontend.layout')

@section('main')
<div class="main" style="background: url(/assets/img/background.jpg); background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 margin-top-xl margin-bottom-xl">
                <form role="form" method="post" action="{{ URL::route('user.doLogin') }}">
                    <div class="form-group">
                        <div class="row text-center">
                            <p class="form-control-static">
                                <h2 class="text-center text-uppercase">{{ trans('user.welcome_back') }}!</h2>
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
                    
                    <div class="margin-top-lg"></div>
                    <div class="form-group">
                        <label class="control-label">{{ trans('user.email') }}</label>
                        <input type="text" class="form-control input-lg" placeholder="{{ trans('user.email') }}" name="email">
                    </div>
                    <div class="margin-top-lg"></div>
                    <div class="form-group">
                        <label class="control-label">{{ trans('user.password') }}</label>
                        <input type="password" class="form-control input-lg" placeholder="{{ trans('user.password') }}" name="password">
                    </div>
                    <div class="margin-top-lg"></div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-4">
                                <a href="{{ URL::route('user.forgotPassword') }}" class="btn btn-link">{{ trans('user.forgot_password') }}</a>
                            </div>
                            
                            <div class="col-sm-8 text-right">                            
                                <label class="checkbox-inline">
    							    <input type="checkbox" id="js-chk-is-remember" name="is_remember" value="1"> {{ trans('user.remember_me') }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    							</label>
                                <button type="submit" class="btn green btn-lg">
                                    {{ trans('user.sign_in') }} <span class="glyphicon glyphicon-ok-circle"></span>
                                </button>
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
<script>
$(document).ready(function() {
    $("input#js-chk-is-remember").click(function() {
        if ($(this).prop('checked')) {
            $(this).val() = 1;
        } else {
            $(this).val() = 0;
        }
            
    });
    $("button#js-btn-resend").click(function() {
        $.ajax({
            url: "{{ URL::route('async.user.active') }}",
            dataType : "json",
            type : "POST",
            data : { user_id : $(this).attr('data-id') },
            success : function(data){
                if (data.result == 'success') {
                    bootbox.alert(data.msg);
                    window.setTimeout(function(){
                        bootbox.hideAll();
                    }, 2000);                            
                    return;                            
                } else {
                    bootbox.alert("{{ trans('user.msg_error_email') }}");
                    window.setTimeout(function(){
                        bootbox.hideAll();
                    }, 2000);                               
                    return;
                }
            }
        });          
    });
});
</script>
@stop

@stop
