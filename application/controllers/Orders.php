<?php
/**
 *
 * Контроллер управления корзиной
 * @author Paintcast
 *
 */



class Orders extends LOFT_Controller
{
    /**
     * вывод списка товаров по категории
     */
    public function index()
    {


        $this->setToData('title', 'Корзина');

        $this->display('orders/orders');
    }
}