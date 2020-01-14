<?php

namespace App\Repositories\Video;

use App\Repositories\AbstractRepository;
use Models\Video\Video;

class VideoRepository extends AbstractRepository
{
    public function __construct(Video $entity)
    {
        parent::__construct($entity);
        $this->is_action_log = false;
    }

    public function _do_search()
    {
        if (isset($this->_search['keyword'])) {
            $this->db = $this->db->where('keyword', '=', $this->_search['keyword']);
        }

        if (isset($this->_search['name'])) {
            $this->db = $this->db->where('name', 'like', '%'.$this->_search['name'].'%');
        }

        if (isset($this->_search['actors'])) {
            $this->db = $this->db->whereRaw("FIND_IN_SET('".$this->_search['actors']."', actors)");
            unset($this->_search['actors']);
        }

        if (isset($this->_search['tags'])) {
            $this->db = $this->db->whereRaw("FIND_IN_SET('".$this->_search['tags']."', tags)");
            unset($this->_search['tags']);
        }

        if (isset($this->_search['publish1'])) {
            $this->db = $this->db->where('created_at', '>=', $this->_search['publish1']);
        }
        if (isset($this->_search['publish2'])) {
            $this->db = $this->db->where('created_at', '<=', $this->_search['publish2']);
        }

        if (isset($this->_search['search'])) {
            $this->db = $this->db->where(function ($query) {
                $query->where('name', 'like', '%'.$this->_search['search'].'%')
                    ->orWhereRaw("FIND_IN_SET('".$this->_search['search']."', actors)")
                    ->orWhereRaw("FIND_IN_SET('".$this->_search['search']."', tags)");
            });
        }

        if (isset($this->_search['status'])) {
            $this->db = $this->db->where('status', '=', $this->_search['status']);
        }

        if (isset($this->_search['updated_at1'])) {
            $this->db = $this->db->where('updated_at', '>=', $this->_search['updated_at1']);
        }
        if (isset($this->_search['updated_at2'])) {
            $this->db = $this->db->where('updated_at', '<=', $this->_search['updated_at2']);
        }

        return $this;
    }
}
