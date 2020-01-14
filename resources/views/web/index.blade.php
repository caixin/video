@extends('layouts.master')

@section('title')
首页 - @parent
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

@section('slider')
<section id="slider" class="slider-element slider-parallax swiper_wrapper full-screen clearfix" data-speed="1500" data-loop="true" data-autoplay="10000">
    <div class="slider-parallax-inner">
        <div class="swiper-container swiper-parent" >
            <div class="swiper-wrapper">
                <div class="swiper-slide dark">
                    <div class="container clearfix">
                        <div class="slider-caption slider-caption-center">
                            <h2 data-animate="fadeInUp"><a href="{{ route('web.profile') }}"style="color:white;">点击注册送点数，<br>分享好友注册成功再送点数</a></h2>
                            <p class="d-none d-sm-block" data-animate="fadeInUp" data-delay="200">
                                <a href="{{ route('web.profile') }}" class="button button-rounded button-reveal button-large button-border tright">
                                    <i class="icon-chevron-down1"></i><span>立即分享</span>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="video-wrap">
                        <video id="slide-video" poster="{{ asset('images/videos/explore.jpg') }}" preload="auto" loop autoplay muted>
                            <source src='{{ asset('videos/cut/video001.mp4') }}' type='video/mp4' />
                        </video>
                        <div class="video-overlay" style="background-color: rgba(0,0,0,0.55);"></div>
                    </div>
                </div>
                <div class="swiper-slide dark">
                    <div class="container clearfix">
                        <div class="slider-caption slider-caption-center">
                            <h2 data-animate="flipInY"><a href="{{ route('web.profile') }}"style="color:white;">点击注册送点数，<br>精选影片看不完</a></h2>
                            <p class="d-none d-sm-block infinite" data-animate="pulse" data-delay="200">
                                <a href="{{ route('web.register') }}" class="button button-rounded button-reveal button-large button-border tright">
                                    <i class="icon-chevron-down1"></i><span>立即加入会员</span>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="video-wrap">
                        <video id="slide-video" poster="{{ asset('images/videos/explore.jpg') }}" preload="auto" loop autoplay muted>
                            <source src='{{ asset('videos/cut/video002.mp4') }}' type='video/mp4' />
                        </video>
                        <div class="video-overlay" style="background-color: rgba(0,0,0,0.55);"></div>
                    </div>
                </div>
                <div class="swiper-slide dark">
                    <div class="container clearfix">
                        <div class="slider-caption slider-caption-center">
                            <h2 data-animate="rotateInDownLeft"><a href="{{ route('web.profile') }}"style="color:white;">每日登入签到送一点</a></h2>
                            <p class="d-none d-sm-block" data-animate="rotateInDownLeft" data-delay="200">
                                <a href="{{ route('web.login') }}" class="button button-rounded button-reveal button-large button-border tright">
                                    <i class="icon-chevron-down1"></i><span>解放你的视觉神经</span>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="video-wrap">
                        <video id="slide-video" poster="{{ asset('images/videos/explore.jpg') }}" preload="auto" loop autoplay muted>
                            <source src='{{ asset('videos/cut/video003.mp4') }}' type='video/mp4' />
                        </video>
                        <div class="video-overlay" style="background-color: rgba(0,0,0,0.55);"></div>
                    </div>
                </div>
            </div>
            <div class="slider-arrow-left"><i class="icon-angle-left"></i></div>
            <div class="slider-arrow-right"><i class="icon-angle-right"></i></div>
        </div>
        <a href="#" data-scrollto="#content" data-offset="100" class="dark one-page-arrow">
            <i class="icon-angle-down infinite animated fadeInDown"></i>
        </a>
    </div>
</section>
@endsection

@section('content')
<div class="content-wrap margin-top0important padding-top0important" style="padding:0px;">
    <div class="section margin-top0important padding-top-l-important nobottommargin nobottomborder">
        <div class="container clearfix">
            <div class="heading-block center nomargin">
                <h3>最．新．影．片</h3>
            </div>
        </div>
    </div>
    <div id="portfolio" class="bottommargin-lg portfolio portfolio-nomargin grid-container portfolio-notitle portfolio-full grid-container clearfix">
    @foreach ($video as $row)
        <article class="portfolio-item pf-media pf-icons">
            <div class="portfolio-image">
                <a href="{{ route('web.detail', ['keyword'=>$row['keyword']]) }}">
                    <img src="{{ $row['pic_big'] }}" alt="Open Imagination">
                </a>
                <div class="portfolio-overlay">
                    <a href="{{ route('web.detail', ['keyword'=>$row['keyword']]) }}" class="right-icon"><i class="icon-line-ellipsis"></i></a>
                </div>
            </div>
            <div class="portfolio-desc">
                <h3><a href="{{ route('web.detail', ['keyword'=>$row['keyword']]) }}">{!! $row['name'] !!}</a></h3>
                <span>
                    {{ $row['publish'] }}
                @foreach (explode(',',$row['tags']) as $val)
                    <a href="{{ route('web.search', ['tags'=>$val]) }}">{{ $val }}</a>,
                @endforeach
                </span>
            </div>
        </article>
    @endforeach
    </div>
    <div class="container clearfix padding-top-l-important">
        <div class="row clearfix">
            <div class="col-xl-5">
                <div class="heading-block topmargin">
                    <h1>独乐乐不如众乐乐！</h1>
                </div>
                <p class="lead">注册会员后分享给好友优质影片看不完！<br>想放飞自我快,加入梦天堂！</p>
                <p class="lead">
                @if (Auth::guard('web')->check())
                    <a href="javascript:;" data-clipboard-action="copy" data-clipboard-target="#share_url" id="copy_btn" class="button button-3d button-large button-rounded button-aqua">复制分享连结！</a>
                    <br>
                    <span id="share_url">{{ route('web.index',['refcode'=>session('referrer_code')]) }}</span>
                @else
                    <a href="{{ route('web.login') }}" class="button button-3d button-large button-rounded button-aqua">加入会员并分享！</a>
                @endif
                </p>
            </div>
            <div class="col-xl-7">
                <div style="position: relative; margin-bottom: -60px;" class="ohidden" data-height-xl="426" data-height-lg="567" data-height-md="470" data-height-md="287" data-height-xs="183">
                    <img src="{{ asset('images/services/main-fbrowser.png') }}" style="position: absolute; top: 0; left: 0;" data-animate="fadeInUp" data-delay="100" alt="Chrome">
                    <img src="{{ asset('images/services/main-fmobile.png') }}" style="position: absolute; top: 0; left: 0;" data-animate="fadeInUp" data-delay="400" alt="iPad">
                </div>
            </div>
        </div>
    </div>

    <div class="section nobottommargin">
        <div class="container clear-bottommargin clearfix">
            <div class="row topmargin-sm clearfix">
            @foreach ($ads_down as $row)
                <a href="{{ $row['url'] }}"><img src="{{ $row['image'] }}" alt="{{ $row['name'] }}"></a>
            @endforeach
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<div class="row clearfix align-items-stretch ">
	<div class="col-lg-3 col-md-6 dark center col-padding" style="background-color: #515875;">
		<i class="i-plain i-xlarge divcenter icon-line2-directions"></i>
		<div class="counter counter-lined">
            <span data-from="100" data-to="{{ $members }}" data-refresh-interval="50" data-speed="2000"></span>K
        </div>
		<h5>会员数</h5>
	</div>
	<div class="col-lg-3 col-md-6 dark center col-padding" style="background-color: #576F9E;">
		<i class="i-plain i-xlarge divcenter icon-line2-graph"></i>
		<div class="counter counter-lined">
            <span data-from="1" data-to="{{ $watchs }}" data-refresh-interval="100" data-speed="2500"></span>
        </div>
		<h5>每日观看数</h5>
	</div>
	<div class="col-lg-3 col-md-6 dark center col-padding" style="background-color: #6697B9;">
		<i class="i-plain i-xlarge divcenter icon-line2-layers"></i>
		<div class="counter counter-lined">
            <span data-from="0" data-to="{{ $newvideo }}" data-refresh-interval="25" data-speed="3500"></span>
        </div>
		<h5>本月新进影片</h5>
	</div>
	<div class="col-lg-3 col-md-6 dark center col-padding" style="background-color: #88C3D8;">
		<i class="i-plain i-xlarge divcenter icon-line2-clock"></i>
		<div class="counter counter-lined">
            <span data-from="10000" data-to="{{ $videohours }}" data-refresh-interval="30" data-speed="2700"></span>
        </div>
		<h5>累计影片时数</h5>
	</div>
</div>
@endsection
