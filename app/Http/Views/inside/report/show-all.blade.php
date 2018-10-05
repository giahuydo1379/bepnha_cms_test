@extends("{$moduleName}.layout.master")
@section('content')
    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo $title?></h1>
    </div>

    <ol class="breadcrumb">
        <li class=""><?php echo $title?></li>
        <li class="active">Thống kê lượt xem theo Ngày</li>
    </ol>

    @if (Session::has('tag-group-success-message'))
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Thành công!</h4>
            {{ Session::get('tag-group-success-message') }}
        </div>
    @elseif (Session::has('category-error-message'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
            {{ Session::get('tag-group-error-message') }}
        </div>
    @endif

    <div id="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Thống kê lượt xem theo Ngày</h3>
            </div>
            <div class="panel-body">
                {{--<div class="row">
                    {!! $chart->container() !!}
                </div>
                    {!! $chart->script() !!}--}}
                <div class="row">
                    <div class="col-sm-3">
                        {!! Form::select('style_filter', $style, $filters['style'], [
                           'id' => 'style_filter',
                           'class' => 'custom_filter',
                           'data-placeholder' => 'Lọc theo loại']) !!}
                        {{--{!! Form::select('status_style', $status_style, $filters['style'], [
                                'id' => 'status_style',
                                'class' => 'custom_filter',
                                'data-placeholder' => 'Lọc theo dạng bài']) !!}--}}
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="input-daterange input-group" id="date_from">
                                <span class="input-group-addon">Từ</span>
                                <input type="text" class="form-control" id="from_filter" value="{{ $filters['from'] }}"
                                       placeholder="----- Ngày bắt đầu -----"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <div class="input-daterange input-group" id="date_to">
                                <span class="input-group-addon">Đến</span>
                                <input type="text" class="form-control" id="to_filter" value="{{ $filters['to'] }}"
                                       placeholder="----- Ngày kết thúc -----"/>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <button id="submit-chart" class="btn btn-default" type="button" name="refresh" title="Xem">Xem
                        </button>
                    </div>
                </div>
                <div id="table-toolbar">
                    <a href="#">
                        <button class="btn btn-success" id="downloadId">Download Excel xls</button>
                    </a></div>

                <div class="row">
                    <div class="col-sm-8">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Số lượt xem theo ngày</h3>
                            </div>
                            <div class="pad-all">
                                <div id="demo-morris-line-legend" class="text-center"></div>
                                <div id="demo-morris-line" style="height:268px"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">So sánh số lượt xem công thức nấu ăn và video</h3>
                            </div>
                            <div class="panel-body">
                                <div id="demo-morris-donut" class="morris-donut" style="height: 250px"></div>
                            </div>
                        </div>

                    </div>

                </div>

                <table id="demo-custom-toolbar" class="demo-add-niftycheck" data-toggle="table"
                       data-locale="vi-VN"
                       data-toolbar="#table-toolbar"
                       data-url="{!! url("/{$moduleName}/{$controllerName}/ajax-data") !!}"
                       data-pagination="true"
                       data-side-pagination="server"
                       data-page-size="{{ PAGE_LIST_COUNT }}"
                       data-query-params="queryParams"
                       data-cookie="true"
                       data-cookie-id-table="inside-report-show-all"
                       data-cookie-expire="{!! config('params.bootstrapTable.extension.cookie.cookieExpire') !!}"
                >
                    <thead>
                    <tr>
                        <th data-field="date_view" data-sortable="true">Ngày</th>
                        <th data-field="date_view_count" data-sortable="true">Số lượt xem</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    </div>

    <link rel="stylesheet" type="text/css"
          href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.11.0/bootstrap-table.min.css">

    <style type="text/css">
        .bootstrap-select {
            margin: 0;
        }
    </style>

    <!--Bootstrap Table [ OPTIONAL ]-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.js" charset="utf-8"></script>

    <script src="/assets/inside/plugins/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/inside/plugins/bootstrap-table/locale/bootstrap-table-vi-VN.min.js"></script>
    <script src="/assets/inside/plugins/bootstrap-table/extensions/cookie/bootstrap-table-cookie.js"></script>

    <script src="/assets/inside/plugins/morris-js/morris.min.js"></script>
    <script src="/assets/inside/plugins/morris-js/raphael-js/raphael.min.js"></script>
    <!--Bootstrap Select [ OPTIONAL ]-->
    <link rel="stylesheet" type="text/css"
          href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/css/bootstrap-select.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.10.0/js/bootstrap-select.min.js"></script>

    <!--Bootstrap Datepicker [ OPTIONAL ]-->
    <link rel="stylesheet" type="text/css"
          href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/css/bootstrap-datepicker.min.css">
    <script type="text/javascript"
            src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/js/bootstrap-datepicker.min.js"></script>
    <script type="text/javascript"
            src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.4/locales/bootstrap-datepicker.vi.min.js"
            charset="UTF-8"></script>





    <script type="text/javascript">


        function formatStyle(value, row, index) {
            return value === 2 ?
                'Công thức nấu ăn' :

                'Video';
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


        function queryParams(params) {
            params.language_code = $('#language_filter').val();
            //params.status = $('#status_filter').val();
            params.style = $('#style_filter').val();
            params.from = $('#from_filter').val();
            params.to = $('#to_filter').val();
            return params;
        }

        $(document).ready(function () {


            $('#downloadId').click(function () {
                var statusStyle = $('#style_filter').val();
                var statusFrom = $('#from_filter').val();
                var statusTo = $('#to_filter').val();

                var url = '{!! url("/{$moduleName}/report/export-total-view-by-date") !!}' + '?from=' + statusFrom + '&to=' + statusTo + '&style=' + statusStyle;
                window.location = url;
                // $.get(url, function (data) {
                //
                //     console.log(data);
                // });
            });
            @if (session('msg'))
            notifyMsg('{{ session('msg') }}');
                    @endif

            var $table = $('#demo-custom-toolbar'), $remove = $('#demo-delete-row');
            $table.on('check.bs.table uncheck.bs.table check-all.bs.table uncheck-all.bs.table', function () {
                $remove.prop('disabled', !$table.bootstrapTable('getSelections').length);
            }).on('load-success.bs.table', function () {
                var tooltip = $('.add-tooltip');
                if (tooltip.length) tooltip.tooltip();
            });

            $remove.click(function () {
                var ids = $.map($table.bootstrapTable('getSelections'), function (row) {
                    return row.id
                });
                removeItems(ids);
            });

            // select_filter
            $('.custom_filter').chosen({width: '100%'});
            $('.custom_filter').on('change', function (evt, params) {
                //    $table.bootstrapTable('refresh');
            });

            $('#date_from,#date_to').datepicker({
                format: "dd-mm-yyyy",
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                language: 'vi'
            }).on('changeDate', function (e) {
                //$table.bootstrapTable('refresh');
            });

            $('#reset-page').click(function () {
                document.getElementById('to_filter').value = '';
                document.getElementById('from_filter').value = '';
                //document.getElementById('status_filter').value = 'active';
                document.getElementById('style_filter').value = '';
                //$('#status_filter').trigger('chosen:updated');
                $('#style_filter').trigger('chosen:updated');
                $table.bootstrapTable('refresh');
                return false;
            });

            $('#submit-chart').click(function () {
                var style = $('#style_filter').val();
                var to_filter = $("#to_filter").val();
                var from_fiter = $('#from_filter').val();

                // var style = document.getElementById('style_filter').value;
                // var to_filter = document.getElementById('to_filter').value;
                // var from_fiter = document.getElementById('from_filter').value;


                var url = '{!! url("/{$moduleName}/{$controllerName}/data-line-chart") !!}' + '?style=' + style + '&from='
                    + from_fiter + '&to=' + to_filter;
                //refresh table

                $table.bootstrapTable('refresh');
                $.get(url).done(function (res) {
                    $("#demo-morris-line").empty();
                    Morris.Line({
                        element: 'demo-morris-line',
                        data: res,
                        xkey: 'date',
                        ykeys: ['view'],
                        labels: ['Lượt xem'],
                        gridEnabled: true,
                        gridLineColor: 'rgba(0,0,0,.1)',
                        gridTextColor: '#8f9ea6',
                        gridTextSize: '11px',
                        lineColors: ['#177bbb'],
                        lineWidth: 2,
                        parseTime: false,
                        resize: true,
                        hideHover: 'auto'
                    });

                });

                var url2 = '{!! url("/{$moduleName}/{$controllerName}/data-donut-chart") !!}' + '?style=' + style + '&from='
                    + from_fiter + '&to=' + to_filter;
                $.get(url2).done(function (data) {
                    $("#demo-morris-donut").empty();
                    Morris.Donut({
                        element: 'demo-morris-donut',
                        data: data,
                        colors: [
                            '#ec407a',
                            '#03a9f4',
                        ],
                        resize: true
                    });

                });

                $('.input-order-by').on('click', function () {
                    console.log(123);
                });
            });
        });
    </script>
@endsection