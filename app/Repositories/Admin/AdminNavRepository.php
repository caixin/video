<?php

namespace App\Repositories\Admin;

use App\Repositories\AbstractRepository;
use Models\Admin\AdminNav;

class AdminNavRepository extends AbstractRepository
{
    public function __construct(AdminNav $entity)
    {
        parent::__construct($entity);
    }

    private function _preAction($row)
    {
        $row['path'] = 0;
        if ($row['pid'] > 0) {
            $parent = $this->row($row['pid']);
            $row['path'] = $parent['path'] . '-' . $row['pid'];
        }
        return $row;
    }

    public function create($row)
    {
        $row = $this->_preAction($row);
        return parent::create($row);
    }

    public function update($row, $id=0)
    {
        $row = $this->_preAction($row);
        return parent::update($row, $id);
    }

    public function _do_search()
    {
        if (isset($this->_search['pid'])) {
            $this->db = $this->db->where('pid', '=', $this->_search['pid']);
        }

        if (isset($this->_search['name'])) {
            $this->db = $this->db->where('name', 'like', '%'.$this->_search['name'].'%');
        }

        if (isset($this->_search['route'])) {
            $this->db = $this->db->where('route', 'like', '%'.$this->_search['route'].'%');
        }

        if (isset($this->_search['status'])) {
            $this->db = $this->db->where('status', '=', $this->_search['status']);
        }

        if (isset($this->_search['created_at1'])) {
            $this->db = $this->db->where('created_at', '>=', $this->_search['created_at1'] . ' 00:00:00');
        }
        if (isset($this->_search['created_at2'])) {
            $this->db = $this->db->where('created_at', '<=', $this->_search['created_at2'] . ' 23:59:59');
        }

        return $this;
    }

    /**
     * 取得所有導航資料
     *
     * @return array
     */
    public function allNav()
    {
        $where[] = ['status', '=', 1];
        $where[] = ['route', '<>', ''];
        $result = $this->where($where)->order(['sort', 'asc'])->result()->toArray();
        return array_column($result, null, 'id');
    }

    /**
     * For 操作日誌用
     *
     * @var array
     */
    public static $columnList = [
        'id'     => '流水号',
        'pid'    => '父级ID',
        'icon'   => 'ICON',
        'name'   => '导航名称',
        'route'  => '主路由',
        'route1' => '次路由1',
        'route2' => '次路由2',
        'path'   => '阶层路径',
        'sort'   => '排序',
        'status' => '状态',
    ];
}
