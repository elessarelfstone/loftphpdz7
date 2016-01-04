<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}


class LOFT_Controller extends CI_Controller
{
    protected $data = array();

    public function __construct(){
        parent::__construct();
        $this->getDefaultData();
    }



    /**
     *  Метод вызова шаблонизатора
     *
     * @param $templateName - имя шаблона
     */
    protected function display($templateName){
        echo $this->templater->render($templateName, $this->data);
    }

    /**
     *  Установка значения для переменных шаблона
     *
     * @param $key
     * @param $value
     */
    protected function setToData($key, $value){
        $this->data[$key] = $value;
    }

    /**
     *  Вставка данных в виде массива
     *
     * @param $data
     */
    protected function setVarsToData($data){
        foreach($data as $key => $value){
            $this->data[$key] = $value;
        }
    }

    /**
     *  Получение данных, необходимых на каждой странице
     */
    protected function getDefaultData(){
        $this->setToData('base_url', base_url());
        $this->load->library('session');

        $email = $this->session->userdata('login');
        if ($this->session->userdata('login')!='')
        {
            $this->setToData('name', $this->session->userdata('name'));
            $this->setToData('lastname', $this->session->userdata('lastname'));
            $this->load->model('Cart_Model');
            $this->load->model('User_Model');
            $user_info = $this->User_Model->get(array('email'=>$email));
            $items_cnt = $this->Cart_Model->getSum('cnt', array('id_user'=>$user_info['id']));
            $this->setToData('cnt', $items_cnt['cnt']);
        }

        // получение списка категорий товаров
        $categories = $this->Categories_Model->getAll();
        $this->setToData('categories', $categories);
    }

    public function page404(){
        $this->setToData('title', 'Ошибка, страница не найдена');
        $this->display('main/404');
    }

    // метод который генерит шаблон нативного CI и возвращает разметку
    protected function renderHTML($template, $data = array(), $return = false){
        return $this->load->view($template, $data, $return);
    }
}