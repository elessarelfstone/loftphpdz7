<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Main extends LOFT_Controller
{
    public function index()
    {
        $this->setToData('title', 'Главная страница');
        $this->load->model("Pages_Model");
        $about = $this->Pages_Model->get(array('alias'=>'about'));
        $this->setToData("about", $about);
        $this->display('main/index');
    }
}