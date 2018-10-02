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
                $query->where('documents.title', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('videos.name', 'LIKE', '%' . $keyword . '%');
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
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('log_view.date', [$from, $to]);
        } elseif (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $sql->whereDate('log_view.date', '>=', $from);
        } elseif (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereDate('log_view.date', '<=', $to);
        }
        $total = DB::table(DB::raw("({$sql->toSql()}) as sub"))
            ->mergeBindings($sql->getQuery())// you need to get underlying Query Builder
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
                $query->where('documents.title', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('videos.name', 'LIKE', '%' . $keyword . '%');
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
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('log_view.date', [$from, $to]);
        } elseif (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $sql->whereDate('log_view.date', '>=', $from);
        } elseif (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereDate('log_view.date', '<=', $to);
        }

        $total = DB::table(DB::raw("({$sql->toSql()}) as sub"))
            ->mergeBindings($sql->getQuery())// you need to get underlying Query Builder
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
            ->mergeBindings($sql->getQuery())// you need to get underlying Query Builder
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
                $query->where('documents.title', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('videos.name', 'LIKE', '%' . $keyword . '%');
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
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('log_view.date', [$from, $to]);
        } elseif (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $sql->whereDate('log_view.date', '>=', $from);
        } elseif (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereDate('log_view.date', '<=', $to);
        }

        $total = DB::table(DB::raw("({$sql->toSql()}) as sub"))
            ->mergeBindings($sql->getQuery())// you need to get underlying Query Builder
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


    public function getAlldocumentsTopView($filter)
    {
        $scope = [
            'documents.*', 'categories.name as category_name',
        ];

        $sql = self::select($scope)
            ->leftJoin('categories', 'categories.id', '=', 'documents.category_id');

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('documents.name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('categories.name', 'LIKE', '%' . $keyword . '%');
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

    public function getDataExportTotalViewByDate($filter)
    {

        if ($filter['style'] == 2) {
            $scope = ['log_view.date', DB::raw('count(*) as total')];

           $sql = self::select($scope)
                ->where('log_view.type', 2)
                ->leftJoin('documents', 'documents.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date')
                ->orderBy('date', 'DESC')->skip(0)->take(50)->get();
        }

        if ($filter['style'] == 1) {
            $scope = ['log_view.date',  DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 1)
                ->leftJoin('videos', 'videos.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date')
                ->orderBy('date', 'DESC')->skip(0)->take(50)->get();
        }
        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.type', 1);
            } else {
                $sql->where('log_view.type', 2);
            }
        }

        return $sql->toArray();
    }

    public function getDataExportTotalViewByItem($filter)
    {
       if ($filter['style']==2){
           $scope = ['documents.title', DB::raw('count(*) as total')];
           $sql = self::select($scope)
               ->where('log_view.type', 2)
               ->leftJoin('documents', 'documents.id', '=' , 'log_view.vid_doc_id')
               ->groupBy('documents.title')
               ->orderBy('documents.title', 'asc')->skip(0)->take(50)->get();
       }

       if ($filter['style'] == 1){
           $scope = ['videos.name', DB::raw('count(*) as total')];
           $sql = self::select($scope)
               ->where('log_view.type', 1)
               ->leftJoin('videos', 'videos.id', '=', 'log_view.vid_doc_id')
               ->groupBy('videos.name')
               ->orderBy('videos.name', 'asc')->skip(0)->take(50)->get();
       }
        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.type', 1);
            } else {
                $sql->where('log_view.type', 2);
            }
        }
       return $sql->toArray();
    }

    public function getDataExportTotalViewByDateItem($filter)
    {
        if ($filter['style'] == 2) {
            $scope = ['log_view.date', 'documents.title', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 2)
                ->leftJoin('documents', 'documents.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date', 'documents.title')
                ->orderBy('log_view.date', 'asc')->skip(0)->take(50)->get();
        }

        if ($filter['style'] == 1) {
            $scope = ['log_view.date', 'videos.name', DB::raw('count(*) as total')];

            $sql = self::select($scope)
                ->where('log_view.type', 1)
                ->leftJoin('videos', 'videos.id', '=', 'log_view.vid_doc_id')
                ->groupBy('log_view.date', 'videos.name')
                ->orderBy('log_view.date', 'asc')->skip(0)->take(50)->get();
        }
        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.type', 1);
            } else {
                $sql->where('log_view.type', 2);
            }
        }
        return $sql->toArray();
    }

}
