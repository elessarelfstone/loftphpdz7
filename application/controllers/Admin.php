<?php

class Admin extends LOFT_Controller
{
    public function categories()
    {
        $this->load->model('Categories_Model');
        $categories = $this->Categories_Model->getAll();
        $this->setToData('categories', $categories);
        $this->display('admin/categories');}

    public function addcat()
    {
        if (!($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->setToData('mode', 'add');
            $this->display('admin/cat');
        } else {
            $this->load->model('Categories_Model');
            $title = $this->input->post('name');
            $this->Categories_Model->insert(array('title' => $title));
            redirect('admin/categories');
        }
    }

    public function editcat($id = 0)
    {
        $something = $this->input->post('name');
        if (!($something)) {
            $this->load->model('Categories_Model');
            $cat_info = $this->Categories_Model->get(array('id' => $id));
            $this->setToData('mode', 'edit');
            $this->setVarsToData($cat_info);
            $this->display('admin/cat');
        }
        else
        {
            $this->load->model('Categories_Model');
            $title = $this->input->post('name');
            $id = $this->input->post('id');
            $this->Categories_Model->update(array('id'=>$id), array('title' => $title));
            redirect('admin/categories');
        }

    }

    public function deletecat($id)
    {
        $this->load->model('Categories_Model');
        $this->Categories_Model->delete(array('id' => $id));
        redirect('admin/categories');
    }

    public function products()
    {
        $this->load->model('Products_Model');
        $products = $this->Products_Model->getAllProducts();
        $this->setToData('products', $products);
        $this->display('admin/products');
    }
    //TODO везде сделать валидацию
    public function addprod()
    {
        if (!($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->setToData('mode', 'add');
            $this->load->model('Categories_Model');
            $this->load->model('Brand_Model');
            $cats = $this->Categories_Model->getAll();
            $brands = $this->Brand_Model->getAll();
            $this->setToData('cats', $cats);
            $this->setToData('brands', $brands);
            $this->display('admin/product');
        }
        else
        {
            $this->load->model('Products_Model');
            $name = $this->input->post('name');
            $cat = $this->input->post('cat_title');
            $brand = $this->input->post('brand_title');
            $price = $this->input->post('price');
            $descr = $this->input->post('descr');
            $this->Products_Model->insert(array('title'=>$name,
                                                'id_category'=>$cat,
                                                'id_brand'=>$brand,
                                                'price'=>$price,
                                                'description'=>$descr));
            redirect('admin/products');
        }
    }
    public function editprod($id = 0)
    {
        if (!($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->setToData('mode', 'edit');
            $this->load->model('Categories_Model');
            $this->load->model('Brand_Model');
            $this->load->model('Products_Model');
            $cats = $this->Categories_Model->getAll();
            $brands = $this->Brand_Model->getAll();
            $product = $this->Products_Model->getProductById2($id);
            $this->setToData('cats', $cats);
            $this->setToData('brands', $brands);
            $this->setToData('product', $product);
            $this->display('admin/product');
        }
        else
        {
            $this->load->model('Products_Model');
            $name = $this->input->post('name');
            $cat = $this->input->post('cat_title');
            $brand = $this->input->post('brand_title');
            $price = $this->input->post('price');
            $descr = $this->input->post('descr');
            $this->Products_Model->update(array('id'=>$id),  array('title'=>$name,
                                                'id_category'=>$cat,
                                                'id_brand'=>$brand,
                                                'price'=>$price,
                                                'description'=>$descr));

            redirect('admin/products');
        }
    }

}