<?php

namespace App\Services\Pmtools;

use App\Repositories\Pmtools\DailyAnalysisRepository;

class DailyAnalysisService
{
    protected $dailyAnalysisRepository;

    public function __construct(DailyAnalysisRepository $dailyAnalysisRepository)
    {
        $this->dailyAnalysisRepository = $dailyAnalysisRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['date', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['type'] = $search['type'] ?? 1;
        $search['date1'] = $search['date1'] ?? date('Y-m-d', time()-86400*30);
        $search['date2'] = $search['date2'] ?? date('Y-m-d', time()-86400);

        //預設值
        $table = $chart = [];
        for ($i=strtotime($search['date1']);$i<=strtotime($search['date2']);$i+=86400) {
            $table[$i] = 0;
        }

        $result = $this->dailyAnalysisRepository
            ->select(['date','count'])
            ->search($search)
            ->order($order)
            ->result();
        foreach ($result as $key => $row) {
            $table[strtotime($row['date'])] = $row['count'];
        }
        
        foreach ($table as $key => $val) {
            $chart[] = [date('Y-m-d', $key),(int)$val];
        }
        krsort($table);

        return [
            'table'      => $table,
            'chart'      => $chart,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }
}
