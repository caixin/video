@extends('layouts.master')
@inject('Message', 'Models\System\Message')

@section('title')
意见回覆 - @parent
@endsection

@section('content')
<!-- Contact Form Overlay ============================================= -->
<div
    id="contact-form-overlay"
    class="clearfix"
>

    <div class="fancy-title title-dotted-border">
        <h3 class="icon-line-mail">意见回覆</h3>
    </div>

    @if ($errors->any() || session('message'))
    <div class="alert alert-danger alert-dismissible">
        <button
            type="button"
            class="close"
            data-dismiss="alert"
            aria-hidden="true"
        >×</button>
        <h4><i class="icon fa fa-ban"></i> 错误!</h4>
        {{ session('message') }}
        @foreach ($errors->all() as $error)
        {{ $error  }} <br>
        @endforeach
    </div>
    @endif
    <!-- Contact Form ============================================= -->
    <form
        action="{{ route('web.message') }}"
        method="post"
    >
        @csrf
        <div class="col_half">
            <label>问题</label>
            <select
                name="type"
                class="sm-form-control"
            >
                <option value="">-- 问题类型 --</option>
                @foreach ($Message::TYPE as $key => $val)
                <option value="{{ $key }}">{{ $val }}</option>
                @endforeach
            </select>
        </div>

        <div class="col_full">
            <label>问题描述 <small>*</small></label>
            <textarea
                class="required sm-form-control"
                name="content"
                rows="6"
                cols="30"
            ></textarea>
        </div>

        <div class="col_full">
            <button
                class="button button-3d nomargin"
                type="submit"
            >确认送出</button>
        </div>
    </form>
</div>
@endsection
