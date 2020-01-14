<?php

namespace App\Services\User;

use App\Repositories\User\UserRepository;
use Models\User\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->userRepository->setActionLog(true);
    }

    public function list($input)
    {
        $search_params = param_process($input, ['id', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];
        $params_uri    = $search_params['params_uri'];

        $search['status'] = $search['status'] ?? 1;
        $search['created_at1'] = $search['created_at1'] ?? date('Y-m-d', time()-86400*30);
        $search['created_at2'] = $search['created_at2'] ?? date('Y-m-d', time());

        $result = $this->userRepository->search($search)
            ->order($order)->paginate(session('per_page'))
            ->result()->appends($input);
        foreach ($result as $key => $row) {
            $row['shares'] = count($row->share_users);
            $row['referrer_code'] = $this->userRepository->referrerCode($row['id']);
            $login_time = strtotime($row['login_time']);
            $row['no_login_day'] = $login_time < 86400 ? '-':floor((time() - $login_time) / 86400).'天';
            $result[$key] = $row;
        }

        return [
            'result'     => $result,
            'search'     => $search,
            'order'      => $order,
            'params_uri' => $params_uri,
        ];
    }

    public function create($input)
    {
        return [
            'row' => $this->userRepository->getEntity(),
        ];
    }

    public function store($row)
    {
        $row = array_map('strval', $row);
        $row['verify_code'] = randpwd(5, 2);
        $this->userRepository->create($row);
    }

    public function show($id)
    {
        return [
            'row' => $this->userRepository->find($id),
        ];
    }

    public function update($row, $id)
    {
        $row = array_map('strval', $row);
        $this->userRepository->update($row, $id);
    }

    public function export($input)
    {
        $search_params = param_process($input, ['id', 'desc']);
        $order         = $search_params['order'];
        $search        = $search_params['search'];

        $search['status'] = $search['status'] ?? 1;
        $search['created_at1'] = $search['created_at1'] ?? date('Y-m-d', time()-86400*30);
        $search['created_at2'] = $search['created_at2'] ?? date('Y-m-d', time());

        $result = $this->userRepository->search($search)
            ->order($order)
            ->result();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValueByColumnAndRow(1, 1, '编号');
        $sheet->setCellValueByColumnAndRow(2, 1, '用户名称');
        $sheet->setCellValueByColumnAndRow(3, 1, '视频点数');
        $sheet->setCellValueByColumnAndRow(4, 1, '分享数');
        $sheet->setCellValueByColumnAndRow(5, 1, '推荐码');
        $sheet->setCellValueByColumnAndRow(6, 1, '推荐人');
        $sheet->setCellValueByColumnAndRow(7, 1, '登录IP');
        $sheet->setCellValueByColumnAndRow(8, 1, '登录时间');
        $sheet->setCellValueByColumnAndRow(9, 1, '活跃时间');
        $sheet->setCellValueByColumnAndRow(10, 1, '未登入');
        $sheet->setCellValueByColumnAndRow(11, 1, '状态');
        $sheet->setCellValueByColumnAndRow(12, 1, '备注');
        $sheet->setCellValueByColumnAndRow(13, 1, '免费到期');
        $sheet->setCellValueByColumnAndRow(14, 1, '添加日期');

        $r = 1;
        foreach ($result as $row) {
            $r++;
            $login_time = strtotime($row['login_time']);
            $no_login_day = $login_time < 86400 ? '-':floor((time() - $login_time) / 86400).'天';

            $sheet->setCellValueByColumnAndRow(1, $r, $row['id']);
            $sheet->setCellValueExplicit("B$r", $row['username'], \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueByColumnAndRow(3, $r, $row['money']);
            $sheet->setCellValueByColumnAndRow(4, $r, count($row->share_users));
            $sheet->setCellValueByColumnAndRow(5, $r, $this->userRepository->referrerCode($row['id']));
            $sheet->setCellValueExplicit("F$r", $row->referrer_user->username ?? '-', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueByColumnAndRow(7, $r, $row['login_ip']);
            $sheet->setCellValueByColumnAndRow(8, $r, $row['login_time']);
            $sheet->setCellValueByColumnAndRow(9, $r, $row['active_time']);
            $sheet->setCellValueByColumnAndRow(10, $r, $no_login_day);
            $sheet->setCellValueByColumnAndRow(11, $r, User::STATUS[$row['status']]);
            $sheet->setCellValueByColumnAndRow(12, $r, $row['remark']);
            $sheet->setCellValueByColumnAndRow(13, $r, $row['free_time']);
            $sheet->setCellValueByColumnAndRow(14, $r, $row['created_at']);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="user.xlsx"');
        header('Cache-Control: max-age=0');
        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }

    public function addMoney($row, $id)
    {
        $this->userRepository->addMoney($id, $row['type'], $row['money'], $row['description']);
    }
}
