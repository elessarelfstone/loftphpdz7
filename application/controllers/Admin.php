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

    public function products($page = 0, $limit = 10, $cat = NULL, $brand = NULL)
    {
        $this->load->model('Categories_Model');
        $this->load->model('Brand_Model');
        $cats = $this->Categories_Model->getAll();
        $brands = $this->Brand_Model->getAll();
        array_unshift($cats, array('id'=>0, 'title'=>'Все'));
        array_unshift($brands, array('id'=>0, 'title'=>'Все'));
        $this->setToData('cats', $cats);
        $this->setToData('brands', $brands);
        $this->load->model('Products_Model');



        // настройки для пагинации
        $config = array();
        $config["base_url"] = base_url() . "admin/products/";
//        $config["total_rows"] = 100;
        $config["per_page"] = $limit;
        $config["uri_segment"] = 3;
        $config['last_link'] = 'Последняя';
        $config['first_link'] = 'Первая';
        $config['reuse_query_string'] = TRUE;

        $cat  = $this->input->get('cat_title');
        $brand = $this->input->get('brand_title');

        $cat = ($cat == 0) ? NULL : $cat;
        $brand = ($brand == 0) ? NULL : $brand;


        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $products = $this->Products_Model->getAllProducts($page, $limit, $cat, $brand);
        $config["total_rows"] = $this->Products_Model->getCntAllProducts($cat, $brand);

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $links = $this->pagination->create_links();

        $this->setToData('products', $products);
        $this->setToData('links', $links);
        $this->setToData('catid', $cat);
        $this->setToData('brandid', $brand);

        $this->display('admin/products');
    }

    //TODO везде сделать валидацию
    public function addprod()
    {
        $this->load->model('Categories_Model');
        $this->load->model('Brand_Model');
        $cats = $this->Categories_Model->getAll();
        $brands = $this->Brand_Model->getAll();
        $this->setToData('cats', $cats);
        $this->setToData('brands', $brands);
        $this->setToData('mode', 'add');
        if (!($this->input->server('REQUEST_METHOD') == 'POST')) {
            $this->display('admin/product');
        }
        else
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->config->item('product'));
            $name = $this->input->post('name');
            $cat = $this->input->post('cat_title');
            $brand = $this->input->post('brand_title');
            $price = $this->input->post('price');
            $descr = $this->input->post('descr');
            $cnt = $this->input->post('cnt');
            if ($this->form_validation->run() == TRUE) {
                $this->load->model('Products_Model');
                $this->Products_Model->insert(array('title' => $name,
                    'id_category' => $cat,
                    'id_brand' => $brand,
                    'price' => $price,
                    'description' => $descr,
                    'cnt' => $cnt
                ));
                redirect('admin/products');
            }
            else {
                $this->load->helper('form');
                $this->setToData('error', validation_errors('<p class="error">', '</p>'));
                $this->setToData('product', array('product_title'=>$name,
                                                  'brand_id'=>$brand,
                                                  'category_id'=>$cat,
                                                  'price'=>$price,
                                                  'description'=>$descr,
                                                  'cnt'=>$cnt)
                                                    );

                $this->display('admin/product');
            }
        }
    }
    public function editprod($id = 0)
    {
        $this->load->model('Categories_Model');
        $this->load->model('Brand_Model');
        $this->load->model('Products_Model');
        $cats = $this->Categories_Model->getAll();
        $brands = $this->Brand_Model->getAll();
        $this->setToData('cats', $cats);
        $this->setToData('brands', $brands);
        $this->setToData('mode', 'edit');

        if (!($this->input->server('REQUEST_METHOD') == 'POST')) {
            $product = $this->Products_Model->getProductById2($id);
            $this->setToData('product', $product);
            $this->display('admin/product');
        }
        else
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->config->item('product'));
            $name = $this->input->post('name');
            $cat = $this->input->post('cat_title');
            $brand = $this->input->post('brand_title');
            $price = $this->input->post('price');
            $descr = $this->input->post('descr');
            $cnt = $this->input->post('cnt');
            $id = $this->input->post('id');
            if ($this->form_validation->run() == TRUE){
                $this->Products_Model->update(array('id'=>$id),  array('title'=>$name,
                    'id_category'=>$cat,
                    'id_brand'=>$brand,
                    'price'=>$price,
                    'description'=>$descr, 'cnt'=>$cnt));
                redirect('admin/products');
            }
            else
            {
                $this->load->helper('form');
                $this->setToData('error', validation_errors('<p class="error">', '</p>'));
                $this->setToData('product', array('product_title'=>$name,
                        'brand_id'=>$brand,
                        'category_id'=>$cat,
                        'price'=>$price,
                        'description'=>$descr,
                        'cnt'=>$cnt)
                );
                $this->display('admin/product');
            }
        }
    }
    public function deleteprod($id)
    {
        $this->load->model('Products_Model');
        $this->Products_Model->delete(array('id' => $id));
        redirect('admin/products');
    }

    /**
     *
     * Метод для отображения списка заказов в админке
     *
     * @author Paintcast
     *
     */
    public function orders()
    {
        // подключаем модель
        $this->load->model('Orders_Model');

        // делаем запрос в БД
        $orders = $this->Orders_Model->getAllOrders('orders.date_order DESC');
        $this->setToData('orders', $orders);

        // отображеем страницу
        $this->display('admin/orders');
    }

    /**
     *
     * Метод отображения содержимого заказа
     *
     * @author Paintcast
     *
     * @param null $id_order - ID заказа
     *
     */
    public function viewOrder($id_order = null)
    {
        if($id_order)
        {
            // подключаем модель + делаем запрос в БД
            $this->load->model('Orders_Model');
            $order_content = $this->Orders_Model->showOrderContent($id_order);
            $this->setToData('order_content', $order_content);
            $this->setToData('title', 'Содержимое заказа #'.$id_order);

            // считаем итоговую сумму по заказу
            $total_price = 0;

            foreach($order_content as $item )
            {
                $total_price += $item['price'];
            }

            // пушим итоговую сумму в шаблон
            $this->setToData('total_price', $total_price);

            // отображеем страницу
            $this->display('admin/vieworder');
        }
        else
        {
            header('Location: ' . base_url() . 'admin/orders');
        }
    }

    /**
     *
     * Метод редактирования статуса заказа
     *
     * @author Paintcast
     *
     * @param null $id_order - ID заказа
     *
     */
    public function editOrder($id_order = null)
    {
        if($id_order)
        {
            // подключаем модель + делаем запрос в БД
            $this->load->model('Orders_Model');
            $order_status = $this->Orders_Model->getOrderStatus($id_order);
            $all_status = $this->Orders_Model->getAllStatus();

            $this->setToData('order_status', $order_status);
            $this->setToData('all_status', $all_status);
            $this->setToData('id_order', $id_order);

            $this->setToData('title', 'Редактирование статуса заказа #'.$id_order);

            // отображеем страницу
            $this->display('admin/editorder');
        }
        else
        {
            header('Location: ' . base_url() . 'admin/orders');
        }
    }


    /**
     * Метод изменения статуса заказа
     *
     * @author Paintcast
     *
     */
    public function editstatus()
    {
        $new_status = $this->input->post('new_status');
        $id_order = $this->input->post('id_order');

        if($new_status && $id_order)
        {
            // подключаем модель + делаем запрос в БД
            $this->load->model('Orders_Model');
            $result = $this->Orders_Model->chageStatus($id_order, $new_status);

            // анализируем результат запроса в БД
            if($result)
            {
                $this->setToData('title', 'Cтатуса заказа #'.$id_order.' был изменён успешно.');
            }
            else
            {
                $this->setToData('title', 'Cтатуса заказа #'.$id_order.' не был изменён. Сорян');
            }

            // отображеем страницу
            $this->display('admin/editstatus');
        }
        else
        {
            header('Location: ' . base_url() . 'admin/orders');
        }
    }
}