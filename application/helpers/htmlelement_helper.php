<?php

defined('BASEPATH') OR exit('No direct script access allowed');


    function getHtmlForProducts($products)
    {
        $result = '';
        $divided_products = array_chunk($products, 3, true);
        $col_template = '<div class="col-md-4"><div class="img-circle"><a href=":base_url:products/product/:num"><img src="" alt=""></a></div><div class="bold"><a href=":base_url:products/product/:num" class="products_links">:title</a></div><p>:descr</p><p>Цена: &nbsp; :price</p><p><a href=":base_url:user/add/:product_id" class="addProduct btn btn-primary btn-xs">В корзину</a></p></div>';
        foreach ($divided_products as $products){
            $row = '<div class="row">';
            foreach ($products as $product){
                $temp = str_replace(':title', $product['title'], $col_template);
                $temp = str_replace(':descr', mb_substr($product['description'],0,25).'...', $temp);
                $temp = str_replace(':base_url:', base_url(), $temp);
                $temp = str_replace(':num', $product['id'], $temp);
                $temp = str_replace(':price', $product['price'], $temp);
                $temp = str_replace(':product_id', $product['id'], $temp);
                $row .= $temp;
            }
            $row .= '</div>';
            $result .= $row;
        }
        return $result;
    }

/**
 *
 * Хелпер для получения итоговой суммы
 *
 * @author Paintcast
 * @param $array - массив товаров
 * @return int - сумма итого
 *
 */

function getTotalPrice($array){
    $result = 0;

    foreach($array as $item)
    {
        $result += $item['price'];
    }

    return $result;
}