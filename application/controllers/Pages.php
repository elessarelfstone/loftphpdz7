<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pages extends LOFT_Controller
{

    public function payment(){
        $this->setToData('title', 'Способы оплаты');
        $this->load->model("Pages_Model");
        $payment = $this->Pages_Model->get(array('alias'=>'payment'));
        $this->setVarsToData($payment);
        $this->display('pages/payment');
    }
    public function test(){
        print_r($this->session->all_userdata());
    }
}