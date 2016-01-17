<?php


class Contacts extends LOFT_Controller
{

    public function index(){


        // включаем библиотеку для работы с сессиями
        $this->load->library('session');
        $success_send = false;

        // если был пост запрос
        if ($this->input->server('REQUEST_METHOD') == 'POST'){

            // подключение библиотеки для валидации форм
            $this->load->library('form_validation');
            // подулючение хелпера для обработки элементов форм
            $this->load->helper('security');

            // установка правил валидации
            $this->form_validation->set_rules($this->config->item('contact_validation'));
            $this->form_validation->set_rules('captcha', 'Captcha', 'callback_validate_captcha');

            // проверка каптчи

            // если данные прошли проверку
            if ($this->form_validation->run() === TRUE) // + правильная ли каптча
            {
                // получаем данные из формы
                $email = xss_clean($this->input->post('email'));
                $subject = xss_clean($this->input->post('subject'));
                $message =  xss_clean($this->input->post('message'));


                // включаем библиотеку для отправки писем
                $this->load->library('email');
                $this->email->from($this->config->item('from_email'), 'Сайт дизайн студии');
                $this->email->to($this->config->item('to_email'), 'Администратору сайта');
                $this->email->subject($subject);
                $this->email->message($message);
                $this->email->send();

                // в сессию записываем данные о том, что письмо отправлено
                $this->session->set_flashdata('success_send', true);
                redirect('/contacts');
            }

        }

        if($this->session->flashdata('success_send')){
            $success_send = $this->session->flashdata('success_send');
        }


        // здесь место для тебя ... передавай каптчу в массив ниже в шаблон




        $this->load->helper('captcha');
        $original_string = array_merge(range(0,9), range('a','z'), range('A', 'Z'));
        $original_string = implode("", $original_string);
        $captcha = substr(str_shuffle($original_string), 0, 6);
        $vals = array(
            'word' => $captcha,
            'img_path' => './captcha/',
            'img_url' => base_url().'/captcha/',
            'img_width' => 150,
            'img_height' => 50,
            'expiration' => 7200
        );

        $cap = create_captcha($vals);
        $data['image'] = $cap['image'];

        if(file_exists(BASEPATH."../captcha/".$this->session->userdata['image']))
            unlink(BASEPATH."../captcha/".$this->session->userdata['image']);

        $this->session->set_userdata(array('captcha'=>$captcha, 'image' => $cap['time'].'.jpg'));

        $form_html = $this->renderHTML('contacts/contact_form', $data, true);
        $this->setToData('title', 'Связаться с нами');
        $this->setToData('form', $form_html);

        $this->setToData('success_send', $success_send);

        $this->display('contacts/index');
    }

    public function validate_captcha(){
        if($this->input->post('captcha') != $this->session->userdata['captcha'])
        {
            $this->form_validation->set_message('validate_captcha', 'Вы ввели неправильно каптчу');
            return false;
        }else{
            return true;
        }

    }
}