@inject('Sysconfig', 'Models\System\sysconfig')
@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
        <form method="post" role="form" action="{{ route("$controller.store") }}">
            @csrf
            <div class="form-group {{ $errors->has('groupid') ? 'has-error' : '' }}">
                <label>变量组</label>
                <select name="groupid" class="form-control">
                @foreach ($Sysconfig::GROUPID as $key => $val)
                    <option value="{{ $key }}" {{ old('groupid',$row['groupid']) == $key ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
                </select>
                {!! $errors->first('groupid', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                <label>变量类型</label>
                <select name="type" class="form-control">
                @foreach ($Sysconfig::TYPE as $key => $val)
                    <option value="{{ $key }}" {{ old('type',$row['type']) == $key ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
                </select>
                {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('skey') ? 'has-error' : '' }}">
                <label>变量名称</label>
                <input type="text" name="skey" class="form-control" placeholder="Enter ..." value="{{ old('skey',$row['skey']) }}">
                {!! $errors->first('skey', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('svalue') ? 'has-error' : '' }}">
                <label>变量值</label>
                <input type="text" name="svalue" class="form-control" placeholder="Enter ..." value="{{ old('svalue',$row['svalue']) }}">
                {!! $errors->first('svalue', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('info') ? 'has-error' : '' }}">
                <label>变量说明</label>
                <input type="text" name="info" class="form-control" placeholder="Enter ..." value="{{ old('info',$row['info']) }}">
                {!! $errors->first('info', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('sort') ? 'has-error' : '' }}">
                <label>排序</label>
                <input type="number" name="sort" class="form-control" placeholder="Enter ..." value="{{ old('sort',$row['sort']) }}">
                {!! $errors->first('sort', '<span class="help-block">:message</span>') !!}
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
