<?php

class Orders_Model extends LOFT_Model
{
    protected $table = 'orders';

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

}