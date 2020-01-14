@extends('layouts.master')

@section('title')
登入 - @parent
@endsection

@section('content')
<div class="content-wrap margin-top0important padding-top0important" style="padding:0px;">
    <div class="content-wrap">
        <div class="container clearfix">
            <div class="accordion accordion-lg divcenter nobottommargin clearfix" style="max-width: 550px;">
                <div class="acctitle"><i class="acc-closed icon-lock3"></i><i class="acc-open icon-unlock"></i>登入会员</div>
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
                    <form id="login-form" name="login-form" class="nobottommargin" action="{{ route('web.login') }}" method="post">
                        @csrf
                        <div class="col_full">
                            <label for="username">手机号:</label>
                            <input type="text" id="username" name="username" class="form-control" />
                        </div>
                        <div class="col_full">
                            <label for="password">密码:</label>
                            <input type="password" id="password" name="password" class="form-control" />
                        </div>
                        <div class="col_full nobottommargin">
                            <button type="submit" class="button button-3d button-black nomargin">登入</button>
                            <button type="button" class="button button-3d button-black nomargin" onclick="location.href='{{ route('web.register') }}'">免费注册</button>
                            <a href="{{ route('web.forgot') }}" class="fright">忘记密码</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

