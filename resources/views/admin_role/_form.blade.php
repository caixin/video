@inject('Admin', 'Models\Admin\Admin')
@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
    @if ($action == 'create')
        <form method="post" role="form" action="{{ route("$controller.store") }}">
    @elseif ($action == 'edit')
        <form method="post" role="form" action="{{ route("$controller.update",['admin_role'=>$row['id']]) }}">
            @method('PUT')
    @endif
            @csrf
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label>角色名称</label>
                <input type="text" name="name" class="form-control" placeholder="Enter ..." value="{{ old('name',$row['name']) }}">
                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group">
                <label>导航权限</label>
                <div class="box-body table-responsive no-padding">
                    <table class="table table-hover">
                        @foreach ($nav as $nav1)
                            <tr style="background:#BABABA;">
                                <th colspan="2">
                                    <label><input type="checkbox" id="nav1_{{ $nav1['id'] }}" name="allow_nav[{{ $nav1['id'] }}]" value="{{ $nav1['route'] }}" onclick="checkall({{ $nav1['id'] }},'nav1')" nav1="{{ $nav1['id'] }}" {{ in_array($nav1['route'], old('allow_nav',(array)$row['allow_nav'])) ? 'checked' : '' }}> {{ $nav1['name'] }}</label>
                                </th>
                            </tr>
                            @foreach ($nav1['sub'] as $nav2)
                                <tr>
                                    <th style="background:#C7C400">
                                        <label><input type="checkbox" id="nav2_{{ $nav2['id'] }}" name="allow_nav[{{ $nav2['id'] }}]" value="{{ $nav2['route'] }}" onclick="cancelcheckall({{ $nav2['id'] }},'nav2')" onchange="group_check({{ $nav1['id'] }},'nav1')" nav1="{{ $nav1['id'] }}" nav2="{{ $nav2['id'] }}" {{ in_array($nav2['route'], old('allow_nav',(array)$row['allow_nav'])) ? 'checked' : '' }}> {{ $nav2['name'] }}</label>
                                    </th>
                                    <td>
                                        @foreach ($nav2['sub'] as $nav3)
                                            <label><input type="checkbox" id="nav3_{{ $nav3['id'] }}" name="allow_nav[{{ $nav3['id'] }}]" value="{{ $nav3['route'] }}" onchange="group_check({{ $nav2['id'] }},'nav2')" nav1="{{ $nav1['id'] }}" nav2="{{ $nav2['id'] }}" {{ in_array($nav3['route'], old('allow_nav',(array)$row['allow_nav'])) ? 'checked' : '' }}> {{ $nav3['name'] }}</label>
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
<script>
    $('.select2').select2();

    function checkall(k, attr) {
        $('input:checkbox[' + attr + '="' + k + '"]').each(function() {
            $(this).prop('checked', $('#' + attr + '_' + k).prop('checked'));
        });
    }

    function cancelcheckall(k, attr) {
        $('input:checkbox[' + attr + '="' + k + '"]').each(function() {
            if ($('#' + attr + '_' + k).prop('checked') == false) {
                $(this).prop('checked', false);
            }
        });
    }

    function group_check(k, attr) {
        var prop = false;
        $('input:checkbox[' + attr + '="' + k + '"]').each(function() {
            if ($(this).prop('checked')) prop = true;
        });

        $('#' + attr + '_' + k).prop('checked', prop).change();
    }
</script>
@endsection
