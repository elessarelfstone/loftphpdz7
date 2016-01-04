<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class LOFT_Output extends CI_Output
{
    function json_output($data)
    {
        $this->set_content_type('application/json');
        $this->final_output = json_encode($data);
        return $this;
    }
}