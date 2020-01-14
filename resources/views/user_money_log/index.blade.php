@extends('layouts.backend')
@inject('UserMoneyLog', 'Models\User\UserMoneyLog')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <div class="col-xs-1" style="width:150px;">
                <label>用户名</label>
                <input type="text" name="username" class="form-control" placeholder="请输入..." value="{{ $search['username'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:170px;">
                <label>类型</label>
                <select name="type" class="form-control">
                    <option value="">请选择</option>
                @foreach ($UserMoneyLog::TYPE as $key => $val)
                    <option value="{{ $key }}" {{ isset($search['type']) && $search['type'] == $key ? 'selected':'' }}>{{ $val }}</option>
                @endforeach
                </select>
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
                    <th>{!! sort_title('id', '编号', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('uid', '用户名', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('type', '类型', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('video_keyword', '视频', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('money_before', '变动前点数', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('money_add', '变动点数', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('money_after', '变动后点数', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('description', '描述', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('created_at', '添加日期', $route, $order, $search) !!}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    @if (session('roleid') == 1 || in_array("$controller.show_username", $allow_url))
                    <td>{{ $row->user->username ?? '' }}</td>
                    @else
                    <td>{{ $row->user->username ? substr($row->user->username,0,3).'****'.substr($row->user->username,-3):'' }}</td>
                    @endif
                    <td>{{ $UserMoneyLog::TYPE[$row['type']] }}</td>
                    <td>{{ $row->video->name ?? '' }}</td>
                    <td>{{ $row['money_before'] }}</td>
                    <td>{{ $row['money_add'] }}</td>
                    <td>{{ $row['money_after'] }}</td>
                    <td>{{ $row['description'] }}</td>
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
