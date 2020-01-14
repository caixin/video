@extends('layouts.backend')
@inject('AdminActionLog', 'Models\Admin\AdminActionLog')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <div class="col-xs-1" style="width:150px;">
                <label>帐号</label>
                <input type="text" name="username" class="form-control" placeholder="请输入..." value="{{ $search['username'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:150px;">
                <label>路由</label>
                <input type="text" name="route" class="form-control" placeholder="请输入..." value="{{ $search['route'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:150px;">
                <label>IP</label>
                <input type="text" name="ip" class="form-control" placeholder="请输入..." value="{{ $search['ip'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:auto;">
                <label>添加日期</label>
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
    <div class="box-header">
        <label for="per_page">显示笔数:</label>
        <input type="test" id="per_page" style="text-align:center;" value="{{ $per_page }}" size="1">
        <h5 class="box-title" style="font-size: 14px;">
            <b>总计:</b> {{ $result->total() }} &nbsp;
        </h5>
        {!! $result->links() !!}
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="60">{!! sort_title('id', '编号', $route, $order, $search) !!}</th>
                    <th width="120">{!! sort_title('adminid', '帐号', $route, $order, $search) !!}</th>
                    <th width="150">{!! sort_title('route', '路由', $route, $order, $search) !!}</th>
                    <th width="100">{!! sort_title('ip', 'IP', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('message', '操作讯息', $route, $order, $search) !!}</th>
                    <th width="80">{!! sort_title('status', '狀態', $route, $order, $search) !!}</th>
                    <th width="100">{!! sort_title('created_at', '添加日期', $route, $order, $search) !!}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row->admin->username }}</td>
                    <td>{{ $row['route'] }}</td>
                    <td>{{ $row['ip'] }}</td>
                    <td style="word-break: break-all;">{{ $row['message'] }}</td>
                    <td>{{ $AdminActionLog::STATUS[$row['status']] }}</td>
                    <td>{{ $row['created_at'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
        {!! $result->links() !!}
    </div>
</div>
@endsection
