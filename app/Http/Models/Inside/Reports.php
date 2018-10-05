<?php

namespace App\Http\Models\Inside;

use App;
use App\MyCore\Inside\Models\DbTable;
//use App\Http\Requests\Inside\VideosRequest;
use DB;
use Session;
use App\Http\Models\Charts\ReportChart;

class Reports extends DbTable
{

    public $timestamps = false;
    public $primaryKey = 'id';

    public function __construct($options = array())
    {
        parent::__construct($options);

        $this->table = 'log_view';
    }

    /**
     * List Type
     * @param array $filter
     * @return array
     * @author HaLV
     */

    public function getAllReports($filter)
    {
        $scope = [
            DB::raw('DATE_FORMAT(log_view.view_time, "%d-%m-%Y") as date_view'),
            DB::raw('COUNT("view_time") as date_view_count'), 'log_view.style',
        ];

        $sql = self::select($scope);

        if (!empty($keyword = $filter['search'])) {
            $sql->where('log_view.view_time', 'LIKE', '%' . $keyword . '%');
        }

        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.style', '1');
            } else {
                $sql->where('log_view.style', '2');
            }
        }

        if (!empty($filter['viewdate'])) {
            $viewdate = date('Y-m-d', strtotime($filter['viewdate'])) . ' 00:00:00';
            $sql->where('log_view.view_time', '=', $viewdate);
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('log_view.view_time', [$from, $to]);
        } else if (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $sql->whereDate('log_view.view_time', '>=', $from);
        } else if (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereDate('log_view.view_time', '<=', $to);
        }

        //$total = $sql->count();

        $count = $sql
            ->groupBy('view_time')
            ->get()
            ->toArray();
        $total = count($count);

        $data = $sql->get();
        $labels = $data->pluck('date_view');
        $values = $data->pluck('date_view_count');
        $chart = new ReportChart();
        $chart->labels($labels);
        $chart->dataset('Số lượt xem', 'line', $values)->color('#46b9d8')->backgroundColor('transparent');

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data, 'chart' => $chart];

    }


    public function getDetailReport($filter)
    {
        $scope = [
            'log_view.id', 'log_view.vd_id as vd_id', 'log_view.style as style',
            DB::raw('COUNT(vd_id) AS vd_view_count'),
            DB::raw('IF (log_view.style = 1, v.name, d.title) AS post')
        ];

        $sql = self::select($scope)
            ->leftJoin('videos AS v', function ($join) {
                $join->where('log_view.style', '=', 1)
                    ->on('log_view.vd_id', '=', 'v.id');
            })
            ->leftJoin('documents AS d', function ($join) {
                $join->where('log_view.style', '!=', 1)
                    ->on('log_view.vd_id', '=', 'd.id');
            });

        //dd($sql->toSql());

        if (!empty($keyword = $filter['search'])) {
            $sql->where(function ($query) use ($keyword) {
                $query->where('v.name', 'LIKE', '%' . $keyword . '%');
                $query->orWhere('d.title', 'LIKE', '%' . $keyword . '%');
            });
        }

        if (!empty($filter['viewdate'])) {
            $viewdate = date('Y-m-d', strtotime($filter['viewdate'])) . ' 00:00:00';
            $sql->where('log_view.view_time', '=', $viewdate);
        }

        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.style', '1');
            } else {
                $sql->where('log_view.style', '2');
            }
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('log_view.view_time', [$from, $to]);
        } else if (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $sql->whereDate('log_view.view_time', '>=', $from);
        } else if (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereDate('log_view.view_time', '<=', $to);
        }

        $count = $sql
            ->groupBy('style', 'vd_id')
            //->orderBy('log_view.id')
            ->get()
            ->toArray();

        $total = count($count);

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get()
            ->toArray();

        return ['total' => $total, 'data' => $data];
    }

    public function getTagReport($filter)
    {
        $sql_video = DB::table('log_view')
            ->leftJoin('videos', 'log_view.vd_id', '=', 'videos.id')
            ->join('tag_video', 'videos.id', '=', 'tag_video.video_id')
            ->join('tags', 'tag_video.tag_id', '=', 'tags.id')
            ->select('log_view.id as id', 'tags.id as tagid', 'tags.title as title', 'log_view.view_time as viewtime', DB::raw('count(vd_id) as total_view'))
            ->where('log_view.style', '=', 1)
            ->groupBy('tags.id');
        $sql_document = DB::table('log_view')
            ->leftJoin('documents', 'log_view.vd_id', '=', 'documents.id')
            ->join('tag_document', 'documents.id', '=', 'tag_document.document_id')
            ->join('tags', 'tag_document.tag_id', '=', 'tags.id')
            ->select('log_view.id as id', 'tags.id as tagid', 'tags.title as title', 'log_view.view_time as viewtime', DB::raw('count(vd_id) as total_view'))
            ->where('log_view.style', '=', 2)
            ->groupBy('tags.id');

        if (!empty($keyword = $filter['search'])) {
            $sql_video->where('tags.title', 'LIKE', '%' . $keyword . '%');
            $sql_document->where('tags.title', 'LIKE', '%' . $keyword . '%');
        }

        $sql_document->union($sql_video);

        $sql = DB::table(DB::raw("({$sql_document->toSql()}) as sql_document"))
            ->mergeBindings($sql_document);

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('viewtime', [$from, $to]);
            dd($sql->get(array('viewtime')));
        } else if (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $sql->whereDate('viewtime', '>=', $from);
        } else if (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereDate('viewtime', '<=', $to);
        }

        $querySql = $sql_document->toSql();
        $sql = DB::table(DB::raw("($querySql) aaa group by tagid"))->mergeBindings($sql_document);

        $count = $sql
            ->get(array('id', 'tagid', 'title', 'viewtime', DB::raw('sum(total_view) as tag_view')));
        $total = count($count);

        $data = $sql->skip($filter['offset'])
            ->take($filter['limit'])
            ->orderBy($filter['sort'], $filter['order'])
            ->get(array('id', 'tagid', 'title', 'viewtime', DB::raw('sum(total_view) as tag_view')));

        return ['total' => $total, 'data' => $data];
    }

    public function getDataExportTotalViewByDate($filter)
    {

        if ($filter['style'] == 2) {
            $scope = [DB::raw('DATE_FORMAT(log_view.view_time, "%d-%m-%Y") as ngay'), DB::raw('count(*) as luot_xem')];

            $sql = self::select($scope)
                ->where('log_view.style', 2)
                ->leftJoin('documents', 'documents.id', '=', 'log_view.vd_id')
                ->groupBy('log_view.view_time')
                ->orderBy('view_time', 'DESC')->skip(0)->take(50);
        }

        if ($filter['style'] == 1) {
            $scope = [DB::raw('DATE_FORMAT(log_view.view_time, "%d-%m-%Y") as ngay'), DB::raw('count(*) as luot_xem')];

            $sql = self::select($scope)
                ->where('log_view.style', 1)
                ->leftJoin('videos', 'videos.id', '=', 'log_view.vd_id')
                ->groupBy('log_view.view_time')
                ->orderBy('view_time', 'DESC')->skip(0)->take(50);
        }
        if ($filter['style'] == "") {
            $scope = [DB::raw('DATE_FORMAT(log_view.view_time, "%d-%m-%Y") as ngay'), DB::raw('count(*) as luot_xem')];

            $sql = self::select($scope)
                ->groupBy('log_view.view_time')
                ->orderBy('view_time', 'DESC')->skip(0)->take(50);
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('log_view.view_time', [$from, $to]);
        } else if (!empty($filter['from'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $sql->whereDate('log_view.view_time', '>=', $from);
        } else if (!empty($filter['to'])) {
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereDate('log_view.view_time', '<=', $to);
        }

        return $sql->orderBy('view_time')->skip(0)->take(50)
            ->get()
            ->toArray();
    }

    public function getDataChartByFilters($filter = [])
    {
        $scope = [
            DB::raw('DATE_FORMAT(log_view.view_time, "%d-%m-%Y") as date_view'),
            DB::raw('COUNT("view_time") as date_view_count'),
            'log_view.style',
        ];

        $sql = self::select($scope);

        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.style', '1');
            } else {
                $sql->where('log_view.style', '2');
            }
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('log_view.view_time', [$from, $to]);
        } else {
            if (!empty($filter['from'])) {
                $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
                $sql->whereDate('log_view.view_time', '>=', $from);
            } else {
                if (!empty($filter['to'])) {
                    $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
                    $sql->whereDate('log_view.view_time', '<=', $to);
                }
            }
        }

        $data = $sql->groupBy('view_time')
            ->get()
            ->toArray();

        $dataFormat = array();

        foreach ($data as $item) {
            $object = [
                'date' => $item['date_view'],
                'view' => $item['date_view_count'],
            ];
            $dataFormat[] = $object;
        }

        return $dataFormat;
    }

    public function getDataDonutCharts($filter = [])
    {
        $scope = [

            DB::raw('COUNT("view_time") as date_view_count'),
            'log_view.style',
        ];

        $sql = self::select($scope);

        if (!empty($style = $filter['style'])) {
            if ($style == '1') {
                $sql->where('log_view.style', '1');
            } else {
                $sql->where('log_view.style', '2');
            }
        }

        if (!empty($filter['from']) && !empty($filter['to'])) {
            $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
            $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
            $sql->whereBetween('log_view.view_time', [$from, $to]);
        } else {
            if (!empty($filter['from'])) {
                $from = date('Y-m-d', strtotime($filter['from'])) . ' 00:00:00';
                $sql->whereDate('log_view.view_time', '>=', $from);
            } else {
                if (!empty($filter['to'])) {
                    $to = date('Y-m-d', strtotime($filter['to'])) . ' 23:59:59';
                    $sql->whereDate('log_view.view_time', '<=', $to);
                }
            }
        }

        $data = $sql->groupBy('style')
            ->get()
            ->toArray();



        $dataFormat = array();


        foreach ($data as $item) {


            if($item['style'] == 1){
                $object = [
                    'label' => "Video",
                    'value' => $item['date_view_count'],
                ];
            }
            else{
                $object = [
                    'label' => "Công thức nấu ăn",
                    'value' => $item['date_view_count'],
                ];
            };

            $dataFormat[] = $object;
        }

        return $dataFormat;
    }
}
