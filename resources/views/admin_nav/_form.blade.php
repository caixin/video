@inject('AdminNav', 'Models\Admin\AdminNav')
@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
    @if ($action == 'create')
        <form method="post" role="form" action="{{ route("$controller.store") }}">
    @elseif ($action == 'edit')
        <form method="post" role="form" action="{{ route("$controller.update",['admin_nav'=>$row['id']]) }}">
            @method('PUT')
    @endif
            @csrf
            <div class="form-group {{ $errors->has('pid') ? 'has-error' : '' }}">
                <label>父导航</label>
                <select name="pid" class="form-control select2" style="width: 100%;">
                    <option value="0">无父层</option>
                    @foreach ($nav as $key => $val)
                        <option value="{{ $key }}" {{ $row['pid'] == $key ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
                {!! $errors->first('pid', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label>导航名称</label>
                <input type="text" name="name" class="form-control" placeholder="Enter ..." value="{{ old('name',$row['name']) }}">
                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('route') ? 'has-error' : '' }}">
                <label>主路由</label>
                <input type="text" name="route" class="form-control" placeholder="Enter ..." value="{{ old('route',$row['route']) }}">
                {!! $errors->first('route', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('route1') ? 'has-error' : '' }}">
                <label>次路由1</label>
                <input type="text" name="route1" class="form-control" placeholder="Enter ..." value="{{ old('route1',$row['route1']) }}">
                {!! $errors->first('route1', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('route2') ? 'has-error' : '' }}">
                <label>次路由2</label>
                <input type="text" name="route2" class="form-control" placeholder="Enter ..." value="{{ old('route2',$row['route2']) }}">
                {!! $errors->first('route2', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('sort') ? 'has-error' : '' }}">
                <label>排序</label>
                <input type="text" name="sort" class="form-control" placeholder="Enter ..." value="{{ old('sort',$row['sort']) }}">
                {!! $errors->first('sort', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('status') ? 'has-error' : '' }}">
                <label>	状态</label>
                <select name="status" class="form-control" {{ $action == 'detail' ? 'disabled' : '' }}>
                    @foreach ($AdminNav::STATUS as $key => $val)
                        <option value="{{ $key }}" {{ old('status',$row['status']) == $key ? 'selected' : '' }}>{{ $val }}</option>
                    @endforeach
                </select>
                {!! $errors->first('status', '<span class="help-block">:message</span>') !!}
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
