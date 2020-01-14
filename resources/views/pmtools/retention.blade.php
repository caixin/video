@extends('layouts.backend')
@inject('DailyRetention', 'Models\Pmtools\DailyRetention')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
    </div>
    <!-- /.box-header -->
    <div id="refresh" class="box-body table-responsive no-padding">
        <div style="color:red;">1) 今天往前算1,3,7,15,30天内有登入的不重复用户数</div>
        <div style="color:red;">2) 今日往前算31天以上未登入的不重复用户数</div>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>颜色</th>
                    <th>类型</th>
                    <th>人数</th>
                    <th>百分比</th>
                    <th>平均剩余点数</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $DailyRetention::TYPE_LIGHT[$row['type']] }}</td>
                    <td>{{ $DailyRetention::TYPE[$row['type']] }}</td>
                    <td>{{ $row['day_count'] }}</td>
                    <td>{{ $row['percent'] }}%</td>
                    <td>{{ $row['avg_money'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>
@endsection
