@extends('backend.layout')

@section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Setting Management</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Setting</span>
					<i class="fa fa-angle-right"></i>
				</li>
				<li>
					<span>List</span>
				</li>
			</ul>
			
		</div>
	</div>    
@stop

@section('content')
<?php if (isset($alert)) { ?>
<div class="alert alert-<?php echo $alert['type'];?> alert-dismissibl fade in">
    <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
    </button>
    <p>
        <?php echo $alert['msg'];?>
    </p>
</div>
<?php } ?>
                    
<div class="portlet box blue">
    <div class="portlet-title">
		<div class="caption">
			<i class="fa fa-navicon"></i> Setting List
		</div>
	</div>
    <div class="portlet-body">
        <div class="form-horizontal">
            @foreach ($settings as $setting)
                <div class="form-group">
                    <label class="col-sm-5 control-label"> {{ $setting->name }}</label>
                    <div class="col-sm-5">
                        @if ($setting->code == 'CD02')
                        <select class="form-control">
                            <option value="EUR">EUR</option>
                            <option value="USD">USD</option>
                            <option value="GBP">GBP</option>
                        </select>
                        @elseif ($setting->code == 'CD03' || $setting->code == 'CD04' || $setting->code == 'CD11')
                            <select class="form-control">
                                <option value="YES" {{ $setting->value == 'YES' ? 'selected' : '' }}>YES</option>
                                <option value="NO" {{ $setting->value == 'NO' ? 'selected' : '' }}>NO</option>
                            </select>                                
                        @else
                        <input type="text" class="form-control" name="<?php echo $setting->code;?>" value="<?php echo $setting->value;?>">
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="form-group">
                <label class="col-sm-5 control-label"></label>
                <div class="col-sm-5">
                    <button class="btn btn-primary">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>             
        </div>
    </div>
</div>    
@stop

@stop
