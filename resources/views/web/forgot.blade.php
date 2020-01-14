@extends('layouts.master')

@section('title')
忘记密码 - @parent
@endsection

@section('footer-scripts')
<script>
    $('#sendSMS').click(function() {
        $.ajax({
            url: '{{ route("web.forgot_code") }}',
            type: 'POST',
            dataType: 'json',
            data: {
                mobile: $('#mobile').val()
            },
            error: function(xhr) {
                alert(xhr.responseJSON.message);
            },
            success: function(res) {
                if (res.success) {
                    alert('讯息已发送!');
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
                <div class="acctitle"><i class="acc-closed icon-user4"></i><i class="acc-open icon-ok-sign"></i>忘记密码</div>
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
                    <form class="nobottommargin" action="{{ route('web.forgot') }}" method="post">
                        @csrf
                        <div class="col_full">
                            <label for="mobile">
                                <span style="color:red;">*</span> 手机号:
                                <button type="button" id="sendSMS" class="button button-3d button-mini button-rounded button-leaf">发送重置验证码</button>
                            </label>
                            <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}" class="form-control" />
                        </div>
                        <div class="col_full">
                            <label for="verify_code"><span style="color:red;">*</span> 重置验证码:</label>
                            <input type="text" id="verify_code" name="verify_code" class="form-control" />
                        </div>
                        <div class="col_full">
                            <label for="password">
                                <span style="color:red;">*</span> 新密码:
                                <span style="color:red;">【请输入英数6至12码】</span>
                            </label>
                            <input type="password" id="password" name="password" class="form-control" />
                        </div>
                        <div class="col_full">
                            <label for="repassword"><span style="color:red;">*</span> 再次输入新密码:</label>
                            <input type="password" id="repassword" name="repassword" class="form-control" />
                        </div>
                        <div class="col_full nobottommargin">
                            <button type="submit" class="button button-3d button-black nomargin">送出</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
