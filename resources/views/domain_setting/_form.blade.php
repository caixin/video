@inject('Ads', 'Models\System\Ads')
@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
    @if ($action == 'create')
        <form method="post" role="form" action="{{ route("$controller.store") }}">
    @elseif ($action == 'edit')
        <form method="post" role="form" action="{{ route("$controller.update",['domain_setting'=>$row['id']]) }}">
            @method('PUT')
    @endif
            @csrf
            <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                <label>网域</label>
                <input type="text" name="domain" class="form-control" placeholder="Enter ..." value="{{ old('domain',$row['domain']) }}">
                {!! $errors->first('domain', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('title') ? 'has-error' : '' }}">
                <label>标题</label>
                <input type="text" name="title" class="form-control" placeholder="Enter ..." value="{{ old('title',$row['title']) }}">
                {!! $errors->first('title', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('keyword') ? 'has-error' : '' }}">
                <label>关键字</label>
                <input type="text" name="keyword" class="form-control" placeholder="Enter ..." value="{{ old('keyword',$row['keyword']) }}">
                {!! $errors->first('keyword', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label>描述</label>
                <input type="text" name="description" class="form-control" placeholder="Enter ..." value="{{ old('description',$row['description']) }}">
                {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('baidu') ? 'has-error' : '' }}">
                <label>百度统计代码</label>
                <textarea name="baidu" class="form-control" placeholder="Enter ..." rows="10">{{ old('baidu',$row['baidu']) }}</textarea>
                {!! $errors->first('baidu', '<span class="help-block">:message</span>') !!}
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
