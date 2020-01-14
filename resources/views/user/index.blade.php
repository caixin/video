@extends('layouts.backend')
@inject('User', 'Models\User\User')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <input type="hidden" name="sidebar" value="{{ $search['sidebar'] ?? '' }}">
            <input type="hidden" name="referrer" value="{{ $search['referrer'] ?? '' }}">
            <div class="col-xs-1" style="width:150px;">
                <label>用户名称</label>
                <input type="text" name="username" class="form-control" placeholder="请输入..." value="{{ $search['username'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:200px;">
                <label>推荐码</label>
                <input type="text" name="referrer_code" class="form-control" placeholder="请输入..." value="{{ $search['referrer_code'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:200px;">
                <label>备注</label>
                <input type="text" name="remark" class="form-control" placeholder="请输入..." value="{{ $search['remark'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:170px;">
                <label>状态</label>
                <select name="status" class="form-control">
                    <option value="">请选择</option>
                @foreach ($User::STATUS as $key => $val)
                    <option value="{{ $key }}" {{ isset($search['status']) && $search['status'] == $key ? 'selected':'' }}>{{ $val }}</option>
                @endforeach
                </select>
            </div>
            <div class="col-xs-1" style="width:330px">
                <label>活跃时间</label>
                <div class="input-group">
                    <input type="text" id="active_time_from" name="active_time1" class="form-control secpicker" style="width:50%" placeholder="起始时间" value="{{ $search['active_time1'] ?? '' }}" autocomplete="off">
                    <input type="text" id="active_time_to" name="active_time2" class="form-control secpicker" style="width:50%" placeholder="结束时间" value="{{ $search['active_time2'] ?? '' }}" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-1" style="width:250px">
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
        @if (session('roleid') == 1 || in_array("$controller.export", $allow_url))
        <div class="col-xs-1" style="width:auto;float:right;">
            <label>&nbsp;</label>
            <a href="{{ route('user.export')."?$params_uri" }}" class="form-control btn btn-primary">汇出</a>
        </div>
        @endif
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
                    <th>{!! sort_title('username', '用户名称', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('money', '视频点数', $route, $order, $search) !!}</th>
                    <th>分享数</th>
                    <th>推荐码</th>
                    <th>{!! sort_title('referrer', '推荐人', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('login_ip', '登录IP', $route, $order, $search) !!}</th>
                    <th width="100">{!! sort_title('login_time', '登录时间', $route, $order, $search) !!}</th>
                    <th width="100">{!! sort_title('active_time', '活跃时间', $route, $order, $search) !!}</th>
                    <th>未登入</th>
                    <th>{!! sort_title('status', '状态', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('remark', '备注', $route, $order, $search) !!}</th>
                    <th width="100">{!! sort_title('free_time', '免费到期', $route, $order, $search) !!}</th>
                    <th width="100">{!! sort_title('created_at', '添加日期', $route, $order, $search) !!}</th>
                    <th width="180">
                    @if (session('roleid') == 1 || in_array("$controller.create", $allow_url))
                        <button type="button" class="btn btn-primary" onclick="add()">添加</button>
                    @endif
                    </th>
                </tr>
            </thead>
            <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                @if (session('roleid') == 1 || in_array("$controller.show_username", $allow_url))
                    <td>{{ $row['username'] }}</td>
                @else
                    <td>{{ substr($row['username'],0,3).'****'.substr($row['username'],-3) }}</td>
                @endif
                    <td>{{ $row['money'] }}</td>
                    <td><a href="javascript:" onclick="shares({{ $row['id'] }})">{{ $row['shares'] }}</a></td>
                    <td>{{ $row['referrer_code'] }}</td>
                @if (session('roleid') == 1 || in_array("$controller.show_username", $allow_url))
                    <td>{{ $row->referrer_user->username ?? '-' }}</td>
                @else
                    <td>{{ isset($row->referrer_user->username) ? substr($row['username'],0,3).'****'.substr($row['username'],-3) : '-' }}</td>
                @endif
                    <td>{{ $row['login_ip'] }}</td>
                    <td>{{ $row['login_time'] }}</td>
                    <td>{{ $row['active_time'] }}</td>
                    <td>{{ $row['no_login_day'] }}</td>
                    <td>{{ $User::STATUS[$row['status']] }}</td>
                    <td>{{ $row['remark'] }}</td>
                    <td>{{ $row['free_time'] }}</td>
                    <td>{{ $row['created_at'] }}</td>
                    <td>
                    @if (session('roleid') == 1 || in_array("$controller.edit", $allow_url))
                        <button type="button" class="btn btn-primary" onclick="edit({{ $row['id'] }})">编辑</button>
                    @endif
                    @if (session('roleid') == 1 || in_array("$controller.money", $allow_url))
                        <button type="button" class="btn btn-primary" onclick="money({{ $row['id'] }})">人工加减点</button>
                    @endif
                    <br />
                    @if (session('roleid') == 1 || in_array("$controller.free", $allow_url))
                        <button type="button" class="btn btn-primary" onclick="free_setting({{ $row['id'] }})" style="margin-top: 3px;">免费浏览设定</button>
                    @endif
                    </td>
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
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //添加
    function add() {
        layer.open({
            type: 2,
            shadeClose: false,
            title: false,
            closeBtn: [0, true],
            shade: [0.8, '#000'],
            border: [1],
            offset: ['20px', ''],
            area: ['50%', '90%'],
            content: '{{ url("$controller/create") }}'
        });
    }
    //编辑
    function edit(id) {
        layer.open({
            type: 2,
            shadeClose: false,
            title: false,
            closeBtn: [0, true],
            shade: [0.8, '#000'],
            border: [1],
            offset: ['20px', ''],
            area: ['50%', '90%'],
            content: '{{ url($controller) }}/' + id + '/edit'
        });
    }
    //编辑
    function money(id) {
        layer.open({
            type: 2,
            shadeClose: false,
            title: false,
            closeBtn: [0, true],
            shade: [0.8, '#000'],
            border: [1],
            offset: ['20px', ''],
            area: ['50%', '90%'],
            content: '{{ url($controller) }}/' + id + '/money'
        });
    }
    //分享數
    function shares(id) {
        layer.open({
            type: 2,
            shadeClose: false,
            title: false,
            closeBtn: [0, true],
            shade: [0.8, '#000'],
            border: [1],
            offset: ['20px', ''],
            area: ['90%', '90%'],
            content: '{{ route("$controller.index",["sidebar"=>0]) }}&referrer='+id
        });
    }
    //无限浏览设定
    function free_setting(id) {
        layer.open({
            type: 2,
            shadeClose: false,
            title: false,
            closeBtn: [0, true],
            shade: [0.8, '#000'],
            border: [1],
            offset: ['20px', ''],
            area: ['50%', '90%'],
            content: '{{ url($controller) }}/' + id + '/free'
        });
    }
</script>
@endsection
