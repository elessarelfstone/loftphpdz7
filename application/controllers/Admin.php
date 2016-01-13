<?php

use Goodby\CSV\Import\Standard\Interpreter;
use Goodby\CSV\Import\Standard\Lexer;
use Goodby\CSV\Import\Standard\LexerConfig;

class Admin extends LOFT_Controller
{
    public function categories()
    {
        $this->load->model('Categories_Model');
        $categories = $this->Categories_Model->getAll();
        $this->setToData('categories', $categories);
        $this->display('admin/categories');
    }

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
        } else {
            $this->load->model('Categories_Model');
            $title = $this->input->post('name');
            $id = $this->input->post('id');
            $this->Categories_Model->update(array('id' => $id), array('title' => $title));
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
        array_unshift($cats, array('id' => 0, 'title' => 'Все'));
        array_unshift($brands, array('id' => 0, 'title' => 'Все'));
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

        $cat = $this->input->get('cat_title');
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
        } else {
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
            } else {
                $this->load->helper('form');
                $this->setToData('error', validation_errors('<p class="error">', '</p>'));
                $this->setToData('product', array('product_title' => $name,
                        'brand_id' => $brand,
                        'category_id' => $cat,
                        'price' => $price,
                        'description' => $descr,
                        'cnt' => $cnt)
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
        } else {
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->config->item('product'));
            $name = $this->input->post('name');
            $cat = $this->input->post('cat_title');
            $brand = $this->input->post('brand_title');
            $price = $this->input->post('price');
            $descr = $this->input->post('descr');
            $cnt = $this->input->post('cnt');
            $id = $this->input->post('id');
            if ($this->form_validation->run() == TRUE) {
                $this->Products_Model->update(array('id' => $id), array('title' => $name,
                    'id_category' => $cat,
                    'id_brand' => $brand,
                    'price' => $price,
                    'description' => $descr, 'cnt' => $cnt));
                redirect('admin/products');
            } else {
                $this->load->helper('form');
                $this->setToData('error', validation_errors('<p class="error">', '</p>'));
                $this->setToData('product', array('product_title' => $name,
                        'brand_id' => $brand,
                        'category_id' => $cat,
                        'price' => $price,
                        'description' => $descr,
                        'cnt' => $cnt)
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
    public function upload()
    {
        $this->display('admin/upload');
    }
    public function do_upload()
    {
        $this->load->helper('form');
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 2048;
        $this->load->library('upload');
        $this->upload->initialize($config);
        if (!$this->upload->do_upload('userfile')) {
            $error = $this->upload->display_errors('<p>', '</p>');
            $this->setToData('error', $error);
            $this->display('admin/upload');
        } else {
            $this->load->model('Products_Model');
            $csvFile = new Keboola\Csv\CsvFile($this->upload->data('full_path'));
            foreach ($csvFile as $row) {
                $this->Products_Model->insert(array('title' => $row[0],
                    'cnt' => $row[1],
                    'price' => $row[2],
                    'description' => $row[3],
                    'id_category' => $row[4],
                    'id_brand' => $row[5]
                ));
            }
            redirect('admin/products');
        }
    }
    public function users($page=0, $limit=15, $is_active=null)
    {
        $this->load->model('User_Model');

        // настройки для пагинации
        $config = array();
        $config["base_url"] = base_url() . "admin/users/";
//        $config["total_rows"] = 100;
        $config["per_page"] = $limit;
        $config["uri_segment"] = 3;
        $config['last_link'] = 'Последняя';
        $config['first_link'] = 'Первая';
        $config['reuse_query_string'] = TRUE;

        $is_active = $this->input->get('is_active');

        $is_active = ($is_active == 3) ? NULL : $is_active;


        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $users = $this->User_Model->getAllUsers($page, $limit, $is_active);
        $config["total_rows"] = $this->User_Model->getCountAllUsers($is_active);

        $this->load->library('pagination');
        $this->pagination->initialize($config);
        $links = $this->pagination->create_links();

        $this->setToData('links', $links);

        $this->setToData('users', $users);

        $this->display('admin/users');
    }

    public function edituser($id = 0)
    {
        $this->load->model('User_Model');

        $this->setToData('mode', 'edit');
        $this->setToData('id', $id);

        if (!($this->input->server('REQUEST_METHOD') == 'POST')) {
            $user = $this->User_Model->getUserById($id);
            $this->setToData('user', $user);
            $this->display('admin/user');
        }
        else
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules($this->config->item('edit_user'));
            $name = $this->input->post('name');
            $id = $this->input->post('id');
            $lastname = $this->input->post('lastname');
            $email = $this->input->post('email');
            $is_active = $this->input->post('is_active');
            $password = $this->input->post('password');
            $birthday = $this->input->post('birthday');
            if ($this->form_validation->run() == TRUE){

$data = array(
    'name'=>$name,
    'lastname'=>$lastname,
    'email'=>$email,
    'birthday'=>$birthday,
    'is_active'=>$is_active,
    'last_update'=>date('Y-m-d H:i:s')
);
                if($password!='') {
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }
               $this->User_Model->update(array('id'=>$id), $data);
                    redirect('admin/users');

            }
            else
            {
                $this->load->helper('form');
                $this->setToData('error', validation_errors('<p class="error">', '</p>'));
                $this->setToData('user', array('name'=>$name,
                        'lastname'=>$lastname,
                        'e-mail'=>$email,
                        'is_active'=>$is_active
                        )
                );
                $this->display('admin/user');
            }
        }
    }

    public function adduser()
    {
        $this->load->model('User_Model');
        $this->setToData('mode', 'add');

        if(!($this->input->server('REQUEST_METHOD') == 'POST')){
            $this->display('admin/user');
        }
        else
        {
        $this->load->library('form_validation');
            $this->form_validation->set_rules($this->config->item('reg_validation'));
            $name = $this->input->post('name');
            $lastname = $this->input->post('lastname');
            $email = $this->input->post('email');
            $password = $this->input->post('password');
            $is_active = $this->input->post('is_active');
            $reg_date = date('Y-m-d H:i:s');
            if($this->form_validation->run() == TRUE)
            {
                $this->User_Model->insert(array('name'=>$name,'lastname'=>$lastname, 'email'=>$email, 'password'=>password_hash($password, PASSWORD_DEFAULT), 'is_active'=>$is_active, 'reg_date'=>$reg_date));

                redirect('admin/users');
            }
            else
            {
                $this->load->helper('form');
                $this->setToData('error', validation_errors('<p class="error">', '</p>'));
                $this->setToData('user', array('name'=>$name,
                        'lastname'=>$lastname,
                        'email'=>$email,
                        'password'=>$password
                    )
                );
                $this->display('admin/user');
            }
        }
    }

    public function deleteuser($id)
    {

        $this->load->model('User_Model');
        $this->User_Model->delete(array('id' => $id));
        redirect('admin/users');

    }

    public function sendmails()
    {
        $this->load->library('session');

        if(($this->input->server('REQUEST_METHOD') == 'POST')){
            $whom = $this->input->post('is_active');
            $subject = $this->input->post('subject');
            $message = $this->input->post('message');
            $this->load->model('User_Model');

            $emails = $this->User_Model->getAllUserEmails($whom);

            // включаем библиотеку для отправки писем
            $this->load->library('email');


            foreach ($emails as $email)
            {
                $this->email->from($this->config->item('from_email'), 'Интернет каталог');
                $this->email->to($email['email'], 'Пользователю сайта');
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->send();
            }
            $this->session->set_flashdata('success_send', true);
            redirect('admin/sendmails');


        }
        if($this->session->flashdata('success_send')) {

            $this->setToData('success', 'Письма отправлены');
            $this->session->set_flashdata('success_send'. FALSE);
        }

        $this->display('admin/sendmails');
    }


}