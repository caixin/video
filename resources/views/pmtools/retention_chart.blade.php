@extends('layouts.backend')
@inject('DailyRetention', 'Models\Pmtools\DailyRetention')

@section('content')
<script type="text/javascript" src="{{ asset('backend/plugins/highcharts.js') }}"></script>
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <div class="col-xs-1" style="width:auto;">
                <label>时间</label>
                <div class="input-group">
                    <input type="text" id="date_from" name="date1" class="form-control datepicker" style="width:50%" placeholder="起始时间" value="{{ $search['date1'] ?? '' }}" autocomplete="off">
                    <input type="text" id="date_to" name="date2" class="form-control datepicker" style="width:50%" placeholder="结束时间" value="{{ $search['date2'] ?? '' }}" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-1">
                <label>&nbsp;</label>
                <button type="submit" class="form-control btn btn-primary">查询</button>
            </div>
        </form>
    </div>
    <!-- /.box-header -->
    <div id="refresh" class="box-body table-responsive no-padding">
	    <div style="color:red;">选择期间每一天的登入状况(保留率延伸表格)</div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>颜色</th>
                    <th>类型</th>
                @for ($i=strtotime($search['date1']); $i<=strtotime($search['date2']); $i+=86400)
                    <th><?=date('Y-m-d', $i)?></th>
                @endfor
                </tr>
            </thead>
            <tbody>
            @foreach ($table as $type => $row)
                <tr>
                <td>{{ $DailyRetention::TYPE_LIGHT[$type] }}</td>
                <td>{{ $DailyRetention::TYPE[$type] }}</td>
                @for ($i=strtotime($search['date1']); $i<=strtotime($search['date2']); $i+=86400)
                    <th><?=$row[$i]?></th>
                @endfor
                </tr>
            @endforeach
            </tbody>
        </table>
	    <div id="chart" style="width:99%;"></div>
    </div>
    <!-- /.box-body -->
</div>
<script>
$(function () {
    $('#chart').highcharts({
		chart: {
            type: 'line',
			height: 600
        },
        title: {
            text: '{{ $title }}'
        },
		credits: {
			enabled : false
		},
        xAxis: {
			categories: {!! json_encode($date) !!},
			labels: {
                rotation: -45,
                style: {
                    fontSize: '13px',
                    fontFamily: 'Verdana, sans-serif'
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: '人数'
            },
			plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
		legend: {
			shadow: true
		},
        tooltip: {
            pointFormat: '人数: <b>{point.y}</b>'
        },
		plotOptions: {
            column: {
                depth: 25
            }
        },
        series: {!! json_encode($chart) !!}
    });
});
</script>
@endsection
