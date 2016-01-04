<?php

class User_Model extends LOFT_Model
{
    public $table = 'users';

    public function check_user($login, $pass)
    {
        $result = array();
        $result['login'] = $login;
        $result['password'] = $pass;
        $res = $this->get(array('email'=>$login));
        if (count($res) > 0)
        {
            $hash = $res['password'];
            if (password_verify($pass, $hash))
            {
                $result['status'] = 0;
                $result['name'] = $res['name'];
                $result['lastname'] = $res['lastname'];
            }
            else
                $result['status'] = 1;

        }
        else
                $result['status'] = 2;

        return $result;
    }
    public function session_data($login)
    {
        $result = array();
        $res = $this->get(array('email'=>$login));
        $result['name'] = $res['name'];
        $result['lastname'] = $res['lastname'];
        $result['login'] = $res['email'];
        return $result;
    }


}