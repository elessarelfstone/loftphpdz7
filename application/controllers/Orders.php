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

            // Подгружаем хелпер для получения итоговой суммы содержимого корзины
            if($basket)
            {
                $this->load->helper('htmlelement');
                $total_price = getTotalPrice($basket);
                $this->setToData('total_price', $total_price);
            }

            // Пушим всю инфу в шаблон
            $this->setToData('basket', $basket);
            $this->setToData('orders', $orders);

        }
        // Если пользователь не залогинен
        else
        {
            $this->setToData('title', 'Необходимо выполнить вход!');
        }

        // Отображаем страницу
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

                // Подгружаем хелпер для получения итоговой суммы заказа
                $this->load->helper('htmlelement');
                $total_price = getTotalPrice($order_content);

                // Пушим всю инфу в шаблон
                $this->setToData('order_content', $order_content);
                $this->setToData('total_price', $total_price);

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

    /**
     * Метод для оформления заказа
     */
    public function make()
    {
        // Если пользователь залогинен
        if ($this->session->has_userdata('login'))
        {
            // Получаем ID пользователя по его login
            $this->load->model('User_Model');
            $user_login = $this->session->userdata('login');
            $user_id = $this->User_Model->getUserId($user_login);

            // Получаем содержимое корзины пользователя по его ID
            $this->load->model('Cart_Model');
            $basket = $this->Cart_Model->getBasket($user_id);

            //Если коризина не пуста
            if($basket)
            {
                // Выполняем оформление нового заказа
                $this->load->model('Orders_Model');
                $order_id = $this->Orders_Model->makeOrder($user_id, $basket);

                // Очищаем корзину
                $this->Cart_Model->clearBasket($user_id);

                // Устанавливаем заголовок
                $this->setToData('title', 'Заказ оформлен');
                $this->setToData('order_id', $order_id);

                // Отправить мыло админу
                // включаем библиотеку для отправки писем
                $this->load->library('email');

                // Получаем мыла админов, их is_active в базе = 2
                $this->load->model('User_Model');
                $emails = $this->User_Model->getAllUserEmails(2);

                if($emails)
                {
                    foreach ($emails as $email)
                    {
                        $this->email->from($this->config->item('from_email'), 'Интернет каталог');
                        $this->email->to($email['email'], 'Админу сайта');
                        $this->email->subject('Новый заказ #' . $order_id);
                        $this->email->message('Здрасти! Данное письмо уведомляет о том, что имеется новый заказ #'. $order_id);
                        $this->email->send();
                    }
                }

                // Отправляем мыло пользователю
                $this->email->from($this->config->item('from_email'), 'Интернет каталог');
                $this->email->to($user_login, 'Пользователю сайта');
                $this->email->subject('Ваш заказ #' . $order_id);
                $this->email->message('Здрасти! Данное письмо уведомляет о том, ваш заказ #'. $order_id . ' успешно получен.');
                $this->email->send();
            }
            // корзине пуста, не оформляем заказ
            else
            {
                $this->setToData('title', 'Невозможно оформить заказ!');
                $this->setToData('content', 'Корзина пуста. Сорян!');
            }

            // Отображаем страницу
            $this->display('orders/make');
        }
        // Если пользователь не залогинен
        else
        {
            header('Location: ' . base_url() . 'orders');
        }
    }
}