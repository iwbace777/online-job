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
        <div class="row margin-top-sm margin-bottom-normal">
            <div class="col-sm-3">
                <h2>&nbsp;</h2>
                @include("frontend.leftmenu")
            </div>
            <div class="col-sm-9">               
                @if (isset($alert))
                <div class="alert alert-{{ $alert['type'] }} alert-dismissibl fade in">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    <p>
                        {{ $alert['msg'] }}
                    </p>
                </div>
                @endif
                                
                @if ($errors->has())
                <div class="alert alert-danger alert-dismissibl fade in">
                    <button type="button" class="close" data-dismiss="alert">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span>
                    </button>
                    @foreach ($errors->all() as $error)
                		{{ $error }}		
                	@endforeach
                </div>
                @endif                
                
                <div class="row margin-top-xs margin-bottom-normal">
                    <form method="post" action="{{ URL::route('connection.buy.do') }}">
                        <div class="col-sm-6 text-right">
                            <h3 class="color-blue"><b>{{ trans('connection.purchase_bids_amount') }} : </b></h3>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" name="count" class="form-control">
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-primary btn-block" onclick="return validate();">
                                <i class="fa fa-check-circle-o"></i>&nbsp;{{ trans('common.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
                
                <div class="row margin-top-sm">
                    <div class="col-sm-12 text-center">
                        <p>BANK : <b>{{ BANK_ADDRESS1 }}&nbsp;{{ BANK_ADDRESS2 }}</b></p>
                        <p>1 {{ trans('common.bid') }} : <b>{{ CONNECTION_PRICE }}&euro;</b></p>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-12"><hr/></div>
                </div>
                                
                <div class="text-center margin-bottom-lg">
                    <h2 class="color-blue"><i class="fa fa-cube" style="font-size: 30px;"></i>&nbsp;&nbsp;{{ trans('connection.subscribe_bids') }}</h2>
                </div>
                <div class="row thumbnail">
                    @if (!$is_subscribed)
                        @foreach ($plans as $plan)
                        <div class="col-md-4">
                            <div class="pricing hover-effect">
                                <div class="pricing-head">
                                    <h3>{{ $plan->name }}</h3>
                                    <h4><i>&euro;</i>{{ $plan->price }}</h4>
                                </div>
                                <ul class="pricing-content list-unstyled text-center">
                                  <li class="margin-top-sm">
                                    <h4>
                                        <i class="fa fa-star"></i>{{ $plan->count." ".trans('common.bids') }}
                                    </h4>
                                  </li>
                                </ul>
                                <div class="pricing-footer">
                                    <form action="{{ URL::route('connection.subscribe.create', $plan->plan_code) }}" method="POST">
                                        <script
                                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                            data-key="{{ STRIPE_PUBLISH_KEY }}"
                                            data-amount="{{ $plan->price * 100 }}"
                                            data-name="{{ SITE_NAME }}"
                                            data-description="{{ $plan->name.' '.trans('connection.subscription') }}"
                                            data-image="/assets/img/128x128.png">
                                        </script>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="col-sm-4 col-sm-offset-4">
                            <a class="btn yellow btn-block" href="{{ URL::route('connection.subscribe.cancel') }}">
                                {{ trans('connection.cancel_subscription') }}
                            </a>
                        </div>
                    @endif
                </div>                
                
                <div class="row">
                    <div class="col-sm-10 col-sm-offset-1">
                        <hr/>
                    </div>
                </div>
                                
                <div class="row">
                    <div class="col-sm-12 text-center margin-top-normal">
                        <h1 class="color-blue">{{ trans('connection.bid_count') }} : {{ $user->count_connection }}</h1>
                    </div>
                </div>
                        
            </div>
        </div>
    </div>
    
	<?php $paypal_url = 'https://'.PAYPAL_SERVER.'/cgi-bin/webscr'; ?>
	<form id="js-frm-payment" method="post" action="<?php echo $paypal_url; ?>" class="hide">
		<input type="hidden" name="business" value="<?php echo htmlspecialchars(PAYPAL_BUSINESS); ?>">
		<input type="hidden" name="cmd" value="_xclick">
		<input type="hidden" name="item_name" value="{{ SITE_NAME }} Payment">
		<input type="hidden" name="amount">
		<input type="hidden" name="invoice" >
		<input type="hidden" name="currency_code" value="EUR">
		<input type="hidden" name="notify_url" value="{{ URL::route('connection.purchase.ipn') }}">
		<input type="hidden" name="return" value="{{ URL::route('connection.purchase.success') }}">
		<input type="hidden" name="cancel_return" value="{{ URL::route('connection.purchase.failed') }}">
		<input type="hidden" name="no_shipping" value="1">
		<input type="hidden" name="email">
	</form>	     
@stop

@section('custom-scripts')
    @include('js.frontend.connection.purchase')
@stop

@stop
