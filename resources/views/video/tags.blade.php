@extends('layouts.backend')
@inject('VideoTags', 'Models\Video\VideoTags')

@section('content')
{!! lists_message() !!}
<div class="box">
    <div class="box-header">
        <form method="POST" action="{{ route("$controller.search") }}">
            @csrf
            <div class="col-xs-1" style="width:150px;">
                <label>标签</label>
                <input type="text" name="name" class="form-control" placeholder="请输入..." value="{{ $search['name'] ?? '' }}">
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
                    <th>{!! sort_title('name', '标签', $route, $order, $search) !!}</th>
                    <th>{!! sort_title('hot', '是否为热门', $route, $order, $search) !!}</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($result as $row)
                <tr>
                    <td>{{ $row['id'] }}</td>
                    <td><a href="{{ route("video.index",['tags'=>$row['name']]) }}">{{ $row['name'] }}</a></td>
                    <td>
                    @if (session('roleid') == 1 || in_array("$controller.save", $allow_url))
                        <button type="button" id="hot1_{{ $row['id'] }}" class="btn {{ $row['hot'] == 1 ? 'btn-info' : 'btn-default' }}" onclick="hot_row({{ $row['id'] }},1)">{{ $VideoTags::HOT[1] }}</button>
                        <button type="button" id="hot0_{{ $row['id'] }}" class="btn {{ $row['hot'] == 0 ? '' : 'btn-default' }}" onclick="hot_row({{ $row['id'] }},0)">{{ $VideoTags::HOT[0] }}</button>
                    @else
                        {{ $VideoTags::HOT[$row['hot']] }}
                    @endif
                    </td>
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
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    //关闭
    function hot_row(id, hot) {
        if (hot == 1) {
            $('#hot1_' + id).removeClass('btn-default').addClass('btn-info');
            $('#hot0_' + id).addClass('btn-default');
        } else {
            $('#hot1_' + id).removeClass('btn-info').addClass('btn-default');
            $('#hot0_' + id).removeClass('btn-default');
        }
        $.post('{{ url($controller) }}/' + id + '/save', {
            'hot': hot
        });
    }
</script>
@endsection
