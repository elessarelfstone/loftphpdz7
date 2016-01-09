<?php

$config['dir_twig_templates'] = APPPATH.'views/';

// Установка конфигов для пагинации
$config['per_page'] = 9;

// настройки для формы валидации
$config['contact_validation'] = array(
    array(
        'field'   => 'email',
        'label'   => 'Ваш EMAIL',
        'rules'   => 'required|valid_email'
    ),
    array(
        'field'   => 'subject',
        'label'   => 'ТЕМА ПИСЬМА',
        'rules'   => 'required|xss_clean'
    ),
    array(
        'field'   => 'message',
        'label'   => 'ТЕКСТ ПИСЬМА',
        'rules'   => 'required|xss_clean'
    ),
);

$config['reg_validation'] = array(
    array(
        'field'   => 'email',
        'label'   => 'Ваш EMAIL',
        'rules'   => 'required|valid_email'
    ),
    array(
        'field'   => 'name',
        'label'   => 'Ваше Имя',
        'rules'   => 'required'
    ),
    array(
        'field'   => 'lastname',
        'label'   => 'Ваша фамилия',
        'rules'   => 'required'
    ),
    array(
        'field'   => 'password',
        'label'   => 'Ваш пароль',
        'rules'   => 'required'
    ),
);
$config['category'] = array(
    array(
        'field'   => 'name',
        'label'   => 'Наименование категории',
        'rules'   => 'required'
    ),

);
$config['product'] = array(
    array(
        'field'   => 'name',
        'label'   => 'Наименование товара',
        'rules'   => 'required'
    ),
    array(
        'field'   => 'price',
        'label'   => 'Цена',
        'rules'   => 'required|decimal'
    ),
    array(
        'field'   => 'cnt',
        'label'   => 'Количество',
        'rules'   => 'required|is_natural'
    ),
);



// email, с которого нужно отсылать письма
$config['from_email'] = "dake2006@mail.ru";

// email, которому нужно отсылать письма
$config['to_email'] = "elessarelfstone@mail.ru";