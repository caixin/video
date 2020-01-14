@extends('layouts.master')
@inject('UserMoneyLog', 'Models\User\UserMoneyLog')

@section('title')
个人资讯 - @parent
@endsection

@section('footer-scripts')
<script>
$(document).ready(function(){
    var clipboard = new Clipboard('#copy_btn');
    clipboard.on('success', function(e) {
        alert("复制成功",1500);
        e.clearSelection();
    });
});
</script>
@endsection

@section('content')
<div class="content-wrap margin-top0important padding-top0important" style="padding:0px;">

        {{-- <div class="container clearfix"> --}}
            <div class="accordion accordion-lg divcenter nobottommargin clearfix" style="max-width: 600px;">
                <div class="acctitle"><i class="acc-closed icon-user4"></i><i class="icon-user"></i>用户资讯</div>
                <div class="acc_content clearfix">
                    <div class="col_full">
                        <label for="register-form-name">🌈电话:</label><br>
                        <span>{{ $user['username'] }}</span>
                    </div>
                    <div class="col_full">
                        <label for="register-form-email">🌈可使用点数:</label><br>
                        <span style="font-size: 20px; font-weight:bold;">{{ $user['money'] }}</span>
                    <div class="col_full">
                        <label>💗贴心小提示💗注册即赠5点、分享成功赠5点、每日签到赠1点</label>
                    </div>
                    </div>
                    <div class="col_full">
                        <label for="register-form-email">🌈免费畅看到期日:</label><br>
                        <span>{{ $user['free_time'] }}</span>
                    </div>
                    <div class="col_full">
                        <label for="register-form-email">🌈专属连结:</label><br>
                        <span id="share_url">{{ route('web.index',['refcode'=>session('referrer_code')]) }}</span><br>
                        <a href="javascript:;" data-clipboard-action="copy" data-clipboard-target="#share_url" id="copy_btn" class="button button-3d button-large button-rounded button-aqua">复制分享连结</a>

                    <div class="col_full">
                    <label>💗贴心小提示💗分享连结注册会送点数喔！！</label>
                    </div>
                </div>

                <div class="line"></div>

                <h4>帐变明细</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>变动类型</th>
                            <th>变动点数</th>
                            <th>描述</th>
                            <th>时间</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($moneylog as $row)
                        <tr>
                            <td>{{ $UserMoneyLog::TYPE[$row['type']] }}</td>
                            <td>{{ $row['money_add'] }}</td>
                            <td>{!! $row['description'] !!}</td>
                            <td>{{ $row['created_at'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="99">没有资料!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div class="line"></div>

                <h4>我的分享成果</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>电话</th>
                            <th>加入时间</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($result as $row)
                        <tr>
                            <td>{{ $row['username'] }}</td>
                            <td>{{ $row['created_at'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="99">没有资料!</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {!! $result->links('vendor.pagination.web') !!}
            </div>
        </div>
    </div>
</div>
@endsection

