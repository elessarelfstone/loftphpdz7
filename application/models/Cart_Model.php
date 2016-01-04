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
}