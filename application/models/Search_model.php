<?php

class Search_model extends LOFT_Model
{
    /*
     * получения таблицы $table из бд
     * */
    public function select($table)
   {
       $records = $this->db->get($table);
       return $records->result_array();
   }


    /**
     *
     * Метод получения списка товаров удовлетворяющим условиям поиска
     *
     * @param $array - Массив условий
     * @return mixed - массив найдекных товаров
     *
     */
    public function getProductsFromSearch($array, $limit, $page)
    {
        $this->db->where(array('price>='=>$array["minprice"], 'price<='=>$array["maxprice"]));
        if ($array["query"]!='') {
            $this->db->like('title', $array["query"]);
            $this->db->or_like('description', $array["query"]);
        }

        if($array['category']!=0)
            $this->db->where(array('id_category'=>$array["category"]));
        if($array['brand']!=0)
            $this->db->where(array('id_brand'=>$array["brand"]));


        $this->db->limit($limit, $page);

            $result = $this->db->get('goods');

        return $result->result_array();
    }

    public function getCountProductsFromSearch($array)
    {
        $this->db->where(array('price>='=>$array["minprice"], 'price<='=>$array["maxprice"]));
        if ($array["query"]!='') {
            $this->db->like('title', $array["query"]);
            $this->db->like('description', $array["query"]);
        }

        if($array['category']!=0)
            $this->db->where(array('id_category'=>$array["category"]));
        if($array['brand']!=0)
            $this->db->where(array('id_brand'=>$array["brand"]));
        $result = $this->db->get('goods');
        return count($result->result_array());
    }


}