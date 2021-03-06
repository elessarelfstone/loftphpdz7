<?php

class Search extends LOFT_Controller
{

public function index()
{
    $this->load->model('Search_model');

    /*
     * Массив для селекта с категориями
     * */
    $categories = $this->Search_model->select('categoryes');
    array_unshift($categories, array('id' => 0, 'title' => 'Все'));
    $this->setToData('select_category', $categories);

    /*
     * Массив для селекта с брендами
     * */
    $brands = $this->Search_model->select('brand');
    array_unshift($brands, array('id' => 0, 'title' => 'Все'));
    $this->setToData('select_brands', $brands);

        /*
     * если был get запрос
     * */
    if ($this->input->server('REQUEST_METHOD') == 'GET') {
        if ($this->input->get('category') != '' or $this->input->get('query') != '' or $this->input->get('brand') != '' or $this->input->get('minprice') != '' or $this->input->get('maxprice') != '') {
            /*
       *  $search_array - массив условий для выборки из бд
       * */

            $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;

            $limit = 20;
            $search_array['category'] = $this->input->get('category');

            $search_array['query'] = $this->input->get('query');
            $this->setToData('memory_query', $this->input->get('query'));
            /*
             * memory... переменные для запоминания выбранных селектов и инпутов
             * */
            $this->setToData('memory_category', $this->input->get('category'));


            $search_array['brand'] = $this->input->get('brand');
            $this->setToData('memory_brand', $this->input->get('brand'));
            /*
             * если макс цена меньше минимальной
             * */
            if ((int)$this->input->get('maxprice') < (int)$this->input->get('minprice')) {
                /*
                 * устанавливаем минимальную в максимальную
                 * */
                $search_array['maxprice'] = (int)$this->input->get('minprice');
                $this->setToData('memory_maxprice', (int)$this->input->get('minprice'));
            } else {

                /*
                 * если не указана максимальная цена устанавливаем 999999999
                 * */
                if ((int)$this->input->get('maxprice') == NULL) {
                    $search_array['maxprice'] = 999999999;
                    $this->setToData('memory_maxprice', 999999999);
                } else {
                    $search_array['maxprice'] = (int)$this->input->get('maxprice');
                    $this->setToData('memory_maxprice', (int)$this->input->get('maxprice'));
                }

            }

            /*
             * если не указана минимальная цена устанавливаем 0
             * */
            if ((int)$this->input->get('minprice') == NULL) {
                $search_array['minprice'] = 0;
                $this->setToData('memory_minprice', 0);
            } else {
                $search_array['minprice'] = (int)$this->input->get('minprice');
                $this->setToData('memory_minprice', (int)$this->input->get('minprice'));
            }


            /*
             * получаем массив с товарами из бд согласно условиям
             * */
            $searching_products = $this->Search_model->getProductsFromSearch($search_array, $limit, $page);

            // настройки для пагинации
            $config = array();
            $config["base_url"] = base_url() . "search";
            $config["per_page"] = $limit;
            $config["uri_segment"] = 2;
            $config['last_link'] = 'Последняя';
            $config['first_link'] = 'Первая';
            $config['reuse_query_string'] = TRUE;

            $config['total_rows'] = $this->Search_model->getCountProductsFromSearch($search_array);
            $this->load->library('pagination');
            $this->pagination->initialize($config);
            $links = $this->pagination->create_links();

            $this->setToData('links', $links);

            /*
             * если ничего не найдено
             * */
            if (count($searching_products) == 0) {

                $error = "Ничего не найдено";
                $this->setToData('error', $error);
            } else {
                $this->setToData('searching_products', $searching_products);
            }

        } /*
     * если не GET запрос
     * */ else {

            $error = "Выберите что нибудь";
            $this->setToData('error', $error);

        }
    }
    $this->setToData('title', 'Поиск товаров ');

    $this->display('search/search');
}


}