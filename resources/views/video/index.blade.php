@extends('layouts.backend')
@inject('Video', 'Models\Video\Video')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <div class="col-xs-1" style="width:150px;">
                <label>片名</label>
                <input type="text" name="name" class="form-control" placeholder="请输入..." value="{{ $search['name'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:150px;">
                <label>女优</label>
                <input type="text" name="actors" class="form-control" placeholder="请输入..." value="{{ $search['actors'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:150px;">
                <label>标签</label>
                <input type="text" name="tags" class="form-control" placeholder="请输入..." value="{{ $search['tags'] ?? '' }}">
            </div>
            <div class="col-xs-1" style="width:auto;">
                <label>发行日期</label>
                <div class="input-group">
                    <input type="text" id="publish_from" name="publish1" class="form-control datepicker" style="width:50%" placeholder="起始时间" value="{{ $search['publish1'] ?? '' }}" autocomplete="off">
                    <input type="text" id="publish_to" name="publish2" class="form-control datepicker" style="width:50%" placeholder="结束时间" value="{{ $search['publish2'] ?? '' }}" autocomplete="off">
                </div>
            </div>
            <div class="col-xs-1" style="width:150px;">
                <label>状态</label>
                <select name="status" class="form-control">
                    <option value="">请选择</option>
                @foreach ($Video::STATUS as $key => $val)
                    <option value="{{ $key }}" {{ isset($search['status']) && $search['status'] == $key ? 'selected':'' }}>{{ $val }}</option>
                @endforeach
                </select>
            </div>
            <div class="col-xs-1">
                <label>&nbsp;</label>
                <button type="submit" class="form-control btn btn-primary">查询</button>
            </div>
        </form>
    </div>
    <div class="box-header">
        <label for="per_page">显示笔数:</label>
        <input type="test" id="per_page" style="text-align:center;" value="{{ $per_page }}" size="1">
        <h5 class="box-title" style="font-size: 14px;">
            <b>总计:</b> {{ $result->total() }} &nbsp;
        </h5>
        {!! $result->links() !!}
    </div>
    <!-- /.box-header -->
    <div class="box-body table-responsive no-padding">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="60">{!! sort_title('id', '编号', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('keyword', 'Keyword', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('name', '片名', $route, $order, $search) !!}</th>
                    <th>圖片</th>
                    <th width="100">{!! sort_title('actors', '女优', $route, $order, $search) !!}</th>
                    <th width="300">{!! sort_title('tags', '标签', $route, $order, $search) !!}</th>
                    <th width="100">{!! sort_title('publish', '发行日期', $route, $order, $search) !!}</th>
                    <th width="60">{!! sort_title('status', '状态', $route, $order, $search) !!}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    <td>{{ $row['keyword'] }}</td>
                    <td>{{ $row['name'] }}</td>
                    <td><img src="{{ $row['pic_s'] }}" /></td>
                    <td>{{ $row['actors'] }}</td>
                    <td>{{ $row['tags'] }}</td>
                    <td>{{ $row['publish'] }}</td>
                    <td>{{ $Video::STATUS[$row['status']] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix">
        {!! $result->links() !!}
    </div>
</div>
@endsection
