@extends('frontend.layout')

@section('main')
<div class="main" style="background: url(/assets/img/background.jpg); background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 margin-top-xl margin-bottom-xl">
                <form role="form" method="post" action="{{ URL::route('user.sendForgotPasswordEmail') }}">
                    <div class="form-group">
                        <div class="row text-center">
                            <p class="form-control-static">
                                <h2 class="text-center">{{ trans('user.did_forgot_password') }}</h2>
                            </p>
                            <p class="form-control-static">
                                <h4 class="text-center">{{ trans('user.enter_email_to_reset') }}</h4>
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
                    <div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
                        <button type="button" class="close" data-dismiss="alert">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">{{ trans('common.close') }}</span>
                        </button>
                        <p>
                            {{ $alert['msg'] }}
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
                        <div class="text-right">                            
                            <button type="submit" class="btn green btn-lg">
                                {{ trans('common.submit') }} <span class="glyphicon glyphicon-ok-circle"></span>
                            </button>
                        </div>
                    </div>
                    <div class="margin-top-lg"></div>
                </form>    
            </div>
        </div>
   </div>
</div>
@stop

@stop
