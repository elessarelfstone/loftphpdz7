<?php

class Search extends LOFT_Controller
{

public function index()
{
    $this->load->model('Search_model');

    $categories = $this->Search_model->select('categoryes');
    $this->setToData('select_category', $categories);

    $brands = $this->Search_model->select('brand');
    $this->setToData('select_brands', $brands);

    if ($this->input->server('REQUEST_METHOD') == 'GET') {


        $search_array['category'] = $this->input->get('category');
        $search_array['brand'] = $this->input->get('brand');
            $search_array['minprice'] =  $this->input->get('minprice');
            $search_array['maxprice'] =  $this->input->get('maxprice');

        $this->load->model('Products_Model');

        $searching_products = $this->Products_Model->getProductsFromSearch($search_array);
if (count($searching_products)==0)
{
    $searching_products[]["title"] = "Ничего не найдено";
    $this->setToData('searching_products', $searching_products);
}
        else {
                $this->setToData('searching_products', $searching_products);
        }


    }else {

        $searching_products[]["title"] = "Выберите что нибудь";
        $this->setToData('searching_products', $searching_products);

    }

    $this->setToData('title', 'Поиск товаров ');

    $this->display('search/search');
}


}