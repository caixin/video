@extends('layouts.master')

@section('title')
注册 - @parent
@endsection

@section('footer-scripts')
<script>
    $('#sendSMS').click(function() {
        $.ajax({
            url: '{{ route("web.verify_code") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                mobile: $('#username').val()
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            },
            success: function(res) {
                if (res.success) {
                    alert('验证码已发送!');
                } else {
                    alert('操作发生错误!');
                }
            }
        });
    });
    $('#sendEmail').click(function() {
        $.ajax({
            url: '{{ route("web.verify_code_email") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                mobile: $('#username').val(),
                email: $('#email').val()
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            },
            success: function(res) {
                if (res.success) {
                    alert('验证码已发送!');
                } else {
                    alert('操作发生错误!');
                }
            }
        });
    });
</script>
@endsection

@section('content')
<div class="content-wrap margin-top0important padding-top0important" style="padding:0px;">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="accordion accordion-lg divcenter nobottommargin clearfix" style="max-width: 550px;">
                <div class="acctitle"><i class="acc-closed icon-user4"></i><i class="acc-open icon-ok-sign"></i>还没加入会员？</div>
                <div class="acc_content clearfix">
                @if ($errors->any() || session('message'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-ban"></i> 错误!</h4>
                        {{ session('message') }}
                        @foreach ($errors->all() as $error)
                            {{ $error  }} <br>
                        @endforeach
                    </div>
                @endif
                    <form id="register-form" name="register-form" class="nobottommargin" action="{{ route('web.register') }}" method="post">
                        @csrf
                        <div class="col_full">
                            <label for="register-form-code">介绍码:</label>
                            <input type="text" id="referrer_code" name="referrer_code" class="form-control" placeholder="选填，分享注册送点数！！！！" {!! session('referrer_code') ? 'value="'.session('referrer_code').'" readonly':'' !!} />
                        </div>
                        <div class="col_full">
                            <label for="username">
                                <span style="color:red;">*</span> 手机号:
                                <button type="button" class="button button-3d button-mini button-rounded button-leaf" id="sendSMS">发送验证码</button>
                            </label>
                            <input type="text" id="username" name="username" placeholder="手机号与邮箱择一验证！" value="{{ old('username') }}" class="form-control" />
                        </div>
                        <div class="col_full">
                            <label for="email">
                                邮箱:
                                <button type="button" class="button button-3d button-mini button-rounded button-leaf" id="sendEmail">发送验证码</button>
                            </label>
                            <input type="text" id="email" name="email" placeholder="手机号与邮箱择一验证！" value="{{ old('email') }}" class="form-control" />
                        </div>
                        <div class="col_full">
                            <label for="verify_code"><span style="color:red;">*</span> 验证码:</label>
                            <input type="text" id="verify_code" name="verify_code" class="form-control" />
                        </div>
                        <div class="col_full">
                            <label for="password"><span style="color:red;">*</span> 密码: <span style="color:red;">【请输入英数6至12码】</span></label>
                            <input type="password" id="password" name="password" class="form-control" />
                        </div>
                        <div class="col_full">
                            <label for="repassword"><span style="color:red;">*</span> 再次输入密码:</label>
                            <input type="password" id="repassword" name="repassword" value="" class="form-control" />
                        </div>
                        <div class="col_full nobottommargin">
                            <button type="submit" class="button button-3d button-black nomargin">注册</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
