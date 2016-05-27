@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Job Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Job</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Detail</span>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('content')

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

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-pencil-square-o"></i> Detail Job
		</div>
	</div>
	<div class="portlet-body">
        <div class="form-horizontal" role="form">

            <div class="row row-no-margin padding-top-sm">
                <div class="col-sm-3 text-right">
                    <label class="form-control-static font-weight-bold">
                        Name : 
                    </label>
                </div>
                <div class="col-sm-3">
                    <label class="form-control-static">
                        {{ $job->name }}
                    </label>
                </div>
                <div class="col-sm-3 text-right">
                    <label class="form-control-static font-weight-bold">
                        City : 
                    </label>
                </div>
                <div class="col-sm-3">
                    <label class="form-control-static">
                        {{ $job->city_id ? ($job->district_id ? $job->city->name."(".$job->district->name.")" : $job->city->name) : '---' }}
                    </label>
                </div>                 
            </div>
                    
            <div class="row row-no-margin padding-top-sm">
                <div class="col-sm-3 text-right">
                    <label class="form-control-static font-weight-bold">
                        Category : 
                    </label>
                </div>
                <div class="col-sm-3">
                    <label class="form-control-static">
                        {{ $job->category->name }}
                    </label>
                </div>
                <div class="col-sm-3 text-right">
                    <label class="form-control-static font-weight-bold">
                        Sub Category : 
                    </label>
                </div>
                <div class="col-sm-3">
                    <label class="form-control-static">
                        {{ isset($job->sub_category_id) ? $job->subCategory->name : '' }}
                    </label>
                </div>                               
            </div>
            
            <div class="row row-no-margin padding-top-sm">
                <div class="col-sm-3 text-right">
                    <label class="form-control-static font-weight-bold">
                        Job Poster : 
                    </label>
                </div>
                <div class="col-sm-8">
                    <label class="form-control-static">
                        <a href="{{ URL::route('backend.user.edit', $job->user->id) }}">
                            {{ $job->user->name }}
                        </a>
                    </label>
                </div>
            </div>            
            
            <div class="row">
                <div class="col-sm-6">
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
                                    {{ $job->details[$i]->question->name }} :
                                @elseif ($job->details[$i]->question->name != $job->details[$i-1]->question->name) 
                                    {{ $job->details[$i]->question->name }} :
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p>
                                @if (isset($job->details[$i]->answer_id))
                                    {{ $job->details[$i]->answer->name }}
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
                    @for ($i = $last + 1; $i < count($job->details); $i++)
                    <div class="row row-no-margin padding-top-sm">
                        <div class="col-sm-6 text-right">
                            <p class="font-weight-bold color-blue">
                                @if ($i == 0)
                                    {{ $job->details[$i]->question->name }} :
                                @elseif ($job->details[$i]->question->name != $job->details[$i-1]->question->name) 
                                    {{ $job->details[$i]->question->name }} :
                                @endif
                            </p>
                        </div>
                        <div class="col-sm-6">
                            <p>
                                @if (isset($job->details[$i]->answer_id))
                                    {{ $job->details[$i]->answer->name }}
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
            
            @if ($job->description != '')
                <div class="row">
                    <div class="col-sm-3 text-right">
                        <p class="font-weight-bold color-blue">
                            {{ trans('common.description') }} : 
                        </p>
                    </div>
                    <div class="col-sm-8">
                        {{ $job->description }}
                    </div>
                </div>                                        
            @endif                   
            
            @if (count($job->attachments) > 0)
                <div class="row row-no-margin padding-top-sm">
                    <div class="col-sm-3 text-right">
                        <label class="form-control-static font-weight-bold">
                            Attachment : 
                        </label>
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
          
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <a href="{{ URL::route('backend.job') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Bidder List
		</div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>VAT ID</th>
                    <th>Price</th>
                    <th>Bidded At</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($job->bids as $key => $bid)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>
                            <a href="{{ URL::route('backend.user.edit', $bid->user->id) }}">
                                {{ $bid->user->name }}
                            </a>
                        </td>
                        <td>{{ $bid->user->email }}</td>
                        <td>{{ $bid->user->vat_id }}</td>
                        <td>{{ $bid->price }}</td>
                        <td>{{ $bid->created_at }}</td>
                        <td>
                            {{ $bid->note }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Newsletter User List
		</div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Bidded</th>
                    <th>Bids Left</th>
                    <th>Sent At</th>
                    <th>Read</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($job->emailHistories as $key => $emailHistory)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>
                            <a href="{{ URL::route('backend.user.edit', $emailHistory->user_id) }}">{{ $emailHistory->user->name }}</a>
                            
                        </td>
                        <td>{{ $emailHistory->user->email }}</td>
                        <td>{{ $emailHistory->user->phone }}</td>
                        <td>{{ count($emailHistory->user->bids) }}</td>
                        <td>
                            <div class="pull-left">
                            {{ $emailHistory->user->count_connection }} &nbsp;&nbsp;
                            </div>
                            <form method="post" action="{{ URL::route('backend.user.addConnection') }}" class="pull-left">
                                <input type="hidden" name="user_id" value="{{ $emailHistory->user_id }}"/>
                                <input type="text" class="form-control input-sm pull-left" name="count_connection" value="0" style="width: 40px;"/>
                                <button class="btn btn-primary btn-sm pull-left"><i class="fa fa-plus"></i></button>
                            </form>
                        </td>
                        <td>{{ $emailHistory->created_at }}</td>
                        <td>
                            {{ $emailHistory->is_read ? 'Yes' : 'No' }}
                        </td>
                        <td>
                            @if (!$emailHistory->is_read)
                            <button class="btn btn-default btn-sm" data-id="{{ $emailHistory->id }}" id="js-btn-resend">Resend</button>
                            @endif
                            <button class="btn btn-sm btn-primary" onclick="onShowBid(this)">Bid</button>
                        </td>
                    </tr>
                    <tr style="display: none;">
                        <td colspan=9>
                            <form method="post" action="{{ URL::route('backend.job.doBid') }}">
                                <input type="hidden" name="job_id" value="{{ $job->id }}"/>
                                <input type="hidden" name="user_id" value="{{ $emailHistory->user_id}}"/>
                                <div class="col-sm-2 col-sm-offset-2">
                                    <input type="text" class="form-control" placeholder="Price" name="price">
                                </div>
                                <div class="col-sm-5">
                                    <textarea class="form-control" placeholder="Enter any notes..." rows="2" name="note"></textarea>
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-primary btn-sm">Submit</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Subscribe List
		</div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>VAT ID</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Sent At</th>
                    <th>Read</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($job->emailBusinesses as $key => $emailBusiness)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $emailBusiness->business->name }}</td>
                        <td>{{ $emailBusiness->business->vat_id }}</td>
                        <td>{{ $emailBusiness->business->email }}</td>
                        <td>{{ $emailBusiness->business->phone }}</td>
                        <td>{{ $emailBusiness->created_at }}</td>
                        <td>{{ $emailBusiness->is_read ? 'Yes' : 'No' }}</td>
                        <td>
                            @if (!$emailBusiness->is_read)
                            <button class="btn btn-default" data-id="{{ $emailBusiness->id }}" id="js-btn-resubscribe">Resend</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>  

@stop

@section('custom-scripts')
<script>
$(document).ready(function() {
    $("button#js-btn-resend").click(function() {
        $.ajax({
            url: "{{ URL::route('backend.email.resend') }}",
            dataType : "json",
            type : "POST",
            data : { id : $(this).attr('data-id') },
            success : function(data){
                bootbox.alert(data.msg);
            }
        });        
    });

    $("button#js-btn-resubscribe").click(function() {
        $.ajax({
            url: "{{ URL::route('backend.email.resubscribe') }}",
            dataType : "json",
            type : "POST",
            data : { id : $(this).attr('data-id') },
            success : function(data){
                bootbox.alert(data.msg);
            }
        });        
    });    
});

function onShowBid(obj) {
    $(obj).parents("tr").eq(0).next().show();
}
</script>
@stop

@stop
