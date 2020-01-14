@extends('layouts.master')

@section('title')
搜寻 - @parent
@endsection

@section('content')
<div class="content-wrap margin-top0important padding-top0important" style="padding:0px;">
    <div class="section margin-top0important padding-top-l-important nobottommargin nobottomborder">
        <div class="container clearfix">
            <div class="heading-block center nomargin">
                <h3>搜．寻．影．片</h3>
            </div>
        </div>
    </div>
    <div class="container clearfix">
        <div id="portfolio" class="bottommargin-lg portfolio portfolio- grid-container portfolio-notitle  grid-container clearfix">
        {{-- new movies block start --}}
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
                        {{ $row['publish'] }}
                    @foreach (explode(',', $row['tags']) as $val)
                        <a href="{{ route('web.search', ['tags'=>$val]) }}">{{ $val }}</a>,
                    @endforeach
                    </span>
                </div>
            </article>
        @endforeach
        {{-- new movies block end --}}
        </div>
        <div class="container clearfix">
            {!! $result->links('vendor.pagination.web') !!}
        </div>
    </div>

</div>
@endsection

