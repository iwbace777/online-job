@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">User Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>User</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>Edit</span>
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
			<i class="fa fa-pencil-square-o"></i> Edit User
		</div>
	</div>
	<div class="portlet-body">
        <form class="form-horizontal" role="form" method="post" action="{{ URL::route('backend.user.store') }}" enctype="multipart/form-data">
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            @foreach ([
                'email' => 'Email',
                'password' => 'Password',
                'name'   => 'Name',
                'email2' => 'Email 2',
                'email3' => 'Email 3',
                'email4' => 'Email 4',
                'email5' => 'Email 5',
                'is_business'  => 'Business Type',                
                'vat_id'  => 'VAT ID',
                'contact'  => 'Contact',
                'zip_code'  => 'Zip Code',                
                'phone'  => 'Phone',
                'address' => 'Address',
                'city_id' => 'City',
                'photo'  => 'Photo',
                'description'  => 'Description',
                'count_connection'  => 'Bids',
            ] as $key => $value)
            <div class="form-group">
                <label class="col-sm-2 control-label">{{ Form::label($key, $value) }}</label>
                <div class="col-sm-10">
                    @if ($key === 'is_business')
                        <select class="form-control">
                            <option value=1 {{ $user->is_business ? 'selected' : '' }}>Business</option>
                            <option value=0 {{ $user->is_business ? '' : 'selected' }}>Individual</option>
                        </select>
                    @elseif ($key === 'city_id')
                        <select name="city_id" class="form-control">
                            <option class="option-city" value="">{{ trans('user.select_city') }}</option>
                            @foreach ($cities as $city)
                            <option class="option-city" {{ (!$user->district_id && $user->city_id == $city->id) ? 'selected' : '' }} value="{{ $city->id }}">{{ $city->name }}</option>
                                @foreach ($city->districts as $district)
                                <option class="option-district" {{ ($user->district_id && $user->city_id == $city->id && $user->district_id == $district->id) ? 'selected' : '' }} value="{{ $city->id.'-'.$district->id }}">&nbsp;-&nbsp;{{ $district->name }}</option>
                                @endforeach
                            @endforeach
                        </select>            
                    @elseif ($key === 'description')
                        {{ Form::textarea($key, $user->{$key}, ['class' => 'form-control']) }}
                    @elseif ($key === 'photo')
                    <div class="row">
                        <div class="col-sm-6">
                        {{ Form::file($key, ['class' => 'form-control']) }}
                        </div>
                        <div class="col-sm-6">
                            <img src="{{ HTTP_USER_PATH.$user->photo }}" style="width: 80px; height: 80px;">
                        </div>
                    </div>
                    @elseif ($key === 'email')
                        {{ Form::text($key, $user->{$key}, ['class' => 'form-control', 'readonly' => true]) }}                                                                
                    @else
                        {{ Form::text($key, $user->{$key}, ['class' => 'form-control']) }}
                    @endif
                </div>
            </div>
            @endforeach
            
            <div class="form-group" id="js-div-sub-category">
                <label class="col-sm-2 control-label">Category</label>
                <div class="col-sm-10">
			        <?php
			        $subCategories = [];
			        foreach ($user->subCategories as $item) {
                        $subCategories[] = $item->sub_category_id;
                    } 
			        ?>
			        
			        @foreach ($categories as $category)
			            <div class="col-md-4">
			                <p><b>{{ $category->name }}</b></p>
			                @foreach ($category->subCategories as $subCategory)
			                <p>
			                    <input type="checkbox" class="form-control" id="js-checkbox-sub-category" value="{{ $subCategory->id }}" 
			                        {{ in_array($subCategory->id, $subCategories) ? 'checked' : '' }}>&nbsp;{{ $subCategory->name }}
		                    </p>
			                @endforeach
			            </div>
			        @endforeach
                </div>
            </div>                
          
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10 text-center">
                    <button class="btn btn-success" onclick="return validate();">
                        <span class="glyphicon glyphicon-ok-circle"></span> Save
                    </button>
                    <a href="{{ URL::route('backend.user') }}" class="btn btn-primary">
                        <span class="glyphicon glyphicon-share-alt"></span> Back
                    </a>
                </div>
            </div>
          </form>
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> User Notes
		</div>
	</div>
    <div class="portlet-body ">
        <div class="row">
            <form method="post" action="{{ URL::route('backend.user.doNote') }}">
                <input type="hidden" name="user_id" value="{{ $user->id }}"/>
                <div class="form-group">
                    <label class="col-sm-2 control-label text-right">Note </label>
                    <div class="col-sm-8">
                        <textarea class="form-control" rows=4 name="note"></textarea>
                    </div>
                    <div class="col-sm-2">
                        <button class="btn blue btn-block">
                            <i class="fa fa-plus"></i>
                            Add
                        </button>
                    </div>
                </div>
            </form>        
        </div>
        <hr/>
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Note</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->notes as $key => $note)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $note->description }}</td>
                        <td>{{ $note->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                
    </div>
</div>

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Posted Jobs
		</div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Category</th>
                    <th>Avg Bid</th>
                    <th>Newsletter Sent</th>
                    <th>Read Count</th>
                    <th>Bid Count</th>
                    <th class="th-action">Detail</th>
                    <th class="th-action">Delete</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->jobs as $key => $job)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $job->name }}</td>
                        <td>{{ $job->status }}</td>
                        <td>{{ $job->count_view }}</td>
                        <td>{{ $job->category->name }}</td>
                        <td>{{ round($job->bids()->avg('price')) }}</td>
                        <td>{{ count($job->emailHistories) }}</td>
                        <td>
                            <?php $countRead = 0;?>
                            @foreach ($job->emailHistories as $item)
                                @if ($item->is_read)
                                    <?php $countRead++;?>
                                @endif
                            @endforeach
                            {{ $countRead }}
                        </td>
                        <td>{{ count($job->bids) }}</td>
                        <td>
                            <a href="{{ URL::route('backend.job.detail', $job->id) }}" class="btn btn-sm btn-info">
                                <span class="glyphicon glyphicon-edit"></span> Detail
                            </a>
                        </td>
                        <td>
                            <a href="{{ URL::route('backend.job.delete', $job->id) }}" class="btn btn-sm btn-danger" id="js-a-delete">
                                <span class="glyphicon glyphicon-trash"></span> Delete
                            </a>
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
			<i class="fa fa-navicon"></i> Add the Bids
		</div>
	</div>
    <div class="portlet-body ">
        <div class="row">
            <form method="post" action="{{ URL::route('backend.user.addConnection') }}">
                <input type="hidden" name="user_id" value="{{ $user->id }}"/>
                <div class="col-sm-3 text-right">
                    <p class="form-control-static"><b>Bid Count : </b></p>
                </div>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="count_connection" value="0"/>
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-primary">Add Bids &amp; Send Invoice</button>
                </div>
            </form>
        </div>
    </div>
</div>  

<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Purchase Bids History
		</div>
	</div>
    <div class="portlet-body ">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Bids Count</th>
                    <th>Price</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($user->transactions as $key => $item)
                    <tr>
                        <td>{{ ($key + 1) }}</td>
                        <td>{{ $item->package->name }}</td>
                        <td>{{ $item->package->count }}</td>
                        <td>{{ $item->package->price }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @endforeach
                @foreach ($user->subscribes as $key => $item)
                    <tr>
                        <td>{{ count($user->transactions) + ($key + 1) }}</td>
                        <td>{{ $item->plan->name }}</td>
                        <td>{{ $item->plan->count }}</td>
                        <td>{{ $item->plan->price }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @endforeach
                @foreach ($user->buys as $key => $item)
                    <tr>
                        <td>{{ count($user->buys) + count($user->transactions) + ($key + 1) }}</td>
                        <td>{{ "Bank Transfer" }}</td>
                        <td>{{ $item->count }}</td>
                        <td>{{ $item->count * CONNECTION_PRICE }}</td>
                        <td>{{ $item->created_at }}</td>
                    </tr>
                @endforeach                  
            </tbody>
        </table>
    </div>
</div>  
@stop

@section('custom-scripts')
<script>
function validate() {
    var objList = $("input#js-checkbox-sub-category:checked");
    for (var i = 0; i < objList.length; i++) {
        $("div#js-div-sub-category").append($("<input type='hidden' name='sub_category[]' value=" + objList.eq(i).val() + ">"));
    }
    return true;
}
</script>
@stop

@stop
