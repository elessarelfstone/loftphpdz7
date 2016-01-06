<?php

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

    public function editcat($id)
    {
        $something = $this->input->post('name');
        if (!($something)) {
            $this->load->model('Categories_Model');
            $cat_info = $this->Categories_Model->get(array('id' => $id));
            $this->setToData('mode', 'edit');
            $this->setVarsToData($cat_info);
            $this->display('admin/cat');
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

    }

}