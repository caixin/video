<?php

namespace App\Services\Pmtools;

use App\Repositories\User\UserRepository;
use App\Repositories\Pmtools\DailyRetentionRepository;
use App\Repositories\Pmtools\DailyRetentionUserRepository;
use Models\Pmtools\DailyRetention;
use Models\Pmtools\DailyRetentionUser;

class DailyRetentionService
{
    protected $dailyRetentionRepository;
    protected $dailyRetentionUserRepository;
    protected $userRepository;

    public function __construct(
        DailyRetentionRepository $dailyRetentionRepository,
        DailyRetentionUserRepository $dailyRetentionUserRepository,
        UserRepository $userRepository
    ) {
        $this->dailyRetentionRepository = $dailyRetentionRepository;
        $this->dailyRetentionUserRepository = $dailyRetentionUserRepository;
        $this->userRepository = $userRepository;
    }

    public function list($input)
    {
        $search_params = param_process($input, ['date', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['date'] = $search['date'] ?? date('Y-m-d', time()-86400);

        $result = $this->dailyRetentionRepository
                ->search($search)
                ->order($order)
                ->result()->toArray();
        foreach ($result as $key => $row) {
            $percent = round($row['day_count']/$row['all_count']*100, 2);
            $row['percent'] = $row['all_count'] == 0 ? 0 : $percent;
            $result[$key] = $row;
        }

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function chart($input)
    {
        $search_params = param_process($input, ['date', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['date1'] = $search['date1'] ?? date('Y-m-d', time()-86400*10);
        $search['date2'] = $search['date2'] ?? date('Y-m-d', time()-86400);

        $date = $table = $chart = [];
        for ($i=strtotime($search['date1']);$i<=strtotime($search['date2']);$i+=86400) {
            $date[] = date('Y-m-d', $i);
        }

        for ($i=1;$i<=6;$i++) {
            for ($j=strtotime($search['date1']);$j<=strtotime($search['date2']);$j+=86400) {
                $table[$i][$j] = 0;
            }
        }

        $result = $this->dailyRetentionRepository
                ->search($search)
                ->order($order)
                ->result()->toArray();
        foreach ($result as $row) {
            $table[$row['type']][strtotime($row['date'])] = $row['day_count'];
        }

        foreach ($table as $type => $row) {
            $arr = [];
            foreach ($row as $val) {
                $arr[] = (int)$val;
            }

            $chart[] = [
                'name'  => DailyRetention::TYPE[$type],
                'data'  => $arr,
                'color' => DailyRetention::TYPE_COLOR[$type],
            ];
        }

        return [
            'table'      => $table,
            'chart'      => $chart,
            'date'       => $date,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function analysis($input)
    {
        $search_params = param_process($input, ['date', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['status'] = 1;
        $search['created_at1'] = $search['created_at1'] ?? date('Y-m-d', time()-86400*30);
        $search['created_at2'] = $search['created_at2'] ?? date('Y-m-d', time()-86400);

        $total = $this->userRepository->search($search)->count();

        $result = [];
        for ($i=1;$i<=8;$i++) {
            $row = $this->userRepository->retentionAnalysis($search['created_at1'], $search['created_at2'], $i);
            $row['type'] = $i;
            $row['percent'] = $total == 0 ? 0:round($row['count']/$total*100, 2);
            $result[] = $row;
        }

        return [
            'result'     => $result,
            'total'      => $total,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function user($input)
    {
        $search_params = param_process($input, ['date', 'asc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['date1'] = $search['date1'] ?? date('Y-m-d', time()-86400*6);
        $search['date2'] = $search['date2'] ?? date('Y-m-d', time()-86400);

        $date = $table = $chart = [];
        for ($i=strtotime($search['date1']);$i<=strtotime($search['date2']);$i+=86400) {
            $date[] = date('Y-m-d', $i);
        }

        for ($i=1;$i<=5;$i++) {
            for ($j=strtotime($search['date1']);$j<=strtotime($search['date2']);$j+=86400) {
                $table[$i][$j] = '0%(0/0)';
            }
        }

        // get main data.
        $result = $this->dailyRetentionUserRepository
                ->search($search)
                ->order($order)
                ->result();

        $table2 = $table;
        foreach ($result as $row) {
            $percent = $row['all_count'] == 0 ? 0:round($row['day_count'] / $row['all_count'] * 100, 2);
            $table[$row['type']][strtotime($row['date'])] = $percent."%($row[day_count]/$row[all_count])";
            $table2[$row['type']][strtotime($row['date'])] = $percent;
        }

        foreach ($table2 as $type => $row) {
            $arr = [];
            foreach ($row as $val) {
                $arr[] = (int)$val;
            }

            $chart[] = [
                'name' => DailyRetentionUser::TYPE[$type],
                'data' => $arr,
            ];
        }

        return [
            'table'      => $table,
            'chart'      => $chart,
            'date'       => $date,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }
}
