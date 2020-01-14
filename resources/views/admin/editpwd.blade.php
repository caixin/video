@extends('layouts.backend')

@section('inpage_title')
修改密码
@endsection

@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
        <form method="post" role="form" action="{{ route("admin.updatepwd") }}">
            @csrf
            <div class="form-group {{ $errors->has('old_pwd') ? 'has-error' : '' }}">
                    <label>旧密码 <span style="color:red;">【请输入旧密码】</span></label>
                <input type="text" name="old_pwd" class="form-control" placeholder="Enter ...">
                {!! $errors->first('old_pwd', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
                    <label>新密码 <span style="color:red;">【请输入新密码】</span></label>
                <input type="text" name="password" class="form-control" placeholder="Enter ...">
                {!! $errors->first('password', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('repassword') ? 'has-error' : '' }}">
                    <label>确认新密码 <span style="color:red;">【请再输入一次新密码】</span></label>
                <input type="text" name="repassword" class="form-control" placeholder="Enter ...">
                {!! $errors->first('repassword', '<span class="help-block">:message</span>') !!}
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
