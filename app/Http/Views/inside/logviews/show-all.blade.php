@extends("{$moduleName}.layout.master")
@section('content')

    <div id="page-title">
        <h1 class="page-header text-overflow"><?php echo $title?></h1>
    </div>

    <ol class="breadcrumb">
        <li class=""><?php echo $title?></li>
        <li class="active">Nhật kí</li>
    </ol>

    @if (Session::has('video-success-message'))
        <div class="alert alert-info alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-check"></i> Thành công!</h4>
            {{ Session::get('video-success-message') }}
        </div>
    @elseif (Session::has('video-error-message'))
        <div class="alert alert-danger alert-dismissable">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h4><i class="icon fa fa-ban"></i> Lỗi!</h4>
            {{ Session::get('video-error-message') }}
        </div>
    @endif


    <div id="page-content">
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">Thống kê lượt xem</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="row">
                        <div class="col-sm-3">
                            {!! Form::select('status_style', $status_style, $filters['style'], [
                                'id' => 'status_style',
                                'class' => 'custom_filter',
                                'data-placeholder' => 'Lọc theo dạng bài']) !!}
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <div class="input-daterange input-group" id="date_from">
                                    <span class="input-group-addon">Từ</span>
                                    <input type="text" class="form-control" id="from_filter"
                                           value="{{ $filters['from'] }}" placeholder="----- Ngày bắt đầu -----"/>
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
                            <button id="reset-page" class="btn btn-default" type="button" name="refresh" title="Reset">
                                Làm lại
                            </button>
                        </div>
                    </div>
                </div>
                <div id="table-toolbar1">

                    <a href="#" data-toggle="modal" data-target="#modChart" data-source="" data-target-source="34"
                        class="btn btn-pink" > Show chart
                    </a>

                    <a href="{!! url("/{$moduleName}/logviews/export-total-view-by-date/xls") !!}"><button class="btn btn-success">Download Excel xls</button></a>

                </div>
                <div class="col-sm-6">
                    <table id="demo-custom-toolbar1" class="demo-add-niftycheck" data-toggle="table"
                           data-locale="vi-VN"
                           data-toolbar="#table-toolbar1"
                           data-url="{!! url("/{$moduleName}/{$controllerName}/ajax-data-list-date") !!}"
                           data-search="true"
                           data-show-refresh="true"
                           data-show-toggle="true"
                           data-show-columns="true"
                           data-pagination="true"
                           data-side-pagination="server"
                           data-page-size="{{ PAGE_LIST_COUNT }}"
                           data-query-params="queryParams"
                           data-cookie="true"
                           data-cookie-id-table="inside-video-show-all"
                           data-cookie-expire="{!! config('params.bootstrapTable.extension.cookie.cookieExpire') !!}"
                    >
                        <thead>
                        <tr>
                            <th data-field="check_id" data-checkbox="true">ID</th>


                            <th data-field="date" data-sortable="true">Ngày</th>

                            <th data-field="total" data-sortable="true"> Tổng lượt xem</th>
                            {{--<th data-field="vid_doc_id" data-align="center" data-formatter="detail">Xem chi tiết</th>--}}


                        </tr>
                        </thead>

                    </table>
                </div>

                <div id="table-toolbar2">

                   <a href="#" data-toggle="modal" data-target="#modChart" data-source="70,13,20,90,44,12,30,30,30,10,5,0" data-target-source="34"
                        class="btn btn-pink" > Show chart 2
                    </a>
                    <a href="{!! url("/{$moduleName}/videos/export-excel-top-view/xls") !!}"><button class="btn btn-success">Download Excel xls</button></a>
                </div>
                <div class="col-sm-6">
                    <table id="demo-custom-toolbar2" class="demo-add-niftycheck" data-toggle="table"
                           data-locale="vi-VN"
                           data-toolbar="#table-toolbar2"
                           data-url="{!! url("/{$moduleName}/{$controllerName}/ajax-data-list") !!}"
                           data-search="true"
                           data-show-refresh="true"
                           data-show-toggle="true"
                           data-show-columns="true"
                           data-pagination="true"
                           data-side-pagination="server"
                           data-page-size="{{ PAGE_LIST_COUNT }}"
                           data-query-params="queryParams"
                           data-cookie="true"
                           data-cookie-id-table="inside-video-show-all"
                           data-cookie-expire="{!! config('params.bootstrapTable.extension.cookie.cookieExpire') !!}"
                    >
                        <thead>
                        <tr>
                            <th data-field="check_id" data-checkbox="true">ID</th>


                            <th data-field="title" data-sortable="true">Tên Công thức nấu ăn</th>

                            <th data-field="name" data-sortable="true">Tên Video</th>

                            <th data-field="total" data-sortable="true"> Tổng lượt xem</th>
                            {{--<th data-field="vid_doc_id" data-align="center" data-formatter="detail">Xem chi tiết</th>--}}


                        </tr>
                        </thead>

                    </table>
                </div>


                <div id="table-toolbar3">

                   <a href="#" data-toggle="modal" data-target="#modChart" data-source="70,13,20,90,44,12,30,30,30,10,5,0" data-target-source="34"
                        class="btn btn-pink" > Show chart 3
                    </a>
                    <a href="{!! url("/{$moduleName}/videos/export-excel-top-view/xls") !!}"><button class="btn btn-success">Download Excel xls</button></a>
                </div>
                <div class="row">
                    <table id="demo-custom-toolbar3" class="demo-add-niftycheck" data-toggle="table"
                           data-locale="vi-VN"
                           data-toolbar="#table-toolbar3"
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
                           data-cookie-id-table="inside-video-show-all"
                           data-cookie-expire="{!! config('params.bootstrapTable.extension.cookie.cookieExpire') !!}"
                    >
                        <thead>
                        <tr>
                            <th data-field="check_id" data-checkbox="true">ID</th>
                            <th data-field="date" data-sortable="true">Ngày</th>

                            <th data-field="title" data-sortable="true">Tên Công thức nấu ăn</th>

                            <th data-field="name" data-sortable="true">Tên Video</th>

                            <th data-field="total" data-sortable="true">Lượt xem</th>


                        </tr>

                        </thead>

                    </table>
                </div>


            </div>
        </div>
    </div>



 <div class="modal fade" id="modChart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="exampleModalLabel">Linechart</h4>
                    </div>
                    <div class="modal-body">
                        <canvas id="canvas" width="568" height="300"></canvas>
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

       <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.1/Chart.min.js"></script>

    <!--Bootstrap Table [ OPTIONAL ]-->
    <script src="/assets/inside/plugins/bootstrap-table/bootstrap-table.js"></script>
    <script src="/assets/inside/plugins/bootstrap-table/locale/bootstrap-table-vi-VN.min.js"></script>
    <script src="/assets/inside/plugins/bootstrap-table/extensions/cookie/bootstrap-table-cookie.js"></script>

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


            $('#modChart').on('shown.bs.modal',function(event){
                var link = $(event.relatedTarget);
               // link.data('source',"70,13,20,90,44,12,30,30,30,10,5,0");
                // get data source
                var source = link.attr('data-source').split(',');
                // get title
                var title = link.text();
                // get labels
                //var table = $('#demo-custom-toolbar1');
                var table = link.closest('.bootstrap-table').find('table.table');
               
              //  console.log(table);
               
                var labels = [];
                $('#'+table.attr('id')+'>thead>tr>th').each(function(index,value){
                    // without first column
                    if(index>0){labels.push($(value).text());}
                });
                // get target source
                var target = [];
                $.each(labels, function(index,value){
                    target.push(link.attr('data-target-source'));
                });
                // Chart initialisieren
                var modal = $(this);
                var canvas = modal.find('.modal-body canvas');
                modal.find('.modal-title').html(title);
                var ctx = canvas[0].getContext("2d");
                var chart = new Chart(ctx).Line({        
                    responsive: true,
                    labels: labels,
                    datasets: [{
                        fillColor: "rgba(151,187,205,0.2)",
                        strokeColor: "rgba(151,187,205,1)",
                        pointColor: "rgba(151,187,205,1)",
                        pointStrokeColor: "#fff",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "rgba(151,187,205,1)",
                        data: source
                    },{
                        fillColor: "rgba(220,220,220,0.2)",
                        strokeColor: "#F7464A",
                        pointColor: "#FF5A5E",
                        pointStrokeColor: "#FF5A5E",
                        pointHighlightFill: "#fff",
                        pointHighlightStroke: "red",
                        data: target
                    }]
                },{});
            }).on('hidden.bs.modal',function(event){
                // reset canvas size
                var modal = $(this);
                var canvas = modal.find('.modal-body canvas');
                canvas.attr('width','568px').attr('height','300px');
                // destroy modal
                $(this).data('bs.modal', null);
            });




        function detail(value, row, index, field) {


            var url = "{{ url('/fdrive/server/detail/') }}";
            var statusBtn = [
                '<a class="btn btn-success btn-labeled" href="' + url + '/' + value + '"><i class="btn-label fa fa-th-large"></i>' + 'Detail' + '</a>'
            ].join('');


            return [statusBtn].join('');
        }


        function formatRecipe(value, row, index) {
            return value === 1 ?
                '<span class="label label-sm label-success">Công thức nấu ăn</span>' :
                '<span class="label label-sm label-danger">Không công thức nấu ăn </span>';
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
                type: 'success',
                title: 'Thông báo',
                message: msg,
                container: 'floating',
                timer: 5000
            });
        }


        function queryParams(params) {
            params.status = $('#status_filter').val();
            params.from = $('#from_filter').val();
            params.to = $('#to_filter').val();
            params.pcategory = $('#pcategory_filter').val();
            params.category = $('#category_filter').val();
            params.featured = $('#featured_filter').val();
            params.is_new = $('#new_filter').val();
            params.is_like = $('#like_filter').val();
            params.is_for_you = $('#for_you_filter').val();
            params.style = $('#status_style').val();
            return params;
        }

        $(document).ready(function () {


            var $table1 = $('#demo-custom-toolbar1');
            var $table2 = $('#demo-custom-toolbar2');
            var $table3 = $('#demo-custom-toolbar3');


            // select_filter
            $('.custom_filter').chosen({width: '100%'});
            $('.custom_filter').on('change', function (evt, params) {
                $table1.bootstrapTable('refresh');
                $table2.bootstrapTable('refresh');
                $table3.bootstrapTable('refresh');
            });

            $('#date_from,#date_to').datepicker({
                format: "dd-mm-yyyy",
                todayBtn: "linked",
                autoclose: true,
                todayHighlight: true,
                language: 'vi'
            }).on('changeDate', function (e) {
                $table1.bootstrapTable('refresh');
                $table2.bootstrapTable('refresh');
                $table3.bootstrapTable('refresh');
            });

            $('#reset-page').click(function () {
                document.getElementById('to_filter').value = '';
                document.getElementById('from_filter').value = '';
                document.getElementById('status_filter').value = 'active';
                $('#status_filter').trigger('chosen:updated');
                $('#status_style').trigger('chosen:updated');
                document.getElementById('status_style').value = '1';
                $table1.bootstrapTable('refresh');
                $table2.bootstrapTable('refresh');
                $table3.bootstrapTable('refresh');
                return false;
            });
        });

    </script>


@endsection