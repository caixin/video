@extends('layouts.master')

@section('title')
热门关键字 - @parent
@endsection

@section('content')
<div class="content-wrap margin-top0important padding-top0important" style="padding:0px;">
    <div class="section margin-top0important padding-top-l-important nobottommargin nobottomborder">
        <div class="container clearfix">
            <div class="heading-block center nomargin">
                <h3>热．门．关．键．字</h3>
            </div>
        </div>
    </div>
    <div class="container clearfix padding-top-l-important padding-bottom-l-important ">
        <!-- Portfolio Filter
        ============================================= -->
        <ul class="portfolio-filter clearfix " data-container="#">
            <li {!! !isset($param['tags']) ? 'class="activeFilter"':'' !!}>
                <a href="{{ route('web.tags') }}" data-filter="*">全部</a>
            </li>
        @foreach($tags as $tag)
            <li {!! isset($param['tags']) && $param['tags'] == $tag ? 'class="activeFilter"':'' !!}>
                <a href="{{ route('web.tags', ['tags'=>$tag]) }}" data-filter=".pf-icons">{{ $tag }}</a>
            </li>
        @endforeach
        </ul>

        <div class="clear"></div>
        <!-- Portfolio Items
        ============================================= -->
        <div id="portfolio" class="portfolio grid-container clearfix">
        @foreach($result as $row)
            <article class="portfolio-item pf-media pf-icons">
                <div class="portfolio-image">
                    <a href="{{ route('web.detail', ['keyword'=>$row['keyword']]) }}">
                        <img src="{{ $row['pic_b'] }}" alt="Open Imagination">
                    </a>
                    <div class="portfolio-overlay">
                        <a href="{{ route('web.detail', ['keyword'=>$row['keyword']]) }}" class="right-icon"><i class="icon-line-ellipsis"></i></a>
                    </div>
                </div>
                <div class="portfolio-desc">
                    <h3><a href="{{ route('web.detail', ['keyword'=>$row['keyword']]) }}">{!! $row['name'] !!}</a></h3>
                    <span>
                    @foreach(explode(',',$row['tags']) as $val)
                        <a href="{{ route('web.search', ['search'=>$val]) }}">{{ $val }}</a>,
                    @endforeach
                    </span>
                </div>
            </article>
        @endforeach
        </div>
        <!-- #portfolio end -->
        <div class="container clearfix">
            {!! $result->links('vendor.pagination.web') !!}
        </div>
    </div>
</div>
@endsection
