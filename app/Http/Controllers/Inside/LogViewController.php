<?php

namespace App\Http\Controllers\Inside;

use App\MyCore\Inside\Routing\MyController;
use App\Http\Models\Inside\LogViews;
use App\Http\Models\Inside\VideoTypes;
use App\Http\Models\Inside\Categories;
use DB;
use Illuminate\Http\Request;
use Excel;
use App\Http\Requests\Inside\DocumentsRequest;

class LogViewController extends MyController
{
    private $_model = null;
    private $_params = array();

    public function __construct()
    {
        $options = array();
        $this->_params = \Request::all();
        $this->data['params'] = $this->_params;
        parent::__construct($options);

        $this->data['title'] = 'Nhật kí';
        $this->data['controllerName'] = 'logviews';
        $this->_model = new LogViews();
    }

    /**
     * Enter description here ...
     *
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     *
     * @author HaLV
     */
    public function getIndex()
    {
        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
    }

    /**
     * Enter description here ...
     *
     * @return \Illuminate\View\View
     *
     * @author HaLV
     */
    public function getShowAll()
    {
        $this->data['filters'] = session('video_filters', [
            'offset' => 0,
            'limit' => PAGE_LIST_COUNT,
            'sort' => 'id',
            'order' => 'asc',
            'search' => '',
            'status' => 'active',
            'from' => '',
            'to' => '',
            'style' => '1',
        ]);

        $this->data['status'] = ['' => '- lọc theo trạng thái -'] + $this->_model->getOptionsStatus();
        $this->data['status_style'] = ['' => '-- lọc theo dạng bài --'] + $this->_model->getOptionsStyle();

        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.show-all", $this->buildDataView($this->data));
    }

    /**
     * Enter description here ...
     *
     * @return \Illuminate\View\View
     *
     * @author HaLV
     */
    public function getListFeatured()
    {
        $this->data['status'] = ['' => ''] + $this->_model->getOptionsStatus();
        $this->data['categories'] = ['' => ''] + Categories::getOptionsCategory();

        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.list-featured", $this->buildDataView($this->data));
    }

    /**
     * Enter description here ...
     *
     * @return \Illuminate\View\View
     *
     * @author HaLV
     */
    public function getListRecipe()
    {
        $this->data['status'] = ['' => ''] + $this->_model->getOptionsStatus();
        $this->data['categories'] = ['' => ''] + Categories::getOptionsCategory();

        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.list-recipe", $this->buildDataView($this->data));
    }

    /**
     * List staffs request.
     *
     * @return JSON
     *
     * @author HaLV
     */


 public function getValue(Request $request)
    {
        
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'id'),
            'order' => $request->input('order', 'asc'),
            'search' => $request->input('search', ''),
            'status' => $request->input('status', ''),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
            'style' => $request->input('style', ''),
            'table'=>$request->input('table','')
        ];
        $data = $this->_model->getValue($filter);
        // $data = $this->_model->getAllLogVidDocs2($filter);
        $arr = [];
        foreach($data['data'] as $v){
            array_push($arr,$v['total']);
        }
        return response()->json([
            'total' => $data['total'],
            'average' => $data['data'], 
            //'average' => $arr,
        ]);
    }


    public function getAjaxData(Request $request)
    {
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'id'),
            'order' => $request->input('order', 'asc'),
            'search' => $request->input('search', ''),
            'status' => $request->input('status', ''),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
            'style' => $request->input('style', ''),
        ];
        session('video_filters', $filter);

        $data = $this->_model->getAllLogVidDocs($filter);
        // $data = $this->_model->getAllLogVidDocs2($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data'],
        ]);
    }

    public function getAjaxDataList(Request $request)
    {
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'id'),
            'order' => $request->input('order', 'asc'),
            'search' => $request->input('search', ''),
            'status' => $request->input('status', ''),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
            'style' => $request->input('style', ''),
        ];
        session('video_filters', $filter);

        $data = $this->_model->getAllLogVidDocs2($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data'],
        ]);
    }

    public function getAjaxDataListDate(Request $request)
    {
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'id'),
            'order' => $request->input('order', 'asc'),
            'search' => $request->input('search', ''),
            'status' => $request->input('status', ''),
            'from' => $request->input('from', ''),
            'to' => $request->input('to', ''),
            'style' => $request->input('style', ''),
        ];
        session('video_filters', $filter);


        $data = $this->_model->getAllLogVidDocs3($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data'],
        ]);



    }


    /**
     * List staffs request.
     *
     * @return JSON
     *
     * @author HaLV
     */



    public function getAjaxDataTopView(Request $request)
    {
        $filter = [
            'offset' => $request->input('offset', 0),
            'limit' => $request->input('limit', PAGE_LIST_COUNT),
            'sort' => $request->input('sort', 'view_count'),
            'order' => $request->input('view_count', 'desc'),
            'search' => $request->input('search', ''),
        ];

        $data = $this->_model->getAllVideosTopView($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data'],
        ]);
    }

    /**
     * Retrieve and return the posts view/comments metrics.
     *
     * @return JSON
     */
    public function getAjaxGetTopVideoByView()
    {
        $dataViews = $this->_model->getTopVideoByView();

        return json_encode($dataViews);
    }

    //Export file excel, csv

    public function getExportExcel($type)
    {
        $data = $this->_model->getDataExport();

        Excel::create('report_video', function ($excel) use ($data) {
            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export($type);
    }

    public function getExportTotalViewByDate(Request $request)
    {

        $filter = [
        'style' => $request->input('style', ''),
    ];



        $data = $this->_model->getDataExportTotalViewByDate($filter);


        Excel::create('report-total-view-by-date', function ($excel) use ($data) {
            $excel->sheet('Sheet 1', function ($sheet) use ($data) {
                $sheet->fromArray($data);
            });
        })->export('xls');
    }
}
