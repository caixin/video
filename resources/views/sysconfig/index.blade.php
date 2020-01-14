@extends('layouts.backend')
@inject('Sysconfig', 'Models\System\sysconfig')

@section('content')
{!! lists_message() !!}
<div class="box">
    <!-- Custom Tabs -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            @foreach ($result as $key => $row)
                <li class="{{ $groupid == $key ? 'active':'' }}"><a href="#tab_{{ $key }}" data-toggle="tab">{{ $Sysconfig::GROUPID[$key] }}</a></li>
            @endforeach
                <li>
                @if (session('roleid') == 1 || in_array("$controller.create", $allow_url))
                    <button type="button" class="btn btn-primary" onclick="add()">添加</button>
                @endif
                </li>
        </ul>
        <div class="tab-content">
        @foreach ($result as $key => $data)
            <div class="tab-pane {{ $groupid == $key ? 'active' : '' }}" id="tab_{{ $key }}">
                <div class="box box-success">
                    <div class="box-body table-responsive no-padding">
                        <form method="post" role="form" action="{{ route("$controller.update",['sysconfig'=>$key]) }}">
                            @method('PUT')
                            @csrf
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="120">参数名称</th>
                                        <th width="200">参数说明</th>
                                        <th>参数值</th>
                                        <th width="100">排序</th>
                                        <th width="100">操作</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach ($data as $row)
                                    <tr>
                                        <td>{{ $row['skey'] }}</td>
                                        <td>{{ $row['info'] }}</td>
                                        @if (session('roleid') == 1 || in_array("$controller.update", $allow_url))
                                            <td>
                                            @if ($row['type'] == 4)
                                                <label><input type="radio" name="skey[{{ $row['id'] }}]" value="Y" {{ $row['svalue'] == 'Y' ? 'checked' : '' }}> 是</label>
                                                <label><input type="radio" name="skey[{{ $row['id'] }}]" value="N" {{ $row['svalue'] == 'N' ? 'checked' : '' }}> 否</label>
                                            @elseif ($row['type'] == 3)
                                                <textarea name="skey[{{ $row['id'] }}]" class="form-control" rows="5">{{ $row['svalue'] }}</textarea>
                                            @else
                                                <input type="text" name="skey[{{ $row['id'] }}]" class="form-control" value="{{ $row['svalue'] }}">
                                            @endif
                                            </td>
                                            <td><input type="number" name="sort[{{ $row['id'] }}]" class="form-control" value="{{ $row['sort'] }}"></td>
                                        @else
                                            <td>{{ $row['svalue'] }}</td>
                                            <td>{{ $row['sort'] }}</td>
                                        @endif
                                        <td>
                                        @if (session('roleid') == 1 || in_array("$controller.delete", $allow_url))
                                            <button type="button" class="btn btn-primary" onclick="delete_row('{{ $row['id'] }}')">删除</button>
                                        @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            @if (session('roleid') == 1 || in_array("$controller.update", $allow_url))
                                <tfoot>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td colspan="3"><button type="submit" class="btn btn-primary">保存</button></td>
                                    </tr>
                                </tfoot>
                            @endif
                            </table>
                        </form>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
            <!-- /.tab-pane -->
        @endforeach
        </div>
        <!-- /.tab-content -->
    </div>
    <!-- nav-tabs-custom -->
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
</script>
@endsection
