<?php
    if (!isset($subPageNo)) {
        $subPageNo = 0;
    }
    
    $countFeed = \Frontend\FeedController::countFeed();
    $countMessage = \Frontend\MessageController::countMessage();
?>
@if (Session::has('user_id'))
<ul class="tabbable faq-tabbable margin-bottom-normal">
    <li class="{{ ($subPageNo == 11) ? 'active' : '' }}">
        <a href="{{ URL::route('job.dashboard') }}">
            {{ trans('page.dashboard') }}
            @if ($countFeed > 0)
            <span class="badge badge-danger pull-right">{{ $countFeed }}</span>
            @endif
		</a>
    </li>
    <li class="{{ ($subPageNo == 12) ? 'active' : '' }}"><a href="{{ URL::route('job.center') }}">{{ trans('page.job_center') }}</a></li>
    <li class="{{ ($subPageNo == 13) ? 'active' : '' }}">
        <a href="{{ URL::route('message.history') }}">
            {{ trans('page.message_center') }}
            @if ($countMessage > 0)
            <span class="badge badge-danger pull-right">{{ $countMessage }}</span>
            @endif            
        </a>
    </li>
    <li class="{{ ($subPageNo == 14) ? 'active' : '' }}"><a href="{{ URL::route('job.bids') }}">{{ trans('page.my_bids') }}</a></li>
    <li class="{{ ($subPageNo == 15) ? 'active' : '' }}"><a href="{{ URL::route('job.posts') }}">{{ trans('page.my_posts') }}</a></li>
    <li class="{{ ($subPageNo == 16) ? 'active' : '' }}"><a href="{{ URL::route('connection.purchase') }}">{{ trans('page.purchase_subscribe_bids') }}</a></li>
    <li class="{{ ($subPageNo == 18) ? 'active' : '' }}"><a href="{{ URL::route('user.reviews') }}">{{ trans('page.my_reviews') }}</a></li>    
    <li class="{{ ($subPageNo == 17) ? 'active' : '' }}"><a href="{{ URL::route('user.profile') }}">{{ trans('page.my_account') }}</a></li>
</ul>
@endif