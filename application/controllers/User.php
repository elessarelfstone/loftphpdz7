<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class User extends LOFT_Controller
{

    public function login()
    {
        // получение данных с формы
        $email = $this->input->post('email');
        $pass = $this->input->post('password');


        $this->load->model("User_Model");
        $this->load->model('Cart_Model');
        $data['result'] = $this->User_Model->check_user($email, $pass);
        if ($data['result']['status']==0){
            $this->load->library('session');
            $session_data = $this->User_Model->session_data($email);
            $user_info = $this->User_Model->get(array('email'=>$email));
            $items_cnt = $this->Cart_Model->getSum('cnt', array('id_user'=>$user_info['id']));
            $data['result']['cnt'] = $items_cnt['cnt'];
            $this->session->set_userdata($session_data);
        }
        $this->output->json_output($data);
    }

    public function logreg()
    {
        $flag = 1;
        $this->load->library('form_validation');
        // установка правил валидации
        $this->form_validation->set_rules($this->config->item('reg_validation'));

        if ($this->form_validation->run() === TRUE)
        {
            // получение данных с формы
            $email = $this->input->post('email');
            $pass = $this->input->post('password');
            $name = $this->input->post('name');
            $lastname = $this->input->post('lastname');
            $birthday = $this->input->post('birthday');
            $this->load->model("User_Model");
            $this->load->model('Cart_Model');

            $data['result'] = $this->User_Model->check_user($email, $pass);
            if ($data['result']['status']==2 && $flag > 0){
                $this->User_Model->insert(array('email'=>$email,
                    'password'=> password_hash($pass, PASSWORD_DEFAULT),
                    'name'=>$name,
                    'lastname'=>$lastname,
                    'birthday'=>$birthday));

                $data['result'] = array('status'=>0);

            }
        }
        else
        {
            $data['result']['status']= 1;
            $data['result']['message']= $this->form_validation->error_string();
        }





        $this->output->json_output($data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url());
    }

    public function add($product_id)
    {
//        $temp = $this->session->userdata('login');
        $data = array();
        if ($this->session->has_userdata('login'))
        {
            $this->load->model('User_Model');
            $this->load->model('Cart_Model');
            $user_info = $this->User_Model->get(array('email'=>$this->session->userdata('login')));

            //TODO: Даурен, Шторм указывает здесь на то, что переменная $item_id не используется. Баг?
            $item_id = $this->Cart_Model->addProduct($user_info['id'], $product_id);

            $items_cnt = $this->Cart_Model->getSum('cnt', array('id_user'=>$user_info['id']));
            $data['result']['status'] = 0;
            $data['result']['cnt'] = $items_cnt['cnt'];

        }
        else
        {
            $data['result']['status'] = 1;
        }
        $this->output->json_output($data);
    }




}