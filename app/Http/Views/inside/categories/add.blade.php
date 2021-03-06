@extends("{$moduleName}.layout.master")
@section('content')
<div id="page-title">
    <h1 class="page-header text-overflow">{!! $title !!}</h1>
</div>

<ol class="breadcrumb">
    <li>
        <a href="{!! url("/{$moduleName}/{$controllerName}/show-all") !!}">
           {!! $title !!}
    </a>
</li>
    <li class="active">Thêm mới</li>
</ol>

<div id="page-content">
    <form role="form" class="form-horizontal" method="post">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Thêm mới</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <label class="col-sm-2 control-label" for="form-field-1">
                        <strong>Tên Category</strong> <span class="symbol required"></span>
                    </label>
                    <div class="col-sm-7">
                        {!! Form::text("name", null, ['class' => 'form-control' , "id" => "name", 'required' => "required"]) !!}
                        <span class="text-danger">{!! $errors->first("name") !!}</span>
                    </div>
                </div>



                <div class="form-group">
                    <label class="col-sm-2 control-label" for="form-field-1">
                        <strong>Category type</strong>
                    </label>
                    <div class="col-sm-7">
                        {!! Form::select("type", $cat_types, null, ['class' => 'form-control', "id" => "type", 'required' => "required"]) !!}
                        <span class="text-danger">{!! $errors->first("type") !!}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label" for="form-field-1">
                        <strong>Category style</strong>
                    </label>
                    <div class="col-sm-7">
                        {!! Form::select("style", $cat_styles, null, ['class' => 'form-control', "id" => "style", 'required' => "required" ]) !!}
                        <span class="text-danger">{!! $errors->first("style") !!}</span>
                    </div>
                </div>

                <div class="form-group" style="margin-left: 245px;">
                    <div class="col-sm-3">
                        <div class="col-sm-10 btn-file">
                            480x415
                            <div class="fileupload-new thumbnail" style="width: 140px; height: 150px; margin-bottom: 3px;">
                                @if (strlen(old('image_name')))
                                    <img id="preview-file-upload" style="width: 140px; height: 140px;" src="/upload/temp/{!! old('image_name') !!}">
                                @else
                                    <img id="preview-file-upload" style="width: 140px; height: 140px;" src="/assets/inside/img/no-image.jpg">
                                @endif
                            </div>
                            <input id="file_upload" name="file_upload" type="file" multiple="false">
                            {!! Form::hidden("image_name") !!}
                            {!! Form::hidden("is_new_image", 1) !!}
                            <span class="help-block has-error">{!! $errors->first("icon_location") !!}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-footer text-right">
                <a href='{!! url("/{$moduleName}/{$controllerName}/show-all") !!}' class="btn btn-success btn-labeled fa fa-arrow-left pull-left">Về danh sách Category</a>
                <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                <button class="btn btn-primary btn-labeled fa fa-save">Lưu</button>
                <button id="btn-reset" type="reset" class="btn btn-default btn-labeled fa fa-refresh">Hủy</button>
            </div>
        </div>
    </form>
</div>
<!--Bootstrap Datepicker [ OPTIONAL ]-->
<link href="/assets/inside/plugins/bootstrap-datetimepicker/css/datetimepicker.css" rel="stylesheet">
<script src="/assets/inside/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js"></script>
<script src="/assets/inside/plugins/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.vi.js"></script>

<!-- CKFinder -->
<script src="/assets/inside/plugins/ckfinder/ckfinder.js"></script>

<script type="text/javascript">

function BrowseServer(name) {
    var config = {};
    config.startupPath = 'Images:/products/';
    var finder = new CKFinder(config);
    finder.selectActionFunction = SetFileField;
    finder.selectActionData = name;
    finder.callback = function (api) {
        api.disableFolderContextMenuOption('Batch', true);
    };
    finder.popup();
}
function SetFileField(fileUrl, data) {
    var file = fileUrl.replace(/\/\//g, '/');
    if (data["selectActionData"] == 'image_name') {
        file = '/thumb.php?src=' + file + '&w=320&h=190';
    }
    $('#' + data["selectActionData"]).val(file);
    $('#' + data["selectActionData"]).parent().find('.preview-file-upload').attr('src', file);
}

$(function () {
    var date = new Date();
    <?php
        $time = time();
    ?>
    $('#file_upload').uploadify({
        'swf'      : '/assets/inside/plugins/uploadify/uploadify.swf',
        'uploader' : '/upload.php?timestamp=<?php echo $time?>&token=<?php echo md5('unique_salt' . $time);?>',
        'buttonText' : '<i class="icon-picture"></i> Menu Icon',
        'onUploadSuccess' : function (file, data) {
            $('#preview-file-upload').attr('src', '/upload/temp/' + data );
            $('input[name=image_name]').val(data);
        },
        'fileSizeLimit': '5MB',
    	'fileTypeDesc': 'Image Files',
        'fileTypeExts': '*.gif; *.jpg; *.jpeg; *.png; *.GIF; *.JPG; *.PNG',
        'multi'    : false
    });
});

</script>
@endsection