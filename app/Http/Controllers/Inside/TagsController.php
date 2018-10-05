<?php

namespace App\Http\Controllers\Inside;

use App\Http\Requests\Inside\TagsRequest;
use App\MyCore\Inside\Routing\MyController;
use App\Http\Models\Inside\Tags;
use Illuminate\Http\Request;

class TagsController extends MyController {

    private $_model = null;
    private $_params = array();

    public function __construct() {
        $options = array();
        $this->_params = \Request::all();
        $this->data['params'] = $this->_params;
        parent::__construct($options);

        $this->data['title'] = 'Quản lý Tag';
        $this->data['controllerName'] = 'tags';
        $this->_model = new Tags();
    }

    public function getShowAll() {
        $this->data['filters'] = session('tag_filters', [
            'offset' => 0,
            'limit' => PAGE_LIST_COUNT,
            'sort' => 'id',
            'order' => 'asc',
            'search' => '',
            'status' => 'active',
            'from' => '',
            'to' => '',
        ]);
        $this->data['status'] = ['' => '-- lọc theo trạng thái --'] + $this->_model->getOptionsStatus();
        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.show-all", $this->buildDataView($this->data));
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
            'to' => $request->input('to', '')
        ];
        session(['tag_filters' => $filter]);

        $data = $this->_model->getAllTags($filter);

        return response()->json([
            'total' => $data['total'],
            'rows' => $data['data']
        ]);
    }
    public function getAdd() {
        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.add", $this->buildDataView($this->data));
    }
    public function postAdd(TagsRequest $request) {

        $last_id = $this->_model->add($request);

        if ($last_id) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }

        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/add");
    }
    public function getEdit($id) {
        $object = $this->_model->findOrNew($id)->toArray();

        $this->data['object'] = $object;

        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.edit", $this->buildDataView($this->data));
    }

    public function postEdit(Request $request, $id) {
        if ($this->_model->edit($request, $id)) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }
        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/edit", $id);
    }

    public function getRemove($id) {
        if ($this->_model->remove($id)) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }
    }

    public function postRemove() {
        if (isset($this->_params['ids'])) {
            $this->_model->removeMulti($this->_params['ids']);
            return response()->json(['msg' => 'Đổi trạng thái thành công!']);
        }
        return response()->json(['msg' => 'Đổi trạng thái không thành công!']);
    }

    public function postUpdateOrder(){
        if (isset($this->_params['id']) && isset($this->_params['order_by'])) {
            $this->_model->updateOrderBy($this->_params['id'], $this->_params['order_by']);
            return response()->json(['msg' => 'Đổi thứ tự thành công!']);
        }
        return response()->json(['msg' => 'Đổi thứ tự không thành công!']);
    }
}