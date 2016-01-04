<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
 * Class LOFT_Model - Базовая модель для всех моделей
 */

class LOFT_Model extends CI_Model
{
    public $table;

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll($condition = array(), $limit = 0)
    {
        $this->db->where($condition);
        if($limit){
            $records = $this->db->get($this->table, $limit);
        } else {
            $records = $this->db->get($this->table);
        }
        return $records->result_array();
    }

    public function get($condition)
    {
        $this->db->where($condition);
        $result = $this->db->get($this->table);
        return $result->row_array();
    }

    public function insert($data){
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($condition, $data){
        $this->db->update($this->table, $data, $condition);
        return $this->db->affected_rows();
    }

    public function delete($condition){
        $this->db->delete($this->table, $condition);
        return $this->db->affected_rows();
    }

    public function getSum($field,$condition){
        $this->db->select_sum($field);
        $this->db->where($condition);
        $result = $this->db->get($this->table);
        return $result->row_array();
    }

}