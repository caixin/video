@extends('layouts.backend')
@inject('DailyRetention', 'Models\Pmtools\DailyRetention')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <div class="col-xs-1" style="width:auto;">
                <label>创帐号时间</label>
                <div class="input-group">
                    <input type="text" id="created_at_from" name="created_at1" class="form-control datepicker" style="width:50%" placeholder="起始时间" value="{{ $search['created_at1'] ?? '' }}" autocomplete="off">
                    <input type="text" id="created_at_to" name="created_at2" class="form-control datepicker" style="width:50%" placeholder="结束时间" value="{{ $search['created_at2'] ?? '' }}" autocomplete="off">
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
	    <div style="color:red;">选择日创帐号的用户在1,3,7,30,60,90天后的保留率及流失率</div>
        <div class="box-header">
            <h5 class="box-title" style="font-size: 14px;"><b>用户总计:</b> {{ $total }}</h5>
        </div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>类型</th>
                    <th>人数</th>
                    <th>百分比</th>
                    <th>平均剩余点数</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $DailyRetention::ANALYSIS_TYPE[$row['type']] }}</td>
                    <td>{{ $row['count'] }}</td>
                    <td>{{ $row['percent'] }}%</td>
                    <td>{{ $row['avg_money'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="box-header">
            <h5 class="box-title" style="font-size: 14px;"><b>用户总计:</b> {{ $total }}</h5>
        </div>
    </div>
    <!-- /.box-body -->
</div>
@endsection
