@extends('layouts.backend')
@inject('Admin', 'Models\Admin\Admin')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <div class="col-xs-1" style="width:150px;">
                <label>用户名称</label>
                <input type="text" name="username" class="form-control" placeholder="请输入..." value="{{ $search['username'] ?? '' }}">
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
                    <th>{!! sort_title('username', '用户名称', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('roleid', '角色群组', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('created_at', '添加日期', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('login_time', '登录日期', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('status', '状态', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('remark', '备注', $route, $order, $search) !!}</th>
                    <th colspan="2">
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
                    <td>{{ $row['username'] }}</td>
                    <td>{{ $role[$row['roleid']] }}</td>
                    <td>{{ $row['created_at'] }}</td>
                    <td>{{ $row['login_time'] }}</td>
                    <td>
                    @if (session('roleid') == 1 || in_array("$controller.save", $allow_url))
                        <button type="button" id="status1_{{ $row['id'] }}" class="btn {{ $row['status'] == 1 ? 'btn-info' : 'btn-default' }}" onclick="status_row({{ $row['id'] }},1)">{{ $Admin::STATUS[1] }}</button>
                        <button type="button" id="status0_{{ $row['id'] }}" class="btn {{ $row['status'] == 0 ? '' : 'btn-default' }}" onclick="status_row({{ $row['id'] }},0)">{{ $Admin::STATUS[0] }}</button>
                    @else
                        {{ $Admin::STATUS[$row['status']] }}
                    @endif
                    </td>
                    <td>{{ $row['remark'] }}</td>
                    <td>
                    @if (session('roleid') == 1 || in_array("$controller.edit", $allow_url))
                        <button type="button" class="btn btn-primary" onclick="edit({{ $row['id'] }})">编辑</button>
                    @endif
                    @if (session('roleid') == 1 || in_array("$controller.delete", $allow_url))
                        <button type="button" class="btn btn-primary" onclick="delete_row({{ $row['id'] }})">删除</button>
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
    //删除
    function delete_row(id) {
        if (confirm('您确定要删除吗?'))
            $.post('{{ url("$controller/destroy") }}', {
                '_method': 'delete',
                'id': id
            }, function(data) {
                if (data == 'done') {
                    location.reload();
                } else {
                    alert('操作失败!');
                }
            });
    }
    //关闭
    function status_row(id, status) {
        if (status == 1) {
            $('#status1_' + id).removeClass('btn-default').addClass('btn-info');
            $('#status0_' + id).addClass('btn-default');
        } else {
            $('#status1_' + id).removeClass('btn-info').addClass('btn-default');
            $('#status0_' + id).removeClass('btn-default');
        }
        $.post('{{ url($controller) }}/' + id + '/save', {
            'status': status
        });
    }
</script>
@endsection
