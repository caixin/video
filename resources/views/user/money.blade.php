@extends('layouts.backend')

@section('content')
<div class="box box-{{ $errors->any() ? 'danger' : 'success' }}">
    <!-- /.box-header -->
    <div class="box-body">
        <form method="post" role="form" action="{{ route("$controller.money",['user'=>$row['id']]) }}">
            @method('PUT')
            @csrf
            <div class="form-group {{ $errors->has('money') ? 'has-error' : '' }}">
                <label>增减点数 <span style="color:red;">【加点请输入正数，扣点请输入负数，不可為0】</span></label>
                <input type="number" name="money" class="form-control" placeholder="Enter ..." value="{{ old('money',0) }}">
                {!! $errors->first('money', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label>描述 <span style="color:red;">【帐变明细会记录】</span></label>
                <input type="text" name="description" class="form-control" placeholder="Enter ..." value="{{ old('description',$row['description']) }}">
                {!! $errors->first('description', '<span class="help-block">:message</span>') !!}
            </div>
            <button type="submit" class="btn btn-primary">保存</button>
        </form>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
@endsection
