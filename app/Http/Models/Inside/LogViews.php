<?php

namespace App\Http\Models\Inside;

use App;
use App\MyCore\Inside\Models\DbTable;
use DB;
use Session;
use Illuminate\Http\Request;

class LogViews extends DbTable
{
    public $timestamps = false;
    public $primaryKey = 'id';

    public function __construct($options = array())
    {
        parent::__construct($options);

        $this->table = 'log_view';
    }

    /**
     * List Type.
     *
     * @param array $filter
     *
     * @return array
     *
     * @author HaLV
     */
    public function getAllLogVidDocs($filter)
    {
        if ($filter['style'] == 2) {
            $scope = ['log_view.*', 'documents.title', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 2)
                ->leftJoin('documents', 'documents.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date', 'documents.title');
        }

        if ($filter['style'] == 1) {
            $scope = ['log_view.*', 'videos.name', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 1)
                ->leftJoin('videos', 'videos.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date', 'videos.name');
        }

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('documents.title', 'LIKE', '%'.$keyword.'%');
                $query->orWhere('videos.name', 'LIKE', '%'.$keyword.'%');
            });
        }

        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.type', 1);
            } else {
                $sql->where('log_view.type', 2);
            }
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])).' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])).' 23:59:59';
            $sql->whereBetween('log_view.date', [$from, $to]);
        } elseif (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])).' 00:00:00';
            $sql->whereDate('log_view.date', '>=', $from);
        } elseif (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])).' 23:59:59';
            $sql->whereDate('log_view.date', '<=', $to);
        }
        $total = DB::table(DB::raw("({$sql->toSql()}) as sub"))
            ->mergeBindings($sql->getQuery()) // you need to get underlying Query Builder
            ->count();
        // $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }

    public function getAllLogVidDocs2($filter)
    {
        if ($filter['style'] == 2) {
            $scope = ['log_view.*', 'documents.title', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 2)
                ->leftJoin('documents', 'documents.id', '=', 'log_view.vid_doc_id')
                ->groupBy('documents.title');
        }

        if ($filter['style'] == 1) {
            $scope = ['log_view.*', 'videos.name', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 1)
                ->leftJoin('videos', 'videos.id', '=', 'log_view.vid_doc_id')
                ->groupBy('videos.name');
        }

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('documents.title', 'LIKE', '%'.$keyword.'%');
                $query->orWhere('videos.name', 'LIKE', '%'.$keyword.'%');
            });
        }

        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.type', 1);
            } else {
                $sql->where('log_view.type', 2);
            }
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])).' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])).' 23:59:59';
            $sql->whereBetween('log_view.date', [$from, $to]);
        } elseif (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])).' 00:00:00';
            $sql->whereDate('log_view.date', '>=', $from);
        } elseif (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])).' 23:59:59';
            $sql->whereDate('log_view.date', '<=', $to);
        }

        $total = DB::table(DB::raw("({$sql->toSql()}) as sub"))
            ->mergeBindings($sql->getQuery()) // you need to get underlying Query Builder
            ->count();

        //   $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }





public function getValue($filter)
    {
        if ($filter['style'] == 2) {
            $scope = ['log_view.*', 'documents.title', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 2)
                ->leftJoin('documents', 'documents.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date', 'documents.title');
        }
        
        if ($filter['style'] == 1) {
            $scope = ['log_view.*', 'videos.name', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 1)
                ->leftJoin('videos', 'videos.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date', 'videos.name');
        }

        
        $total = DB::table(DB::raw("({$sql->toSql()}) as sub"))
            ->mergeBindings($sql->getQuery()) // you need to get underlying Query Builder
            ->count();
        // $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }

   

    public function getAllLogVidDocs3($filter)
    {
        if ($filter['style'] == 2) {
            $scope = ['log_view.*', 'documents.title', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 2)
                ->leftJoin('documents', 'documents.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date');
        }

        if ($filter['style'] == 1) {
            $scope = ['log_view.*', 'videos.name', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 1)
                ->leftJoin('videos', 'videos.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date');
        }

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('documents.title', 'LIKE', '%'.$keyword.'%');
                $query->orWhere('videos.name', 'LIKE', '%'.$keyword.'%');
            });
        }

        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.type', 1);
            } else {
                $sql->where('log_view.type', 2);
            }
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])).' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])).' 23:59:59';
            $sql->whereBetween('log_view.date', [$from, $to]);
        } elseif (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])).' 00:00:00';
            $sql->whereDate('log_view.date', '>=', $from);
        } elseif (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])).' 23:59:59';
            $sql->whereDate('log_view.date', '<=', $to);
        }

        $total = DB::table(DB::raw("({$sql->toSql()}) as sub"))
            ->mergeBindings($sql->getQuery()) // you need to get underlying Query Builder
            ->count();

        //   $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }

    /**
     * List Type.
     *
     * @param array $filter
     *
     * @return array
     *
     * @author HaLV
     */
    public function getFeatureddocuments($filter)
    {
        $scope = [
            'documents.*', 'categories.name as category_name',
        ];

        $sql = self::select($scope)
            ->leftJoin('categories', 'categories.id', '=', 'documents.category_id');

        $sql->where('documents.is_featured', 1);

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('documents.title', 'LIKE', '%'.$keyword.'%');
                $query->orWhere('categories.name', 'LIKE', '%'.$keyword.'%');
            });
        }

        if (!empty($category = $filter['category'])) {
            $sql->where('documents.category_id', $category);
        }

        if (!empty($status = $filter['status'])) {
            if ($status == 'active') {
                $sql->where('documents.disable', 0);
            } else {
                $sql->where('documents.disable', 1);
            }
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])).' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])).' 23:59:59';
            $sql->whereBetween('documents.date_created', [$from, $to]);
        }

        $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }

    /**
     * List Type.
     *
     * @param array $filter
     *
     * @return array
     *
     * @author HaLV
     */
    public function getRecipedocuments($filter)
    {
        $scope = [
            'documents.*', 'categories.name as category_name',
        ];

        $sql = self::select($scope)
            ->leftJoin('categories', 'categories.id', '=', 'documents.category_id');

        $sql->where('documents.is_recipe', 1);

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('documents.name', 'LIKE', '%'.$keyword.'%');
                $query->orWhere('categories.name', 'LIKE', '%'.$keyword.'%');
            });
        }

        if (!empty($category = $filter['category'])) {
            $sql->where('documents.category_id', $category);
        }

        if (!empty($status = $filter['status'])) {
            if ($status == 'active') {
                $sql->where('documents.disable', 0);
            } else {
                $sql->where('documents.disable', 1);
            }
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])).' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])).' 23:59:59';
            $sql->whereBetween('documents.date_created', [$from, $to]);
        }

        $total = $sql->count();

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }

    /**
     * List Type.
     *
     * @param array $filter
     *
     * @return array
     *
     * @author HaLV
     */
    public function getAlldocumentsTopView($filter)
    {
        $scope = [
            'documents.*', 'categories.name as category_name',
        ];

        $sql = self::select($scope)
            ->leftJoin('categories', 'categories.id', '=', 'documents.category_id');

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('documents.name', 'LIKE', '%'.$keyword.'%');
                $query->orWhere('categories.name', 'LIKE', '%'.$keyword.'%');
            });
        }

        $sql->where('documents.disable', 0);

        $total = $sql->count();

        $data = $sql->limit(50)
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }

    /**
     * Enter description here ...
     *
     * @param VideoRequest $request
     *
     * @return unknown
     *
     * @author HaLV
     */
    public function add(Request $request)
    {
        $date = date('Y/m/d');
        $folder_image = 'documents';

        /**
         * Lưu trong object.
         */
        $object = new documents();

        $data = $request->all();

        $isNewImage = $data['is_new_image'];
        //$isNewImage1 = $data['is_new_image1'];

        //$data['ingredients'] = $this->remove_empty($data['ingredients']);

        //$data['ingredients'] = json_encode($data['ingredients']);

        $data = $this->_formatDataToSave($data);

        $data['date_created'] = date('Y-m-d H:i:s');
        $data['created_by'] = \Auth::id();

        if (isset($data['is_home']) && $data['is_home'] == 'on') {
            $data['is_home'] = 1;
        } else {
            $data['is_home'] = 0;
        }

        $this->filterColumns($data, $object);

        $isSuccess = false;

        DB::transaction(function () use ($data, $object, &$isSuccess) {
            try {
                $object->save();
                $videoId = $object->{$object->primaryKey};

                if (isset($data['tags'])) {
                    foreach ($data['tags'] as $tagid) {
                        DB::table('tag_document')->insert(['document_id' => $videoId, 'tag_id' => $tagid]);
                    }
                }

                DB::commit();
                Session::flash('video-success-message', 'Thêm thông tin Sổ tay thành công.');
                $isSuccess = $videoId;
            } catch (Exception $ex) {
                DB::rollback();
                Session::flash('video-error-message', 'Thêm thông tin Sổ tay thất bại. Vui lòng thử lại sau.');
            }
        });

        /*
         * Xử lý media
         */
        if ($isSuccess) {
            $dataMedia = array();

            /*
             * upload hinh
             */
            if ($isNewImage) {
                $path = $_ENV['MEDIA_PATH_IMAGE'].'/'.$folder_image.'/'.$date;
                $dataMedia['image_location'] = $folder_image.'/'.$date.'/'.$data['image_name'];
                $this->saveFile($path, $data['image_name']);
            }

            /*
            if ($isNewImage1) {
                $path = $_ENV['MEDIA_PATH_IMAGE'] . '/' . $folder_image . '/' . $date;
                $dataMedia['background_location'] = $folder_image . '/' . $date . '/' . $data['image_name1'];
                $this->saveFile($path, $data['image_name1']);
            }
             */

            /*
             * upload video
             */

            if (count($dataMedia)) {
                documents::where('id', $isSuccess)->update($dataMedia);
            }
        }

        return $isSuccess;
    }

    /**
     * Enter description here ...
     *
     * @param VideoRequest $request
     * @param unknown      $id
     *
     * @return unknown
     *
     * @author HaLV
     */
    public function edit(Request $request, $id)
    {
        /**
         * Lưu trong object.
         */
        $object = $this->findOrNew($id);
        $old_image = $object->image_location;
        $old_video = $object->video_location;

        $date = date('Y/m/d');
        $folder_image = 'documents';

        $data = $request->all();

        /*
        if(!isset($data['is_featured']))
            $data['is_featured'] = 0;

        if(!isset($data['is_like']))
            $data['is_like'] = 0;

        if(!isset($data['is_new']))
            $data['is_new'] = 0;

        if(!isset($data['is_for_you']))
            $data['is_for_you'] = 0;
         */

        $isNewImage = $data['is_new_image'];
        //$isNewImage1 = $data['is_new_image1'];

        if (isset($data['is_home']) && $data['is_home'] == 'on') {
            $data['is_home'] = 1;
        } else {
            $data['is_home'] = 0;
        }
        //$data['ingredients'] = $this->remove_empty($data['ingredients']);

        //$data['ingredients'] = json_encode($data['ingredients']);

        $data = $this->_formatDataToSave($data);

        $data['modified_by'] = \Auth::id();
        $data['date_modified'] = date('Y-m-d H:i:s');

        $tags = isset($data['tags']) ? $data['tags'] : [];

        $this->filterColumns($data, $object);

        $isSuccess = false;

        DB::transaction(function () use ($tags, $object, &$isSuccess) {
            try {
                $object->save();
                $videoId = $object->{$object->primaryKey};

                // Update video tags
                DB::table('tag_video')->where('video_id', '=', $videoId)->delete();
                foreach ($tags as $tagid) {
                    DB::table('tag_video')->insert(['video_id' => $videoId, 'tag_id' => $tagid]);
                }

                DB::commit();
                Session::flash('video-success-message', 'Chỉnh sửa thông tin Sổ tay thành công');
                $isSuccess = $videoId;
            } catch (Exception $ex) {
                DB::rollback();
                Session::flash('video-error-message', 'Chỉnh sửa thông tin Sổ tay thất bại. Vui lòng thử lại sau.');

                return false;
            }
        });

        /*
         * Xử lý media
         */
        if ($isSuccess) {
            $dataMedia = array();

            /*
             * upload hinh
             */
            if ($isNewImage) {
                $path = $_ENV['MEDIA_PATH_IMAGE'].'/'.$folder_image.'/'.$date;
                $dataMedia['image_location'] = $folder_image.'/'.$date.'/'.$data['image_name'];
                $this->delFile($_ENV['MEDIA_PATH_IMAGE'], $old_image);
                $this->saveFile($path, $data['image_name']);
            }

            /*
            if ($isNewImage1) {
                $path = $_ENV['MEDIA_PATH_IMAGE'] . '/' . $folder_image . '/' . $date;
                $dataMedia['background_location'] = $folder_image . '/' . $date . '/' . $data['image_name1'];
                $this->saveFile($path, $data['image_name1']);
            }
             */

            /*
             * upload video
             */

            if (count($dataMedia)) {
                documents::where('id', $isSuccess)->update($dataMedia);
            }
        }

        return $id;
    }

    /**
     * Enter description here ...
     *
     * @param unknown $data
     *
     * @return unknown
     *
     * @author HaLV
     */
    public function getTopVideoByView()
    {
        return documents::where('disable', 0)
            ->orderBy('view_count', 'DESC')->skip(0)->take(10)->get()->toArray();
    }

    /**
     * Enter description here ...
     *
     * @param unknown $data
     *
     * @return unknown
     *
     * @author HaLV
     */
    public function getDataExport()
    {
        return documents::select('id', 'name')->get()->toArray();
    }

    /**
     * Enter description here ...
     *
     * @param unknown $data
     *
     * @return unknown
     *
     * @author HaLV
     */
    public function getDataExportTopVideoByView()
    {
        return documents::select('name', 'duration', 'view_count')->where('disable', 0)
            ->orderBy('view_count', 'DESC')->skip(0)->take(50)->get()->toArray();
    }
}