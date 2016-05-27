@extends('frontend.layout')
@section('custom-styles')
{{ HTML::style('/assets/metronic/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') }}
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
        <div class="row margin-top-sm margin-bottom-normal">
            <div class="col-sm-3">
                <h2>&nbsp;</h2>
                @include("frontend.leftmenu")
            </div>
            <div class="col-sm-9">
                <div class="text-center margin-bottom-lg">
                    <h2 class="color-blue"><i class="fa fa-star" style="font-size: 30px;"></i>&nbsp;&nbsp;{{ trans('page.my_reviews') }}</h2>
                </div>
                
                <div class="portlet box yellow margin-top-normal">
                    <div class="portlet-title">
                		<div class="caption">
                			<i class="fa fa-navicon"></i> {{ trans('page.my_reviews') }}
                		</div>
                	</div>
                    <div class="portlet-body">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td>#</td>
                                    <td>{{ trans('job.job_name') }}</td>
                                    <td>{{ trans('user.name') }}</td>
                                    <td>{{ trans('common.description') }}</td>
                                    <td>{{ trans('common.created_at') }}</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($user->rates as $key => $rate)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><a href="{{ URL::route('job.detail', $rate->job->slug) }}">{{ $rate->job->name }}</a></td>
                                    <td><a href="{{ URL::route('user.detail', $rate->rater->slug) }}">{{ $rate->rater->name }}</a></td>
                                    <td>
                                        <p>
                                            {{ $rate->description }}
                                        </p>
                                        
                                        <input id="js-number-score" type="number" name="score" class="rating" min=0 max=5 step=1 data-show-clear=false data-show-caption=false data-size='xs' value="{{ $rate->score }}" readonly=true>
                                    </td>
                                    <td>{{ $rate->created_at }}</td>
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
{{ HTML::script('/assets/js/star-rating.min.js') }}
@stop

@stop
