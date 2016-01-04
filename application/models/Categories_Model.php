<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_Model extends LOFT_Model
{
    public $table = "categoryes";
    public $title;

    public function __construct()
    {
        parent::__construct();
    }

    public function create($title)
    {
        $this->title = $title;
        return $this->db->insert($this->table, $this);
    }
}