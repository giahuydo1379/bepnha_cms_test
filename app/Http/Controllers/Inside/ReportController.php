<?php

namespace App\Http\Controllers\Inside;

use App\Http\Models\Inside\Reports;
use App\MyCore\Inside\Routing\MyController;
use Illuminate\Http\Request;
use Excel;

class ReportController extends MyController
{

    private $_model = null;
    private $_params = array();

    public function __construct()
    {
        $options = array();
        $this->_params = \Request::all();
        $this->data['params'] = $this->_params;
        parent::__construct($options);

        $this->data['title'] = 'Báo cáo thống kê';
        $this->data['controllerName'] = 'report';
        $this->_model = new Reports();
    }

    public function getIndex()
    {
        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
    }

    public function getShowAll()
    {
        $this->data['filters'] = [
            //$this->data['filters'] = session('report_filters', [
            'offset' => 0,
            'limit' => PAGE_LIST_COUNT,
            'sort' => 'id',
            'order' => 'asc',
            'search' => '',
            'status' => 'active',
            'style' => '',
            'from' => '',
            'to' => '',
            'viewdate' => ''
        ];
        $this->data['chart'] = $this->_model->getAllReports($this->data['filters'])['chart'];
        $this->data['style'] = ['' => '-- lọc theo loại --'] + $this->_model->getOptionsStyle();
        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.show-all", $this->buildDataView($this->data));
    }

    public function getShowDetail()
    {
        $this->data['style'] = ['' => '-- lọc theo loại --'] + $this->_model->getOptionsStyle();
        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.show-detail", $this->buildDataView($this->data));
    }

    public function getShowTag()
    {
        $this->data['filters'] = [
            //$this->data['filters'] = session('report_filters', [
            'offset' => 0,
            'limit' => PAGE_LIST_COUNT,
            'sort' => 'id',
            'order' => 'asc',
            'search' => '',
            'status' => 'active',
            'style' => '',
            'from' => '',
            'to' => '',
            'viewdate' => ''
        ];
        $this->data['style'] = ['' => '-- lọc theo loại --'] + $this->_model->getOptionsStyle();
        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.show-tag", $this->buildDataView($this->data));
    }

    public function getAjaxData(Request $request)
    {
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'id'),
            'order' => $request->input('order', 'asc'),
            'search' => $request->input('search', ''),
            'status' => $request->input('status', 'active'),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
            'style' => $request->input('style', '')
        ];
        session(['report_filters' => $filter]);

        $data = $this->_model->getAllReports($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data']
        ]);
    }

    public function getAjaxDataDetail(Request $request)
    {
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'id'),
            'order' => $request->input('order', 'asc'),
            'search' => $request->input('search', ''),
            'status' => $request->input('status', 'active'),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
            'style' => $request->input('style', ''),
            'viewdate' => $request->input('viewdate', ''),

        ];
        //session(['report_filters' => $filter]);

        $data = $this->_model->getDetailReport($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data']
        ]);
    }

    public function getAjaxDataTag(Request $request)
    {
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'id'),
            'order' => $request->input('order', 'asc'),
            'search' => $request->input('search', ''),
            'status' => $request->input('status', 'active'),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
            'style' => $request->input('style', ''),
            'viewdate' => $request->input('viewdate', ''),

        ];
        //session(['report_filters' => $filter]);

        $data = $this->_model->getTagReport($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data']
        ]);
    }

    public function getAdd()
    {
        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.add", $this->buildDataView($this->data));
    }

    public function postAdd(TagsRequest $request)
    {

        $last_id = $this->_model->add($request);

        if ($last_id) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }

        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/add");
    }

    public function getEdit($id)
    {
        $object = $this->_model->findOrNew($id)->toArray();

        $this->data['object'] = $object;

        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.edit", $this->buildDataView($this->data));
    }

    public function postEdit(Request $request, $id)
    {
        if ($this->_model->edit($request, $id)) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }
        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/edit", $id);
    }

    public function getRemove($id)
    {
        if ($this->_model->remove($id)) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }
    }

    public function postRemove()
    {
        if (isset($this->_params['ids'])) {
            $this->_model->removeMulti($this->_params['ids']);
            return response()->json(['msg' => 'Đổi trạng thái thành công!']);
        }
        return response()->json(['msg' => 'Đổi trạng thái không thành công!']);
    }

    public function postUpdateOrder()
    {
        if (isset($this->_params['id']) && isset($this->_params['order_by'])) {
            $this->_model->updateOrderBy($this->_params['id'], $this->_params['order_by']);
            return response()->json(['msg' => 'Đổi thứ tự thành công!']);
        }
        return response()->json(['msg' => 'Đổi thứ tự không thành công!']);
    }


    public function getExportTotalViewByDate(Request $request)
    {

        $filter = [
            'style' => $request->input('style', ''),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
        ];

        $data = $this->_model->getDataExportTotalViewByDate($filter);

        return Excel::create('report-total-view-by-date', function ($excel) use ($data) {
            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->download('xls');
    }

    public function getDataLineChart(Request $request) {
        $filters = $request->all();

        $data = $this->_model->getDataChartByFilters($filters);

        return response()->json($data);

    }
    public function getDataDonutChart(Request $request) {
        $filters = $request->all();

        $data = $this->_model->getDataDonutCharts($filters);

        return response()->json($data);

    }
}