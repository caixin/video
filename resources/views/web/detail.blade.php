@extends('layouts.master')

@section('title')
{!! $video['name'] !!} - @parent
@endsection

@section('css-append')
<link
    href="//vjs.zencdn.net/7.3.0/video-js.min.css"
    rel="stylesheet"
>
@endsection

@section('footer-scripts')
<script src="//vjs.zencdn.net/7.3.0/video.min.js"></script>
<script src="{{ asset('js/videojs-contrib-hls.min.js') }}"></script>
<script>
    var limitSec = {{ $all ? 36000:300 }};
    var player = videojs('player');
    player.on('timeupdate', function() {
        if(player.currentTime() > limitSec) {
            player.pause();
            player.currentTime(0);
        }
    });

    $('#buy').click(function() {
        if (confirm('您确定要扣除1点数观看完整影片吗?'))
        {
            $.ajax({
                url: '{{ route("web.video.buy") }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    keyword: '{{ $video["keyword"] }}'
                },
                error: function(xhr) {
                    alert(xhr.responseJSON.message);
                },
                success: function(res) {
                    if (res.success) {
                        alert('扣除成功!');
                        location.reload();
                    } else {
                        alert('操作发生错误!');
                    }
                }
            });
        }
    });

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
<div class="content-wrap">
    <div class="container clearfix">
        <!-- Post Content
		============================================= -->
        <div class="postcontent nobottommargin clearfix">
            <div class="single-post nobottommargin">
                <!-- Single Post
				============================================= -->
                <div class="entry clearfix">
                    <!-- Entry Title
					============================================= -->
                    <div class="entry-title">
                        <h2>{!! $video['name'] !!}</h2>
                    </div><!-- .entry-title end -->
                    <!-- Entry Meta
					============================================= -->
                    <ul class="entry-meta clearfix">
                        <li><i class="icon-calendar3"></i> {{ $video['publish'] }}</li>
                        <li>
                            <i class="icon-user"></i>
                            @foreach(explode(',',$video['actors']) as $actors)
                            {{ $actors }},
                            @endforeach
                        </li>
                    </ul><!-- .entry-meta end -->
                    <!-- Entry Image
					============================================= -->
                    <div class="entry-image">
                        <video
                            poster="{{ $video['pic_b'] }}"
                            id="player"
                            class="video-js vjs-default-skin"
                            width="100%"
                            height="auto"
                            controls
                            data-setup='{ "aspectRatio":"720:420", "playbackRates": [1, 1.5, 2] }'
                        >
                            <source
                                src="{!! $video['url'] !!}"
                                type="application/x-mpegURL"
                            />
                            <!-- Captions are optional -->
                            <!-- <track kind="captions" label="English captions" src="/path/to/captions.vtt" srclang="en" default /> -->
                        </video>
                    </div><!-- .entry-image end -->
                    <!-- Entry Content
					============================================= -->
                    <div class="entry-content notopmargin">
                        <!-- Post Single - Content End -->
                        <!-- Tag Cloud
						============================================= -->
                        <div class="tagcloud clearfix bottommargin">
                            @foreach (explode(',',$video['tags']) as $tag)
                            <a href="{{ route('web.tags') }}">{{ $tag }}</a>
                            @endforeach
                        </div><!-- .tagcloud end -->
                        <div class="clear"></div>
                        <!-- Post Single - Share
						============================================= -->
                        @if (Auth::guard('web')->check())
                        <div class="si-share noborder clearfix">
                            <span>分享送点数:</span>
                            <div>
                                <input
                                    type="text"
                                    id="share_url"
                                    value="{{ route('web.index', ['refcode'=>session('referrer_code')]) }}"
                                    size="30"
                                    readonly
                                >
                                <input
                                    type="button"
                                    id="copy_btn"
                                    data-clipboard-action="copy"
                                    data-clipboard-target="#share_url"
                                    value="复制连结"
                                >
                            </div>
                        </div><!-- Post Single - Share End -->
                        @endif
                    </div>
                </div><!-- .entry end -->
                <!-- Post Navigation
                ============================================= -->
                <div class="row mb-3">
                    <div class="col-12">
                        <a
                            href="{{ $prev_url == '' ? "javascript:alert('没有上一部了')":route('web.detail',['keyword'=>$prev_url]) }}"
                            class="btn btn-outline-secondary float-left"
                        >&larr; 上一部</a>
                        <a
                            href="{{ $next_url == '' ? "javascript:alert('没有下一部了')":route('web.detail',['keyword'=>$next_url]) }}"
                            class="btn btn-outline-dark float-right"
                        >下一部 &rarr;</a>
                    </div>
                </div>
                {{-- <div class="post-navigation clearfix">
					<div class="col-12">
						<a href="{{ $prev_url == '' ? "javascript:alert('没有上一部了')":route('web.detail',['keyword'=>$prev_url]) }}">&lArr;
                上一部</a>
            </div>
            <div class="col_half col_last tright nobottommargin">
                <a
                    href="{{ $next_url == '' ? "javascript:alert('没有下一部了')":route('web.detail',['keyword'=>$next_url]) }}">下一部
                    &rArr;</a>
            </div>
        </div><!-- .post-navigation end --> --}}
        {{-- <div class="line"></div> --}}
    </div>
</div><!-- .postcontent end -->
<!-- Sidebar
		============================================= -->
<div class="sidebar nobottommargin col_last clearfix">
    <div class="sidebar-widgets-wrap">
        <div class="widget clearfix">
            <h4>💗分享连结注册送点数</h4>
            @if (Auth::guard('web')->check())
            <b>您剩余点数为 {{ $user->money }} 点</b><br />
            @if (!$all)
            <a
                href="javascript:;"
                id="buy"
                class="btn btn-secondary btn-sm "
            >使用点数兑换观看完整影片</a>
            @endif
            @else
            <a
                href="{{ route('web.login') }}"
                class="btn btn-secondary btn-sm "
            >登入看完整影片</a>
            @endif
        </div>
        <div class="widget clearfix">
            <h4>更多影片</h4>
            <div class=" masonry-thumbs">
                @foreach ($more as $row)
                <a href="{{ route('web.detail', ['keyword'=>$row['keyword']]) }}">
                    <img
                        src="{{ $row['pic_s'] }}"
                        alt="{!! $row['name'] !!}"
                        title="{!! $row['name'] !!}"
                    >
                </a>
                @endforeach
            </div>
            @foreach ($ads as $row)
            <a
                href="{{ $row['url'] }}"
                target="_blank"
            ><img src="{{ asset($row['image']) }}"></a>
            @endforeach
        </div>
    </div>
</div><!-- .sidebar end -->
</div>
</div>
@endsection
