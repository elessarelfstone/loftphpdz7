<?php
/**
 *
 * Контроллер управления корзиной
 *
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
     *
     * @param null $id_goods - если задано, то ID товара, который нужно удалить из корзины
     *
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

    /**
     * Метод для отображения содержимого заказа
     *
     * @param $id_order - ID заказа
     *
     * @return bool - false, если не передан ID заказа
     */

    public function view($id_order = null)
    {
        // Если пользователь залогинен и есть ID заказа
        if ($this->session->has_userdata('login') && $id_order)
        {
            // Необходимо проверить есть ли доступ у текущего пользователя доступ к заказу с ID = $id_user
            // Получаем login пользователя
            $user_login = $this->session->userdata('login');

            // Получаем ID пользователя по его login
            $this->load->model('User_Model');
            $id_user = $this->User_Model->getUserId($user_login);

            // Проверяем принадлежность заказа в БД
            $this->load->model('Orders_Model');
            $flag = $this->Orders_Model->checkUserOrder($id_order, $id_user);

            if($flag){
                // Заказ принадлежит пользователю, получаем содержимое заказа
                $order_content = $this->Orders_Model->showOrderContent($id_order);

                // Подгружаем хелпер, получаем HTML-код для отображения содержимого заказа
                $this->load->helper('htmlelement');
                $temp = getHtmlForOrderView($order_content);
                $this->setToData('order_content', $temp);

                // Устанавливаем заголовок
                $this->setToData('title', 'Содержимое заказа #' . $id_order);
            }
            else
            {
                //заказ не принадлежит пользователю
                $this->setToData('title', 'Содержимое заказа недоступно. Сорян!');
            }

            // Отображаем страницу
            $this->display('orders/view');
        }
        // Если пользователь не залогинен и нет ID заказа
        else
        {
            header('Location: ' . base_url() . 'orders');
        }
    }

    public function make($id_user)
    {

    }
}