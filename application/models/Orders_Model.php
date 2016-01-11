<?php

class Orders_Model extends LOFT_Model
{
    public $table = 'orders';

    function getAllOrders()
    {
        $this->db->select('orders.id, orders.id_user, orders.id_status, orders.date_order, users.name uname, users.lastname');
        $this->db->from('orders');
        $this->db->join('users', 'orders.id_user = users.id.');
        $result = $this->db->get();
        return $result->result_array();
    }

    // заготовка типа под процессинг))
    function makeOrder($id_user, $items)
    {
        $this->db->trans_start();
        $order_id = $this->db->insert(array('id_user'=>$id_user, 'id_status'=>1, 'date_order'=> date()));
        $this->load->model('OrderItems_Model');
        foreach ( $items as $item )
        {
            $this->OrderItems_Model->insert(array('price'=>$item['price'], 'cnt'=>$item['cnt'], 'id_goods'=>$item['id_goods'], 'id_order'=>$order_id));
        }
        $this->db->trans_complete();
    }

    /**
     *
     * Метод получения списка заказов по ID пользователя
     *
     * @author Paintcast
     *
     * @param $user_id - ID пользователя
     * @return mixed  - массив заказов
     */

    public function getOrders($user_id)
    {
        $this->db->select('orders.id, orders.date_order, status.title AS status');
        $this->db->select_sum('order_items.price');
        $this->db->join('status', 'orders.id_status = status.id');
        $this->db->join('order_items', 'order_items.id_order = orders.id');
        $this->db->where(array('orders.id_user'=>$user_id));
        $this->db->group_by('orders.id');
        $result = $this->db->get($this->table);
        return $result->result_array();
    }

    /**
     *
     * Метод получения списка заказов по ID пользователя
     *
     * @author Paintcast
     *
     * @param $id_order - ID заказа
     * @return mixed - массив содержимого заказа
     */

    public function showOrderContent($id_order)
    {
        $this->db->select('order_items.price, order_items.cnt, order_items.id_goods, goods.title');
        $this->db->from('order_items');
        $this->db->join('goods', 'goods.id = order_items.id_goods');
        $this->db->where(array('order_items.id_order'=>$id_order));
        $result = $this->db->get();
        return $result->result_array();
    }

    /**
     *
     * Метод проверяет принадлежность заказа с ID = $id_order пользователю c ID = $id_user
     *
     * @param $id_order - ID заказа
     * @param $id_user - ID пользователя
     * @return bool - true, если принадлежит, иначе false
     */
    public function checkUserOrder($id_order, $id_user){
        $this->db->select('orders.id, orders.id_user');
        $this->db->where(array('orders.id'=>$id_order,'orders.id_user'=>$id_user));
        $result = $this->db->get($this->table)->num_rows();
        if($result)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}