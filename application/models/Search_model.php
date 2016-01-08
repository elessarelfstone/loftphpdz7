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
    public function getProductsFromSearch($array)
    {
        $this->db->where(array('id_category'=>$array["category"], 'id_brand'=>$array["brand"], 'price>='=>$array["minprice"], 'price<='=>$array["maxprice"]));
        $result = $this->db->get('goods');
        return $result->result_array();
    }


}