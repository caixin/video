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
            <div class="form-group {{ $errors->has('domain') ? 'has-error' : '' }}">
                <label>网域 <span style="color:red;">【留空表示允许所有网域】</span></label>
                <select name="domain[]" class="form-control select2" multiple="multiple">
                @foreach (old('domain',$row['domain']) as $val)
                    <option selected>{{ $val }}</option>
                @endforeach
                </select>
                {!! $errors->first('domain', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                <label>广告位置</label>
                <select name="type" class="form-control">
                @foreach ($Ads::TYPE as $key => $val)
                    <option value="{{ $key }}" {{ old('type',$row['type']) == $key ? 'selected' : '' }}>{{ $val }}</option>
                @endforeach
                </select>
                {!! $errors->first('type', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label>广告名称</label>
                <input type="text" name="name" class="form-control" placeholder="Enter ..." value="{{ old('name',$row['name']) }}">
                {!! $errors->first('name', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('image') ? 'has-error' : '' }}">
                <label>广告图片</label>
                <button type="button" id="upload" class="btn btn-primary">上传图片</button>
                <button type="button" id="delete" class="btn btn-primary">删除图片</button>
                <br>
                <span class="error" id="error_image"></span>
                <input type="hidden" name="image" value="{{ old('image',$row['image']) }}">
                <img id="img" src="{{ asset(old('image',$row['image'])) }}" width="100">
                {!! $errors->first('image', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('url') ? 'has-error' : '' }}">
                <label>广告连结</label>
                <input type="text" name="url" class="form-control" placeholder="Enter ..." value="{{ old('url',$row['url']) }}">
                {!! $errors->first('url', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('start_time') ? 'has-error' : '' }}">
                <label>开始时间</label>
                <input type="text" name="start_time" class="form-control secpicker" placeholder="Enter ..." value="{{ old('start_time',$row['start_time']) }}">
                {!! $errors->first('start_time', '<span class="help-block">:message</span>') !!}
            </div>
            <div class="form-group {{ $errors->has('end_time') ? 'has-error' : '' }}">
                <label>结束时间</label>
                <input type="text" name="end_time" class="form-control secpicker" placeholder="Enter ..." value="{{ old('end_time',$row['end_time']) }}">
                {!! $errors->first('end_time', '<span class="help-block">:message</span>') !!}
            </div>
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
<div id="plupload_ani" style="display:none;"></div>
<script src="{{ asset('backend/plugins/plupload/plupload.full.min.js') }}"></script>
<script>
    $('.select2').select2({
        tags: true,
        tokenSeparators: [",", " "]
    });
    function toggle_image() {
        if ($('[name="image"]').val() == '') {
            $('#delete, #img').hide();
            $('#upload, #error_image').show();
        } else {
            $('#delete, #img').show();
            $('#upload, #error_image').hide();
        }
    }

    var uploader = new plupload.Uploader({
        runtimes: 'html5,flash,silverlight,html4',
        browse_button: 'upload', // you can pass in id...
        container: 'plupload_ani', // ... or DOM Element itself
        max_file_size: '2mb',
        multi_selection: false,
        url: '{{ route("ajax.imageupload", ["dir"=>$controller]) }}',
        flash_swf_url: '{{ asset('backend/plugins/plupload/Moxie.swf') }}',
        silverlight_xap_url: '{{ asset('backend/plugins/plupload/Moxie.xap') }}',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },

        filters: {
            max_file_size: '2mb',
            mime_types: [{
                    title: "Image files",
                    extensions: "jpg,gif,png"
                },
                {
                    title: "Zip files",
                    extensions: "zip"
                }
            ]
        },

        init: {
            PostInit: function() {},

            FilesAdded: function(up, files) {
                up.refresh(); // Reposition Flash/Silverlight
                setTimeout(function() {
                    uploader.start();
                }, 1000); // auto start
            },

            UploadProgress: function(up, file) {
                $('#error_image').html(file.percent + '%');
            },

            Error: function(up, err) {
                $('#error_image').html(err.code + ": " + err.message);
            },

            FileUploaded: function(up, file, response) {
                var go_response = response;
                var response = $.parseJSON(go_response.response);
                if (response.status == '1') {
                    $('#img').attr('src', '{{ asset('') }}'+response.filelink);
                    $('[name="image"]').val(response.filelink);
                    toggle_image();
                } else {
                    $("#error_image").html(response.message);
                }
            }
        }
    });

    uploader.init();

    $('#delete').click(function() {
        $('[name="image"]').attr('value', '');
        $('#img').attr('src', '');
        toggle_image();
    });

    toggle_image();
</script>
@endsection
