@extends("{$moduleName}.layout.master")
@section('content')
<div id="page-title">
    <h1 class="page-header text-overflow"><?php echo $title?></h1>
</div>

<ol class="breadcrumb">
    <li class=""><?php echo $title?></li>
    <li class="active">Danh sách sổ tay</li>
</ol>

@if (Session::has('recipe-success-message'))
    <div class="alert alert-info alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-check"></i> Thành công!</h4>
        {{ Session::get('recipe-success-message') }}
    </div>
    @elseif (Session::has('recipe-error-message'))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
        {{ Session::get('recipe-error-message') }}
    </div>
@endif

<div id="page-content">
    <div class="panel">
        <div class="panel-heading">
            <h3 class="panel-title">Danh sách sổ tay</h3>
        </div>
        <div class="panel-body">
            <table id="demo-custom-toolbar" class="demo-add-niftycheck" data-toggle="table"
                    data-locale="vi-VN"
                    data-toolbar="#table-toolbar"
                    data-url="{!! url("/{$moduleName}/{$controllerName}/ajax-data") !!}"
                    data-search="true"
                    data-show-refresh="true"
                    data-show-toggle="true"
                    data-show-columns="true"
                    data-pagination="true"
                    data-side-pagination="server"
                    data-page-size="{{ PAGE_LIST_COUNT }}"
                    data-query-params="queryParams"
                    data-cookie="true"
                    data-cookie-id-table="inside-recipe-show-all"
                    data-cookie-expire="{!! config('params.bootstrapTable.extension.cookie.cookieExpire') !!}"
                    >
                <thead>
                    <tr>
                        <th data-field="check_id" data-checkbox="true">ID</th>
                        <th data-field="name" data-sortable="true">Tên sổ tay</th>
                        <th data-field="image_location" data-sortable="true" data-formatter="formatImage">Hình thumnail</th>
                        <th data-field="serial_number" data-sortable="true">Serial TV</th>
                        <th data-field="date_created" data-sortable="true">Ngày tạo</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">

<style type="text/css">
    .bootstrap-select {
        margin: 0;
    }
</style>

<!--Bootstrap Table [ OPTIONAL ]-->
<script src="/assets/inside/plugins/bootstrap-table/bootstrap-table.js"></script>
<script src="/assets/inside/plugins/bootstrap-table/locale/bootstrap-table-vi-VN.min.js"></script>
<script src="/assets/inside/plugins/bootstrap-table/extensions/cookie/bootstrap-table-cookie.js"></script>

<!--Bootstrap Select [ OPTIONAL ]-->
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

<!--Bootstrap Datepicker [ OPTIONAL ]-->
<link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.vi.min.js" charset="UTF-8"></script>

<script type="text/javascript">

    $('#reset-page').click(function (){
    	var url = window.location.href;
    	window.location = url;
    });

    function actionColumn(value, row, index) {
        var editBtn = [
            '<a href="{{ url("/{$moduleName}/{$controllerName}/edit") }}/' + value + '" ',
            'class="add-tooltip btn btn-primary btn-icon icon-lg btn-xs" data-placement="top" data-original-title="Sửa">',
            '<i class="fa fa-pencil"></i></a>'
        ].join('');
        var removeBtn = [
            '<a href="#" onclick="removeItems([' + value + '], event)"',
            'class="add-tooltip btn btn-danger btn-icon icon-lg btn-xs" data-placement="top" data-original-title="Đổi trạng thái">',
            '<i class="fa fa-trash-o"></i></a>'
        ].join('');

        return [editBtn, removeBtn].join(' ');
    }

    function formatImage(value, row, index) {

        var url = '<img src="{!! $_ENV['MEDIA_URL_IMAGE'] !!}/' + value +'" height="100" width="150">';

        return url;
    }

    function formatDisable(value, row, index) {
        return value === 0 ?
            '<span class="label label-sm label-primary">Hoạt động</span>' :
            '<span class="label label-sm label-danger">Không hoạt động</span>';
    }

    function formatGroupName(value, row, index) {
        var colors = {
            7: 'pink',
            8: 'purple'
        };
        var className = colors[row.group] || 'gray';
        return '<span class="label label-sm label-' + className + '">' + value + '</span>'
    }

    function notifyMsg(msg) {
        $.niftyNoty({
            type: 'dark',
            title: 'Thông báo',
            message: msg,
            container: 'floating',
            timer: 5000
        });
    }

    function removeItems(items, e) {
        if (e) e.preventDefault();
        if (confirm('Xác nhận đổi trạng thái?')) {
            var url = '{{ url("/{$moduleName}/{$controllerName}/remove") }}';
            var data = {
                '_token': '{{ csrf_token() }}',
                'ids': items
            };
            $.post(url, data).done(function(data){
                notifyMsg(data.msg);
                $('#demo-custom-toolbar').bootstrapTable('refresh');
                $('#demo-delete-row').prop('disabled', true);
            });
        }
    }

    function queryParams(params) {
        params.language_code = $('#language_filter').val();
        params.status = $('#status_filter').val();
        params.from = $('#from_filter').val();
        params.to = $('#to_filter').val();
        return params;
    }

    $(document).ready(function() {

        @if (session('msg'))
            notifyMsg('{{ session('msg') }}');
        @endif

        var $table = $('#demo-custom-toolbar'), $remove = $('#demo-delete-row');
        $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
            $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
        }).on('load-success.bs.table', function () {
            var tooltip = $('.add-tooltip');
            if (tooltip.length)tooltip.tooltip();
        });

        $remove.click(function () {
            var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
                return row.id
            });
            removeItems(ids);
        });

        // select_filter
        $('.custom_filter').chosen({width:'100%'});
        $('.custom_filter').on('change', function(evt, params) {
            $table.bootstrapTable('refresh');
        });

        $('#daterange').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true,
            language: 'vi'
        }).on('changeDate', function (e) {
            $table.bootstrapTable('refresh');
        });
    });

</script>
@endsection