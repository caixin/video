@extends('layouts.backend')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <div class="col-xs-1" style="width:150px;">
                <label>网域</label>
                <input type="text" name="domain" class="form-control" placeholder="请输入..." value="{{ $search['domain'] ?? '' }}">
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
                    <th>{!! sort_title('domain', '网域', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('title', '标题', $route, $order, $search) !!}</th>
                    <th width="30%">{!! sort_title('keyword', '关键字', $route, $order, $search) !!}</th>
                    <th width="30%">{!! sort_title('description', '描述', $route, $order, $search) !!}</th>
                    <th width="100">{!! sort_title('created_at', '添加日期', $route, $order, $search) !!}</th>
                    <th width="220">
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
                    <td>{{ $row['domain'] }}</td>
                    <td>{{ $row['title'] }}</td>
                    <td>{{ $row['keyword'] }}</td>
                    <td>{{ $row['description'] }}</td>
                    <td>{{ $row['created_at'] }}</td>
                    <td>
                    @if (session('roleid') == 1 || in_array("$controller.create", $allow_url))
                        <button type="button" class="btn btn-primary" onclick="add({{ $row['id'] }})">复制新增</button>
                    @endif
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
    //添加
    function add(id) {
        layer.open({
            type: 2,
            shadeClose: false,
            title: false,
            closeBtn: [0, true],
            shade: [0.8, '#000'],
            border: [1],
            offset: ['20px', ''],
            area: ['50%', '90%'],
            content: '{{ url("$controller/create") }}?id=' + id
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
</script>
@endsection
