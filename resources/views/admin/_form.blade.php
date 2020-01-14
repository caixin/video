@inject('Admin', 'Models\Admin\Admin')
@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
    @if ($action == 'create')
        <form method="post" role="form" action="{{ route("$controller.store") }}">
    @elseif ($action == 'edit')
        <form method="post" role="form" action="{{ route("$controller.update",['admin'=>$row['id']]) }}">
            @method('PUT')
    @endif
            @csrf
            <div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
                <label>用户名</label>
                <input type="text" name="username" class="form-control" placeholder="Enter ..." value="{{ old('username',$row['username']) }}">
                {!! $errors->first('username', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label>用户密码 <span style="color:red;">【请输入英数6至12码】</span></label>
                <input type="text" name="password" class="form-control" placeholder="Enter ...">
                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('roleid') ? 'has-error' : '' }}">
                <label>角色群组</label>
                <select name="roleid" class="form-control">
                @foreach ($role as $key => $val)
                    <option value="{{ $key }}" {{ old('roleid',$row['roleid']) == $key ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
                </select>
                {!! $errors->first('roleid', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label>	状态</label>
                <select name="status" class="form-control" {{ $action == 'detail' ? 'disabled' : '' }}>
                    @foreach ($Admin::STATUS as $key => $val)
                        <option value="{{ $key }}" {{ old('status',$row['status']) == $key ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
                {!! $errors->first('status', '<span class="help-block">:message</span>') !!}
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
