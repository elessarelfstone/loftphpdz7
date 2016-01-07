<?php

class Search_model extends LOFT_Model
{

    public function select($table)
   {

       $records = $this->db->get($table);
       return $records->result_array();
   }

}