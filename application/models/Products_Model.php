<?php

class Products_Model extends LOFT_Model
{
    public $table = 'goods';

    public function products_count_by_cat($category_id)
    {
        $result = parent::getAll(array('id_category' => $category_id));
        return count($result);
    }

    public function products_count()
    {
        $result = count(parent::getAll());
        return $result;
    }

    public function getProductsByCategory($cat, $limit, $start)
    {
        $this->db->where(array('id_category' => $cat));
        $this->db->limit($limit, $start);
        $result = $this->db->get($this->table);
        return $result->result_array();
    }


    public function getAllProducts($page, $limit, $cat = NULL, $brand = NULL)
    {
        $this->db->select('goods.id, goods.title as product_title, goods.cnt, goods.price, goods.description, categoryes.title as cat_title, brand.title as brand_title');
        $this->db->from('goods');
        $this->db->join('categoryes', 'goods.id_category = categoryes.id');
        $this->db->join('brand', 'brand.id = goods.id_brand');
        $this->db->limit($limit, $page);
        if ($cat)
            $this->db->where(array('categoryes.id' => $cat));
        if ($brand)
            $this->db->where(array('brand.id' => $brand));
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getCntAllProducts($cat, $brand)
    {
        $this->db->select('goods.id, goods.title as product_title, goods.cnt, goods.price, goods.description, categoryes.title as cat_title, brand.title as brand_title');
        $this->db->from('goods');
        $this->db->join('categoryes', 'goods.id_category = categoryes.id');
        $this->db->join('brand', 'brand.id = goods.id_brand');
        if ($cat)
            $this->db->where(array('categoryes.id' => $cat));
        if ($brand)
            $this->db->where(array('brand.id' => $brand));
        $result = $this->db->get();
        return count($result->result_array());
    }

    public function getProductById2($id)
    {
        $this->db->select('goods.id, goods.title as product_title, goods.cnt, goods.price, goods.description, categoryes.title as cat_title, brand.title as brand_title, brand.id as brand_id, categoryes.id as cat_id');
        $this->db->from('goods');
        $this->db->join('categoryes', 'goods.id_category = categoryes.id');
        $this->db->join('brand', 'brand.id = goods.id_brand');
        $this->db->where(array('goods.id' => $id));
        $result = $this->db->get();
        return $result->row_array();
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