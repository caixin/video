<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="keywords" content="{{ $seo['keyword'] ?? '' }}">
	<meta name="description" content="{{ $seo['description'] ?? '' }}">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<!-- Stylesheets
	============================================= -->
	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,400i,700|Raleway:300,400,500,600,700|Crete+Round:400i" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/style.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/swiper.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/dark.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/font-icons.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/animate.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ asset('css/magnific-popup.css') }}" type="text/css" />

<style>
	#top-search-input {
		float: right;
		margin: 33px 0 33px 15px;
		-webkit-transition: margin .4s ease;
		-o-transition: margin .4s ease;
		transition: margin .4s ease;
	}

	#top-search-input form {
		width: 160px;
		height: 34px;
		padding: 0;
		margin: 0;
	}

	#header.sticky-header #top-search-input {
		margin: 13px 0 13px 15px;
	}

	@media (max-width: 991px) {

		#top-search-input {
			float: none;
			margin: 20px 0;
		}

		#top-search-input form {
			margin: 0 auto;
			width: 300px;
		}

	}

	.device-sm #top-search-input form {
		width: 100%;
	}
</style>

	<link rel="stylesheet" href="{{ asset('css/responsive.css') }}" type="text/css" />
	@section('css-append')

	@show
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Document Title
	============================================= -->
	<title>@section('title'){{ $seo['title'] ?? $sysconfig['video_title'] }}@show</title>
</head>

<body class="stretched">
	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">
		<!-- Header
		============================================= -->
		<header id="header" class="transparent-header full-header" data-sticky-class="not-dark">
			<div id="header-wrap">
				<div class="container clearfix">
					<div id="primary-menu-trigger"><i class="icon-reorder"></i></div>
					<!-- Logo
					============================================= -->
					<div id="logo">
                        <a href="{{ route('web.index') }}" class="standard-logo" data-dark-logo="{{ asset('images/logo-dark.png') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="{{ $sysconfig['video_title'] }}">
                        </a>
                        <a href="{{ route('web.index') }}" class="retina-logo" data-dark-logo="{{ asset('images/logo-dark@2x.png') }}">
                            <img src="{{ asset('images/logo@2x.png') }}" alt="{{ $sysconfig['video_title'] }}">
                        </a>
					</div><!-- #logo end -->

					<!-- Primary Navigation
					============================================= -->
					<nav id="primary-menu" class="dark">
						<ul>
						@section('menus')
							<li @if (($page ?? '') == "index") class="current" @endif><a href="{{ route('web.index') }}"><div>首页</div></a></li>
							<li @if (($page ?? '') == "video") class="current" @endif><a href="{{ route('web.video') }}"><div>最新影片</div></a></li>
                            <li @if (($page ?? '') == "tags") class="current" @endif><a href="{{ route('web.tags') }}"><div>热门关键字</div></a></li>
                            <li  class="current" ><a href="https://despan.seloil.com/chat/chatClient/chatbox.jsp?companyID=365049617&configID=1308&jid=9531445661&skillId=20&s=1"><div>客服连结</div></a></li>
                            <li @if (($page ?? '') == "message") class="current" @endif><a href="{{ route('web.message') }}"><div>意见回覆</div></a></li>
                        @if (Auth::guard('web')->check())
                            <li @if (($page ?? '') == "profile") class="current" @endif><a href="{{ route('web.profile') }}"><div>用户资讯</div></a></li>
							<li><a href="{{ route('web.logout') }}"><div>用户登出</div></a></li>
						@else
                            <li @if (($page ?? '') == "login") class="current" @endif><a href="{{ route('web.login') }}"><div>用户登入</div></a></li>
							<li @if (($page ?? '') == "register") class="current" @endif><a href="{{ route('web.register') }}"><div>免费注册</div></a></li>
                        @endif
                            <li class="current">
                                <div id="top-search-input">
                                    <form action="{{ route('web.search') }}" method="get">
                                        <input type="text" name="search" class="form-control" placeholder="输入搜寻关键字">
                                    </form>
                                </div>
                            </li>
							@show
						</ul>
						<!---USER---->
						{{-- <div id="top-cart">
							<a href="{{ route('web.login') }}"><i class="icon-user-alt"></i></a>

						</div><!-- #top-cart end --> --}}
						{{-- <a href="#" class="btn btn-secondary d-none d-md-inline-block">Login</a> --}}
						<div id="top-search">
                                @if (($page ?? '') == "profile") @endif<a href="{{ route('web.profile') }}"><i class="icon-user-alt"></i></a>
						</div>
						<!-- Top Search
						============================================= -->
						{{-- <div id="top-search">
							<a href="#" id="top-search-trigger">
                                <i class="icon-search3"></i>
                                <i class="icon-line-cross"></i>
                            </a>
							<form action="{{ route('web.search') }}" method="get">
								<input type="text" name="search" class="form-control" placeholder="在此输入搜寻关键字...">
							</form>
						</div> --}}
					</nav><!-- #primary-menu end -->
				</div>
			</div>
		</header><!-- #header end -->

		@yield('slider')

		<!-- Content
		============================================= -->
		<section id="content">
		@yield('content')
		</section><!-- #content end -->

		<!-- Footer
		============================================= -->
		<footer id="footer" class="dark">
			<!-- Copyrights
			============================================= -->
			<div id="copyrights">
				<div class="container clearfix">
					<div class="col_half">
						Copyrights &copy; 2014 All Rights Reserved by TvGood Inc.<br>
						<div class="copyright-links"><a href="#">Terms of Use</a> / <a href="#">Privacy Policy</a></div>
					</div>
					<div class="col_half col_last tright">
						<div class="fright clearfix">
							<a href="#" class="social-icon si-small si-borderless si-facebook">
								<i class="icon-facebook"></i>
								<i class="icon-facebook"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-twitter">
								<i class="icon-twitter"></i>
								<i class="icon-twitter"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-gplus">
								<i class="icon-gplus"></i>
								<i class="icon-gplus"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-pinterest">
								<i class="icon-pinterest"></i>
								<i class="icon-pinterest"></i>
							</a>

							<a href="#" class="social-icon si-small si-borderless si-vimeo">
								<i class="icon-vimeo"></i>
								<i class="icon-vimeo"></i>
							</a>
						</div>

						<div class="clear"></div>
                        <i class="icon-envelope2"></i> info@canvas.com <span class="middot">&middot;</span>
                        <i class="icon-headphones"></i> +91-11-6541-6369 <span class="middot">&middot;</span>
                        <i class="icon-skype2"></i> CanvasOnSkype
					</div>
				</div>
			</div><!-- #copyrights end -->
		</footer><!-- #footer end -->
	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	<!-- External JavaScripts
	============================================= -->
	<script src="{{ asset('js/jquery.js') }}"></script>
	<script src="{{ asset('js/plugins.js') }}"></script>

	<!-- Footer Scripts
	============================================= -->
	<script src="{{ asset('js/functions.js') }}"></script>
	<script src="{{ asset('js/clipboard.min.js') }}"></script>
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    </script>
    {!! $seo['baidu'] ?? '' !!}
	@section('footer-scripts')

	@show
</body>
</html>
