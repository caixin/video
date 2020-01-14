<?php

namespace App\Services\Pmtools;

use App\Repositories\Pmtools\ConcurrentUserRepository;

class ConcurrentUserService
{
    protected $concurrentUserRepository;

    public function __construct(ConcurrentUserRepository $concurrentUserRepository)
    {
        $this->concurrentUserRepository = $concurrentUserRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['minute_time', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['per'] = $search['per'] ?? 1;
        $search['minute_time2'] = $search['minute_time2'] ?? date('Y-m-d H:i:00', time()-60);
        $search['minute_time1'] = $search['minute_time1'] ?? date('Y-m-d H:i:00', time()-1800);

        //預設值
        $table = $chart_data = $chart = [];
        for ($i=strtotime($search['minute_time1']);$i<=strtotime($search['minute_time2']);$i+=60*$search['per']) {
            $minute = date('i', $i);
            $mod = $minute % $search['per'];
            $i += $mod == 0 ? 0:($search['per'] - $mod) * 60;
            $chart_data[$i] = 0;
        }

        $count = $this->concurrentUserRepository
            ->select(['minute_time','count'])
            ->search($search)
            ->order($order)
            ->count();
        if ($count <= 200) {
            $result = $this->concurrentUserRepository
                ->select(['minute_time','count'])
                ->search($search)
                ->order($order)
                ->result();
            //填入人數
            foreach ($result as $key => $row) {
                $chart_data[strtotime($row['minute_time'])] = $row['count'];
            }
            //轉換格式
            foreach ($chart_data as $key => $val) {
                $chart[] = [date('m-d H:i', $key),(int)$val];
                $table[] = [
                    'time' 	=> date('m-d H:i', $key),
                    'count'	=> $val
                ];
            }
            krsort($table);
        }

        return [
            'count'      => $count,
            'table'      => $table,
            'chart'      => $chart,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }
}
