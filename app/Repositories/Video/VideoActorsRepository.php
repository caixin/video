<?php

namespace App\Repositories\Video;

use App\Repositories\AbstractRepository;
use Models\Video\VideoActors;

class VideoActorsRepository extends AbstractRepository
{
    public function __construct(VideoActors $entity)
    {
        parent::__construct($entity);
    }

    public function _do_search()
    {
        if (isset($this->_search['name'])) {
            $this->db = $this->db->where('name', 'like', '%'.$this->_search['name'].'%');
        }

        return $this;
    }

    public function getAllName()
    {
        $result = $this->result();
        $data = [];
        foreach ($result as $row) {
            $data[] = $row['name'];
        }
        return $data;
    }

    /**
     * For 操作日誌用
     *
     * @var array
     */
    public static $columnList = [
        'id'   => 'ID',
        'name' => '女优',
        'hot'  => '是否为热门',
    ];
}
