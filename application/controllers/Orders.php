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

            // Подгружаем хелпер, получаем HTML-код для отображения корзины
            $this->load->helper('htmlelement');
            $temp = getHtmlForBasket($basket);
            $this->setToData('basket', $temp);

            $this->setToData('title', 'Корзина');
        }
        // Если пользователь не залогинен
        else
        {
            $this->setToData('title', 'Необходимо выполнить вход!');
        }

        $this->display('orders/orders');
    }
}