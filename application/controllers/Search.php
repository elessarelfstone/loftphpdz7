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
if ($this->input->get('category')!=NULL) {

    $search_array['category'] = $this->input->get('category');
    $this->setToData('memory_category', $this->input->get('category'));

    $search_array['brand'] = $this->input->get('brand');
    $this->setToData('memory_brand', $this->input->get('brand'));

    if((int)$this->input->get('maxprice')<(int)$this->input->get('minprice'))

    {
        $search_array['maxprice'] = (int)$this->input->get('minprice');
        $this->setToData('memory_maxprice', (int)$this->input->get('minprice'));
    }
    else {
        if ((int)$this->input->get('maxprice') == NULL) {
            $search_array['maxprice'] = 999999999;
            $this->setToData('memory_maxprice', 999999999);
        } else {
            $search_array['maxprice'] = (int)$this->input->get('maxprice');
            $this->setToData('memory_maxprice', (int)$this->input->get('maxprice'));
        }

    }
    if((int)$this->input->get('minprice')==NULL) {$search_array['minprice'] = 0;
        $this->setToData('memory_minprice', 0);} else
    {
        $search_array['minprice'] = (int)$this->input->get('minprice');
        $this->setToData('memory_minprice', (int)$this->input->get('minprice'));}


    $this->load->model('Products_Model');

    $searching_products = $this->Products_Model->getProductsFromSearch($search_array);
    if (count($searching_products) == 0) {
        $error = "Ничего не найдено";
        $this->setToData('error', $error);
    } else {
        $this->setToData('searching_products', $searching_products);
    }

}
        else
        {
            $error = "Выберите что нибудь";
            $this->setToData('error', $error);
        }
    }else {

        $error = "Выберите что нибудь";
        $this->setToData('error', $error);

    }

    $this->setToData('title', 'Поиск товаров ');

    $this->display('search/search');
}


}