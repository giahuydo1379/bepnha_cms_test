<?php

namespace App\Http\Controllers\Inside;

use App\MyCore\Inside\Routing\MyController;
use App\Http\Models\Inside\Categories;
use App\Http\Requests\Inside\CategoriesRequest;
use Symfony\Component\HttpKernel\Tests\HttpCache\StoreTest;
use Illuminate\Http\Request;

class CategoriesController extends MyController
{
    private $_model = null;
    private $_params = array();

    public function __construct()
    {
        $options = array();
        $this->_params = \Request::all();
        $this->data['params'] = $this->_params;
        parent::__construct($options);

        $this->data['title'] = 'Quản lý Category';
        $this->data['controllerName'] = 'categories';
        $this->_model = new Categories();
    }

    /**
     * Enter description here ...
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     * @author HaLV
     */
    public function getIndex()
    {
        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
    }

    /**
     * Enter description here ...
     * @return \Illuminate\View\View
     * @author HaLV
     */
    public function getShowAll()
    {
        $this->data['filters'] = [
            'offset' => 0,
            'limit' => PAGE_LIST_COUNT,
            'sort' => 'id',
            'order' => 'asc',
            'search' => '',
            'status' => 'active',
            'style' => '',
            'from' => '',
            'to' => '',
        ];
        $this->data['status_opts'] = ['' => '-- lọc theo trạng thái --'] + $this->_model->getOptionsStatus();
        $this->data['status_style'] = ['' => '-- lọc theo dạng bài --'] + $this->_model->getOptionsStyle();
        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.show-all", $this->buildDataView($this->data));
    }

    /**
     * List staffs request
     * @return JSON
     * @author HaLV
     */
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
        session(['cat_filters' => $filter]);

        $data = $this->_model->getAllCategories($filter);

        return response()->json([
                'total' => $data['total'],
                'rows' => $data['data'],
                ]);
    }

    /**
     * Enter description here ...
     * @return \Illuminate\View\View
     * @author HaLV
     */
    public function getAdd()
    {
        $categories = $this->_model->getParentCategoryOptions();
        $this->data['cat_types'] = [null=>'------','1'=>'Chính','2'=>'Phụ'];
        $this->data['categories'] = array('' => '----- Chọn Parent Category -----') + $categories;
        $this->data['cat_styles'] = [null =>'------','1'=>'Video','2'=>'Công thức nấu ăn'];

        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.add", $this->buildDataView($this->data));
    }

    /**
     * Enter description here ...
     * @param StorageRequest $request
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     * @author HaLV
     */
    public function postAdd(CategoriesRequest $request)
    {
        $last_id = $this->_model->add($request);

        if ($last_id) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }

        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/add");
    }

    /**
     * Enter description here ...
     * @param unknown $id
     * @return \Illuminate\View\View
     * @author HaLV
     */
    public function getEdit($id)
    {
        $object = $this->_model->findOrNew($id)->toArray();

        $categories = $this->_model->getParentCategoryOptions();
        $this->data['cat_types'] = [null=>'------','1'=>'Chính','2'=>'Phụ'];
        $this->data['categories'] = array(null => '----- Chọn Parent Category -----') + $categories;
        $this->data['cat_styles'] = [null =>'------','1'=>'Video','2'=>'Công thức nấu ăn'];
        /*
                if(!empty($object['parent_cate']))
                {
                    $this->data['categories'] = $this->_model->getOptionsCategoryById($object['parent_cate']);
                }
                else
                {
                    $this->data['categories'] = array('' => '----- Chọn Parent Category -----') + $this->_model->getParentCategoryOptions();
                }
        */
        $this->data['object'] = $object;

        return view("{$this->data['moduleName']}.{$this->data['controllerName']}.edit", $this->buildDataView($this->data));
    }

    /**
     * Enter description here ...
     * @param JobLevelsRequest $request
     * @param unknown $id
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     * @author HaLV
     */
    public function postEdit(CategoriesRequest $request, $id)
    {
        if ($this->_model->edit($request, $id)) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all")->withInput(session('cat_filter_opt'));
            //return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }
        return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/edit", $id);
    }

    /**
     * Enter description here ...
     * @param unknown $id
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     * @author HaLV
     */
    public function getRemove($id)
    {
        if ($this->_model->remove($id)) {
            return redirect("/{$this->data['moduleName']}/{$this->data['controllerName']}/show-all");
        }
    }

    /**
     * Enter description here ...
     * @return Ambigous <\Illuminate\Routing\Redirector, \Illuminate\Http\RedirectResponse>
     * @author HaLV
     */
    public function postRemove()
    {
        if (isset($this->_params['ids'])) {
            $this->_model->removeMulti($this->_params['ids']);
            return response()->json(['msg' => 'Đổi trạng thái thành công!']);
        }
        return response()->json(['msg' => 'Đổi trạng thái không thành công!']);
    }

    /**
     * @param $loactionId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAjaxGetCategoryByLanguage($language)
    {
        $data = $this->_model->getOptionsCategoryByLanguage($language);
        return response()->json($data);
    }
}
