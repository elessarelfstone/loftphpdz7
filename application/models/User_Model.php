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

    /**
     *
     * Метод для получения ID пользователя
     *
     * @autor Paintcast
     *
     * @param $login
     * @return mixed
     */

    public function getUserId($login)
    {
        $res = $this->get(array('email'=>$login));
        $user_id = $res['id'];

        return $user_id;

    }

    public function getAllUsers($page, $limit, $is_active = NULL)
    {
        $this->db->select('users.id, users.`name`, users.lastname, users.email, users.is_active');
        $this->db->from($this->table);
        $this->db->limit($limit, $page);
        if ($is_active)
            $this->db->where(array('users.is_active' => $is_active));
        $this->db->order_by('users.id DESC');
        $result = $this->db->get();
        return $result->result_array();
    }

    public function getAllUserEmails($is_active)
    {
        $this->db->select('users.email');
        $this->db->from($this->table);
        if($is_active == 0 | $is_active==1 |$is_active==2)
            $this->db->where(array('users.is_active'=>$is_active));
        elseif($is_active != 3) {
        return FALSE;}

        $result = $this->db->get();
        return $result->result_array();
    }

    public function getUserById($id)
    {
        $this->db->select('users.id, users.`name`, users.lastname, users.email, users.is_active, users.birthday');
        $this->db->from($this->table);
        $this->db->where(array('id'=>$id));
        $this->db->limit(0,0);
        $result = $this->db->get();
        return $result->result_array()[0];
    }

    public function getCountAllUsers($is_active = NULL)
    {
        $this->db->select('users.id, users.`name`, users.lastname, users.email');
        $this->db->from('users');
        if ($is_active)
            $this->db->where(array('users.is_active' => $is_active));

        $result = $this->db->get();
        return count($result->result_array());
    }

    public function isAdmin($login)
    {
        $result = false;
        $arr = $this->get(array('email'=>$login, 'is_active'=>2));
        if (sizeof($arr))
            $result = true;
        return $result;
    }


}