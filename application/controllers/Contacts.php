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

            // если данные прошли проверку
            if ($this->form_validation->run() === TRUE)
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

        $form_html = $this->renderHTML('contacts/contact_form', array(), true);
        $this->setToData('title', 'Связаться с нами');
        $this->setToData('form', $form_html);


        $this->setToData('success_send', $success_send);

        $this->display('contacts/index');
    }
}