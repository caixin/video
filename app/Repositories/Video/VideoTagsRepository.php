<?php

namespace App\Repositories\Video;

use App\Repositories\AbstractRepository;
use Models\Video\VideoTags;

class VideoTagsRepository extends AbstractRepository
{
    public function __construct(VideoTags $entity)
    {
        parent::__construct($entity);
    }

    public function _do_search()
    {
        if (isset($this->_search['name'])) {
            $this->db = $this->db->where('name', 'like', '%'.$this->_search['name'].'%');
        }
        if (isset($this->_search['hot'])) {
            $this->db = $this->db->where('hot', '=', 1);
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
        'name' => '标签',
        'hot'  => '是否为热门',
    ];
}
