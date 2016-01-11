<?php
/**
 *
 * Контроллер управления корзиной
 * @author Paintcast
 *
 */

class Orders extends LOFT_Controller
{
    public function index()
    {
        // Если пользователь залогинен
        if ($this->session->has_userdata('login'))
        {
            // Получаем login пользователя
            $user_login = $this->session->userdata('login');

            // Получаем ID пользователя по его login
            $this->load->model('User_Model');
            $user_id = $this->User_Model->getUserId($user_login);

            // Получаем содержимое корзины пользователя по его ID
            $this->load->model('Cart_Model');
            $basket = $this->Cart_Model->getBasket($user_id);

            // Получаем заказы пользователя по его ID
            $this->load->model('Orders_Model');
            $orders = $this->Orders_Model->getOrders($user_id);

            // Подгружаем хелпер, получаем HTML-код для отображения корзины / заказов
            $this->load->helper('htmlelement');
            $temp = getHtmlForBasket($basket, $orders);
            $this->setToData('basket', $temp);

            // $this->setToData('title', 'Корзина');
        }
        // Если пользователь не залогинен
        else
        {
            $this->setToData('title', 'Необходимо выполнить вход!');
        }

        $this->display('orders/orders');
    }

    /**
     * Метод для очистки содержимого корзины
     */
    public function clear($id_goods = null)
    {
        // Если пользователь залогинен
        if ($this->session->has_userdata('login'))
        {
            // Получаем login пользователя
            $user_login = $this->session->userdata('login');

            // Получаем ID пользователя по его login
            $this->load->model('User_Model');
            $user_id = $this->User_Model->getUserId($user_login);

            // Очищаем корзину
            $this->load->model('Cart_Model');
            $this->Cart_Model->clearBasket($user_id, $id_goods);

        // Если пользователь не залогинен
        }

        header('Location: ' . base_url() . 'orders');
    }

    public function view($id_order = null)
    {
        // Если пользователь залогинен
        if ($this->session->has_userdata('login'))
        {
            // Очищаем корзину
            $this->load->model('Orders_Model');
            $order_content = $this->Orders_Model->showOrderContent($id_order);

            // Подгружаем хелпер, получаем HTML-код для отображения содержимого заказа
            $this->load->helper('htmlelement');
            $temp = getHtmlForOrderView($order_content);
            $this->setToData('order_content', $temp);

            // Отображаем содержимое заказа
            $this->setToData('title', 'Содержимое заказа #' . $id_order);
            $this->display('orders/view');
        }
        // Если пользователь не залогинен
        else
        {
            header('Location: ' . base_url() . 'orders');
        }
    }
}