<?php

class Cart_Model extends LOFT_Model
{
    public $table = 'cart';

    public function addProduct($user_id, $product_id)
    {
        $info = array('id_user'=>$user_id,
                        'id_goods'=>$product_id);
        $cart_item = $this->get($info);
        if (empty($cart_item))
        {
            $result = $this->insert($info);
        }
        else
        {
            $info['cnt'] = $cart_item['cnt']+1;
            $this->update(array('id'=>$cart_item['id']), $info);
            $result = $cart_item['id'];
        }
        return $result;
    }

    /**
     *
     * Метод получения содержимого корзины
     *
     * @autor Paintcast
     *
     * @param $user_id - ID пользователя
     * @return mixed - содержимое корзины
     */

    public function getBasket($user_id)
    {
        $this->db->select('cart.cnt, cart.id_goods, goods.price, goods.title');
        $this->db->join('goods', 'goods.id = cart.id_goods');
        $this->db->where(array('id_user'=>$user_id));
        $cart_items = $this->db->get($this->table);

        if ($cart_items->num_rows())
        {
            return $cart_items->result_array();
        }
        else
        {
            return null;
        }
    }


    /**
     *
     * Метод очистки корзины / удаления товара из корзины
     *
     * @param $user_id - ID пользователя
     * @param null $id_goods - ID товара, который нужно удалить из корзины
     * @return bool
     */
    public function clearBasket($user_id, $id_goods = null)
    {
        if($id_goods)
        {
            // Если передаётся id_goods, то удаляем этот товар
            $this->delete(array('id_user'=>$user_id, 'id_goods'=> $id_goods));
        }
        else
        {
            // Если id_goods = null, то удаляем всё из корзины
            $this->delete(array('id_user'=>$user_id));
        }

        return true;
    }
}