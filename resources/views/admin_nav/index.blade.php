@extends('layouts.backend')
@inject('AdminNav', 'Models\Admin\AdminNav')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <input type="hidden" name="sidebar" value="{{ $search['sidebar'] ?? '' }}">
            <div class="col-xs-1" style="width:150px;">
                <label>导航名称</label>
                <input type="text" name="name" class="form-control" placeholder="请输入..." value="{{ $search['name'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:150px;">
                <label>主路由</label>
                <input type="text" name="route" class="form-control" placeholder="请输入..." value="{{ $search['route'] ?? '' }}">
            </div>
            <div class="col-xs-1">
                <label>&nbsp;</label>
                <button type="submit" class="form-control btn btn-primary">查询</button>
            </div>
        </form>
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>编号</th>
                    <th>导航名称</th>
                    <th>PATH</th>
                    <th>主路由</th>
                    <th>次路由1</th>
                    <th>次路由2</th>
                    <th>状态</th>
                    <th>{!! sort_title('sort', '排序', $route, $order, $search) !!}</th>
                    <th>添加日期</th>
                    <th colspan="2">
                    @if (session('roleid') == 1 || in_array("$controller.create", $allow_url))
                        <button type="button" class="btn btn-primary" onclick="add(0)">添加</button>
                    @endif
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($result as $row)
                    <tr>
                        <td>{{ $row['id'] }}</td>
                        <td style="color:{{ $AdminNav::PREFIX_COLOR[$row['prefix']] }}">{{ $row['prefix'] . $row['name'] }}</td>
                        <td>{{ $row['path'] }}</td>
                        <td>{{ $row['route'] }}</td>
                        <td>{{ $row['route1'] }}</td>
                        <td>{{ $row['route2'] }}</td>
                        <td>
                        @if (session('roleid') == 1 || in_array("$controller.save", $allow_url))
                            <button type="button" id="status1_{{ $row['id'] }}" class="btn {{ $row['status'] == 1 ? 'btn-info' : 'btn-default' }}" onclick="status_row({{ $row['id'] }},1)">{{ $AdminNav::STATUS[1] }}</button>
                            <button type="button" id="status0_{{ $row['id'] }}" class="btn {{ $row['status'] == 0 ? '' : 'btn-default' }}" onclick="status_row({{ $row['id'] }},0)">{{ $AdminNav::STATUS[0] }}</button>
                        @else
                            {{ $AdminNav::STATUS[$row['status']] }}
                        @endif
                        </td>
                        <td>{{ $row['sort'] }}</td>
                        <td>{{ $row['created_at'] }}</td>
                        <td>
                            @if (session('roleid') == 1 || in_array("$controller.create", $allow_url))
                                @if ($row['prefix'] != '∟ ∟ ')
                                    <button type="button" class="btn btn-primary" onclick="add({{ $row['id'] }})">添加子导航</button>
                                @endif
                            @endif
                        </td>
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
</div>
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //添加
    function add(pid) {
        layer.open({
            type: 2,
            shadeClose: false,
            title: false,
            closeBtn: [0, true],
            shade: [0.8, '#000'],
            border: [1],
            offset: ['20px', ''],
            area: ['50%', '90%'],
            content: '{{ url("$controller/create") }}?pid=' + pid
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
            $.post('{{ url("$controller/delete") }}', {
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
