<?php

class Orders_Model extends LOFT_Model
{
    public $table = 'orders';

    // from Paintcast: я тут чуток допилил метод, чтобы не создавать ещё один :)
    function getAllOrders($order_by = null)
    {
        $this->db->select('orders.id, orders.id_user, orders.id_status, orders.date_order, users.name uname, users.lastname, status.title');
        $this->db->from('orders');
        $this->db->join('users', 'orders.id_user = users.id');
        $this->db->join('status', 'orders.id_status = status.id');

        if($order_by)
        {
            $this->db->order_by($order_by);
        }

        $result = $this->db->get();
        return $result->result_array();
    }


    /**
     *
     * Метод для процессинга нового заказа
     *
     * @param $id_user - ID пользователя
     * @param $items - массив товаров из корзины
     */
    function makeOrder($id_user, $items)
    {
        $this->db->trans_start();
        $this->db->insert('orders', array('id_user'=>$id_user, 'id_status'=>1, 'date_order'=> date(DATE_ATOM, time())));
        $order_id = $this->db->insert_id();
        foreach ( $items as $item )
        {
            $this->db->insert('order_items', array('price'=>$item['price'], 'cnt'=>$item['cnt'], 'id_goods'=>$item['id_goods'], 'id_order'=>$order_id));
        }
        $this->db->trans_complete();
        return $order_id;
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
     * Метод получения списка товаров в заказе по его ID
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