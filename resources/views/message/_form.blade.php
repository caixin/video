@inject('Ads', 'Models\System\Ads')
@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
    @if ($action == 'create')
        <form method="post" role="form" action="{{ route("$controller.store") }}">
    @elseif ($action == 'edit')
        <form method="post" role="form" action="{{ route("$controller.update",['ad'=>$row['id']]) }}">
            @method('PUT')
    @endif
            @csrf
            <div class="form-group {{ $errors->has('sort') ? 'has-error' : '' }}">
                <label>排序</label>
                <input type="text" name="sort" class="form-control" placeholder="Enter ..." value="{{ old('sort',$row['sort']) }}">
                {!! $errors->first('sort', '<span class="help-block">:message</span>') !!}
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
