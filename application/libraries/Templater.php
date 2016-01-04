<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Templater
{
    /**
     * @var Twig_Loader_Filesystem
     */
    protected $loader;
    /**
     * @var Twig_Environment
     */
    protected $twig;

    protected $CI;

    /**
     * Конструктор обертка для шаблонизатора
     */
    public function __construct(){
        $this->CI = &get_instance();
        $this->loader = new Twig_Loader_Filesystem($this->CI->config->item('dir_twig_templates'));
        $this->twig = new Twig_Environment($this->loader);
    }


    /**
     * @param $templateName
     * @param array $data
     * @return string
     */
    public function render($templateName, $data = array()){
        return $this->twig->render($templateName.'.twig', $data);
    }

}