<?php


class Products extends LOFT_Controller
{
    /**
     * вывод списка товаров по категории
     */
    public function index($num, $page = 0)
    {
        $this->load->model('Products_Model');
        // настройка пагинатора
        $config = array();
        $config["base_url"] = base_url() . "products/".$num;
        $config["total_rows"] = $this->Products_Model->products_count_by_cat($num);
        $config["per_page"] = 6;
        $config["uri_segment"] = 3;
        $config['last_link'] = 'Последняя';
        $config['first_link'] = 'Первая';
//        $config['use_page_numbers']  = TRUE;

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        // получаем данные для категории
        $this->load->model("Categories_Model");
        $cat_title = $this->Categories_Model->get(array('id'=>$num));

        $this->setToData('title', 'Товары категории '. $cat_title['title']);

        //получаем список товаров по категории
        $this->load->model("Products_Model");
        $products = $this->Products_Model->getProductsByCategory($num, $config["per_page"], $page);


        $this->load->library('pagination');

        $this->pagination->initialize($config);

        //TODO пофиксить, выдаёт ссылки  с 404 страницами
        $links = $this->pagination->create_links();

        $this->setToData('products', $products);
        $this->setToData('links', $links);


        $this->load->helper('htmlelement');
        $temp = getHtmlForProducts($products);
        $this->setToData('prod_html', $temp);


        $this->display('products/products');
    }


    /**
     *
     * Метод для отображения страницы с информацией о товаре
     *
     * УРЛ: /products/product/[ID]
     *
     * @author Paintcast
     *
     * @param $id - ID товара
     */
    public function product($id)
    {
        // подгружаем модель для работы с БД
        $this->load->model('Products_Model');

        // делаем запрос в БД по переданному ID
        $product_info = $this->Products_Model->getProductByID($id);

        // Устанавливаем тайтл страницы = значению поля title
        $this->setToData('title', $product_info->title);

        // подгружаем хелпер, получаем HTML-код для отображения информации о товаре
        $this->load->helper('htmlelement');
        $temp = getHtmlForProduct($product_info);
        $this->setToData('product_info', $temp);

        // отображаем страницу через шаблон product.twig
        $this->display('products/product');

    }
}