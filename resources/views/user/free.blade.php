@extends('layouts.backend')

@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
        <form method="post" role="form" action="{{ route("$controller.free_update",['user'=>$row['id']]) }}">
            @method('PUT')
            @csrf
            <div class="form-group {{ $errors->has('free_day') ? 'has-error' : '' }}">
                <label>免费天数设定 <span style="color:red;">【0为取消免费看，不可输入负数】</span></label>
                <input type="number" name="free_day" class="form-control" placeholder="Enter ..." min="0" value="{{ old('free_day',0) }}">
                {!! $errors->first('free_day', '<span class="help-block">:message</span>') !!}
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
