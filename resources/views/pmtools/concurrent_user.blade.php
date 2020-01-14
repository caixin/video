@extends('layouts.backend')
@inject('ConcurrentUser', 'Models\Pmtools\ConcurrentUser')

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
                    <input type="text" id="minute_time_from" name="minute_time1" class="form-control secpicker" style="width:50%" placeholder="起始时间" value="{{ $search['minute_time1'] ?? '' }}" autocomplete="off">
                    <input type="text" id="minute_time_to" name="minute_time2" class="form-control secpicker" style="width:50%" placeholder="结束时间" value="{{ $search['minute_time2'] ?? '' }}" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-1" style="width:170px;">
                <label>间隔</label>
                <select name="per" class="form-control">
                @foreach ($ConcurrentUser::PER as $key => $val)
                    <option value="{{ $key }}" {{ isset($search['per']) && $search['per'] == $key ? 'selected':'' }}>{{ $val }}</option>
                @endforeach
                </select>
            </div>
            <div class="col-xs-1">
                <label>&nbsp;</label>
                <button type="submit" class="form-control btn btn-primary">查询</button>
            </div>
        </form>
    </div>
    <!-- /.box-header -->
    <div id="refresh" class="box-body table-responsive no-padding">
	    <div id="chart"></div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>时间</th>
                    <th>人数</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($table as $key => $row)
                <tr>
                    <td>{{ $row['time'] }}</td>
                    <td>{{ $row['count'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <script type="text/javascript">
        $(function () {
            $('#chart').highcharts({
                chart: {
                    type: 'column'
                },
                title: {
                    text: '{{ $title }}'
                },
                credits: {
                    enabled : false
                },
                xAxis: {
                    type: 'category',
                    title: {
                        text: '时间'
                    },
                    labels: {
                        rotation: -45,
                        style: {
                            fontSize: '13px',
                            fontFamily: 'Verdana, sans-serif',
                            fontWeight: 'bold'
                        }
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '人数'
                    }
                },
                legend: {
                    enabled: false
                },
                tooltip: {
                    pointFormat: '人数: <b>{point.y}</b>'
                },
                plotOptions: {
                    column: {
                        depth: 25
                    }
                },
                series: [{
                    name: '人数',
                    colorByPoint: true,
                    data: {!! json_encode($chart) !!},
                    dataLabels: {
                        enabled: 'true',
                        color: '#000000',
                        style: {
                            fontSize: '14px',
                            fontFamily: 'Verdana, sans-serif',
                            textShadow: '0 0 2px black'
                        }
                    }
                }]
            });
        });
        </script>
    </div>
    <!-- /.box-body -->
</div>
<script type="text/javascript">
if ({{ $count }} > 200) {
    alert('笔数过多无法显示，请调整时间区间!');
}
setInterval(function(){
	$.get('{{ route("ccu.index") }}?{!! $params_uri !!}',{},function(data){
		$('#refresh').html($(data).find('#refresh'));
	});
},60000);
</script>
@endsection
