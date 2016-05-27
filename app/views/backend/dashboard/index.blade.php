@extends('backend.layout')

    @section('custom-styles')
    {{ HTML::style('/assets/metronic/global/plugins/bootstrap-datepicker/css/datepicker3.css') }}
    @stop

    @section('breadcrumb')
	<div class="row">
		<div class="col-md-12">
			<h3 class="page-title">Dashboard</h3>
			<ul class="page-breadcrumb breadcrumb">
				<li>
					<i class="fa fa-home"></i>
					<span>Dashboard</span>
				</li>
			</ul>
		</div>
	</div>
    @stop
    
    @section('content')
    <div class="row">
        <div class="col-sm-12">
            <form class="form-horizontal" method="post" action="{{ URL::route('backend.dashboard') }}">
                <div class="form-group">
                    <label class="col-sm-2 control-label">Search Date</label>
                    <div class="col-sm-2">
                        <input type="text" class="form-control text-center readonly" name="startDate" id="startDate" placeholder="Start Date" readonly value="{{ $startDate }}">
                    </div>
                    <div class="col-sm-2">
                        <input type="text" class="form-control text-center readonly" name="endDate" id="endDate" placeholder="End Date" readonly value="{{ $endDate }}">
                    </div>
                    <div class="col-sm-2">
                        <button class="btn btn-primary" onclick="return onValidate();">Search</button>
                    </div>
                    <div class="col-sm-1">
                        &nbsp;
                    </div>                                
                    <div class="col-sm-3">
                        <select class="form-control" id="period">
                            <option value="0">Select Period</option>
                            <option value="3">Last 3 days</option>
                            <option value="7">Last 1 week</option>
                            <option value="30">Last 1 month</option>
                            <option value="60">Last 2 months</option>
                            <option value="90">Last 3 months</option>
                            <option value="180">Last 6 months</option>
                            <option value="365">Last 1 year</option>
                        </select>
                    </div>
                </div>                        
            </form>            
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>
    
    <div class="row">
        <div class="col-sm-3">
            <div class="dashboard-stat blue">
				<div class="visual">
					<i class="icon-diamond"></i>
				</div>
				<div class="details">
					<div class="number">
						 {{ $avgUserPostProject }}
					</div>
					<div class="desc">
						 Average amount of projects
						 <br/> 
						 per user
					</div>
				</div>
			</div>
        </div>
        
        <div class="col-md-3">
            <div class="dashboard-stat blue">
				<div class="visual">
					<i class="fa fa-comments"></i>
				</div>
				<div class="details">
					<div class="number">
						 {{ $avgUserBidProject }}
					</div>
					<div class="desc">
						 Average amount of bids
						 <br/>
						 per user
					</div>
				</div>
			</div>
        </div>    
        
        <div class="col-md-3">
            <div class="dashboard-stat blue">
				<div class="visual">
					<i class="fa fa-globe"></i>
				</div>
				<div class="details">
					<div class="number">
						 {{ $avgUserBidRate }}&nbsp;%
					</div>
					<div class="desc">
						 Bidding rate
						 <br/> 
						 per user
					</div>
				</div>
			</div>
        </div>        
        
        <div class="col-md-3">
            <div class="dashboard-stat blue">
				<div class="visual">
					<i class="fa fa-bar-chart-o"></i>
				</div>
				<div class="details">
					<div class="number">
						 {{ $totalRevenue }}&nbsp;&euro;
					</div>
					<div class="desc">
						 Total Revenue
					</div>
				</div>
			</div>
        </div>        
    </div>
            
    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>
    
    <div class="row">
        <div id="container1" class="chart-container col-sm-12"></div>
        <div class="col-sm-12"><hr/></div>
        <div id="container2" class="chart-container col-sm-12"></div>
        <div class="col-sm-12"><hr/></div>
        <div id="container3" class="chart-container col-sm-12"></div>
        <div class="col-sm-12"><hr/></div>
        <div id="container4" class="chart-container col-sm-12"></div>
        <div class="col-sm-12"><hr/></div>
        <div id="container5" class="chart-container col-sm-12"></div>
        <div class="col-sm-12"><hr/></div>
        <div id="container6" class="chart-container col-sm-12"></div>    
    </div>
    @stop
    

    @section('custom-scripts')
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}
    {{ HTML::script('/assets/metronic/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ HTML::script('/assets/highcharts/highcharts.js') }}
    {{ HTML::script('/assets/highcharts/modules/exporting.js') }}
    
<script>
    var data1 = [], data2 = [], data3 = [], data4 = [], data5 = [], data6 = [];
    <?php 
        $i = 0; 
        foreach ($averageUserPostProject as $item) {?>
        var temp = [Date.UTC(<?php echo $item->y;?>, <?php echo $item->m;?>, <?php echo $item->d;?>), <?php echo $item->avg_cnt?>]
        data1[<?php echo $i++;?>] = temp;
    <?php } ?>

    <?php 
        $i = 0; 
        foreach ($averageUserBidProject as $item) {?>
        var temp = [Date.UTC(<?php echo $item->y;?>, <?php echo $item->m;?>, <?php echo $item->d;?>), <?php echo $item->avg_cnt?>]
        data2[<?php echo $i++;?>] = temp;
    <?php } ?>

    <?php 
        $i = 0; 
        foreach ($revenue as $item) {?>
        var temp = [Date.UTC(<?php echo $item->y;?>, <?php echo $item->m;?>, <?php echo $item->d;?>), <?php echo $item->amount?>]
        data3[<?php echo $i++;?>] = temp;
    <?php } ?>

    <?php 
        $i = 0; 
        foreach ($countUserRegister as $item) {?>
        var temp = [Date.UTC(<?php echo $item->y;?>, <?php echo $item->m;?>, <?php echo $item->d;?>), <?php echo $item->amount?>]
        data4[<?php echo $i++;?>] = temp;
    <?php } ?>

    <?php 
        $i = 0; 
        foreach ($countBid as $item) {?>
        var temp = [Date.UTC(<?php echo $item->y;?>, <?php echo $item->m;?>, <?php echo $item->d;?>), <?php echo $item->amount?>]
        data5[<?php echo $i++;?>] = temp;
    <?php } ?>

    <?php 
        $i = 0; 
        foreach ($countPost as $item) {?>
        var temp = [Date.UTC(<?php echo $item->y;?>, <?php echo $item->m;?>, <?php echo $item->d;?>), <?php echo $item->amount?>]
        data6[<?php echo $i++;?>] = temp;
    <?php } ?>
    
    function getFormattedDate(date) {
        var year = date.getFullYear();
        var month = (1 + date.getMonth()).toString();
        month = month.length > 1 ? month : '0' + month;
        var day = date.getDate().toString();
        day = day.length > 1 ? day : '0' + day;
        return year + '-' + month + '-' + day;
    }

    function onValidate() {
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val();

        if (startDate == '' || endDate == '' || endDate < startDate) {
            bootbox.alert('Please select Start Date & End Date Correctly.');
            return false;
        }
        return true;
    }    
    
    $(document).ready(function() {
        $('#startDate, #endDate').datepicker({format: 'yyyy-mm-dd'});
        var startDate = $("#startDate").val();
        var endDate = $("#endDate").val(); 
        if (startDate == '' || endDate == '') {
            $("#period").val(7);
            $("#period").change();
        }
        $("#period").change(function() {
            var type = $(this).val();
            var startDate = new Date();
            var endDate = new Date();
            if (type == 0) {
                $("#startDate").val("");
                $("#endDate").val("");
            } else if (type == 3) {
                startDate.setDate(startDate.getDate() - 3 );
            } else if (type == 7) {
                startDate.setDate(startDate.getDate() - 7 );
            } else if (type == 30) {
                startDate.setMonth(startDate.getMonth() - 1 );
            } else if (type == 60) {
                startDate.setMonth(startDate.getMonth() - 2 );
            } else if (type == 90) {
                startDate.setMonth(startDate.getMonth() - 3 );
            } else if (type == 180) {
                startDate.setMonth(startDate.getMonth() - 6 );
            } else if (type == 365) {
                startDate.setYear(startDate.getFullYear() - 1 );
            }
            $("#startDate").val(getFormattedDate(startDate));
            $("#endDate").val(getFormattedDate(endDate));        
        });
    });

    $(function () {
        $('#container1').highcharts({
            chart: { type: 'spline' },
            title: { text: 'Average amount of projects post' },
            xAxis: { type: 'datetime', dateTimeLabelFormats: { month: '%e. %b', year: '%b' }, title: { text: 'Date' } },
            yAxis: { title: {text: ' '}, min: 0 },
            tooltip: { headerFormat: '<b>{series.name}</b><br>', pointFormat: '{point.x:%e. %b}: {point.y:.2f}' },
            series: [{name: 'Post Average', data: data1}]
        });

        $('#container2').highcharts({
            chart: { type: 'spline' },
            title: { text: 'Average amount of projects bid' },
            xAxis: { type: 'datetime', dateTimeLabelFormats: { month: '%e. %b', year: '%b' }, title: { text: 'Date' } },
            yAxis: { title: {text: ' '}, min: 0 },
            tooltip: { headerFormat: '<b>{series.name}</b><br>', pointFormat: '{point.x:%e. %b}: {point.y:.2f}' },
            series: [{name: 'Bid Average', data: data2}]
        });

        $('#container3').highcharts({
            chart: { type: 'spline' },
            title: { text: 'Revenue' },
            xAxis: { type: 'datetime', dateTimeLabelFormats: { month: '%e. %b', year: '%b' }, title: { text: 'Date' } },
            yAxis: { title: {text: ' '}, min: 0 },
            tooltip: { headerFormat: '<b>{series.name}</b><br>', pointFormat: '{point.x:%e. %b}: {point.y:.2f}Euro' },
            series: [{name: 'Revenue', data: data3}]
        });

        $('#container4').highcharts({
            chart: { type: 'spline' },
            title: { text: 'How many user registered?' },
            xAxis: { type: 'datetime', dateTimeLabelFormats: { month: '%e. %b', year: '%b' }, title: { text: 'Date' } },
            yAxis: { title: {text: ' '}, min: 0 },
            tooltip: { headerFormat: '<b>{series.name}</b><br>', pointFormat: '{point.x:%e. %b}: {point.y}' },
            series: [{name: 'User Register Count', data: data4}]
        });  

        $('#container5').highcharts({
            chart: { type: 'spline' },
            title: { text: 'How many bids per day?' },
            xAxis: { type: 'datetime', dateTimeLabelFormats: { month: '%e. %b', year: '%b' }, title: { text: 'Date' } },
            yAxis: { title: {text: ' '}, min: 0 },
            tooltip: { headerFormat: '<b>{series.name}</b><br>', pointFormat: '{point.x:%e. %b}: {point.y}' },
            series: [{name: 'Job Bid Count', data: data5}]
        });

        $('#container6').highcharts({
            chart: { type: 'spline' },
            title: { text: 'How many posts per day?' },
            xAxis: { type: 'datetime', dateTimeLabelFormats: { month: '%e. %b', year: '%b' }, title: { text: 'Date' } },
            yAxis: { title: {text: ' '}, min: 0 },
            tooltip: { headerFormat: '<b>{series.name}</b><br>', pointFormat: '{point.x:%e. %b}: {point.y}' },
            series: [{name: 'Job Post Count', data: data6}]
        });              
    });
</script>    
    @stop
@stop
