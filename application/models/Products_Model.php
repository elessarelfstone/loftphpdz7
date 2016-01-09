<?php

class Products_Model extends LOFT_Model
{
    public $table = 'goods';

    public function products_count_by_cat($category_id)
    {
        $result = parent::getAll(array('id_category'=>$category_id));
        return count($result);
    }

    public function products_count()
    {
        $result = count(parent::getAll());
        return $result;
    }

    public function getProductsByCategory($cat, $limit ,$start)
    {
        $this->db->where(array('id_category'=>$cat));
        $this->db->limit($limit, $start);
        $result = $this->db->get($this->table);
        return $result->result_array();
    }

    /**
     *
     * Метод получения информации из БД по товару с ID = $id
     *
     * @author Paintcast
     *
     * @param $id - ID товара для поиска
     * @return mixed - инфа о товаре + brand + category
     */

    public function getProductById($id)
    {
        $this->db->select('goods.*, brand.title AS brand, categoryes.title AS category');
        $this->db->join('brand', 'brand.id = goods.id_brand');
        $this->db->join('categoryes', 'categoryes.id = goods.id_category');
        $this->db->where(array('goods.id'=>$id));
        $result = $this->db->get($this->table);
        return $result->row();
    }



}