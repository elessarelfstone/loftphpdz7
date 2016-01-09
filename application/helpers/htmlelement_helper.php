<?php

defined('BASEPATH') OR exit('No direct script access allowed');


    function getHtmlForProducts($products)
    {
        $result = '';
        $divided_products = array_chunk($products, 3, true);
        $col_template = '<div class="col-md-4"><div class="img-circle"><a href=":base_url:products/product/:num"><img src="" alt=""></a></div><div class="bold"><a href=":base_url:products/product/:num" class="products_links">:title</a></div><p>:descr</p><p><a href=":base_url:user/add/:product_id" class="addProduct btn btn-primary btn-xs">В корзину</a></p></div>';
        foreach ($divided_products as $products){
            $row = '<div class="row">';
            foreach ($products as $product){
                $temp = str_replace(':title', $product['title'], $col_template);
                $temp = str_replace(':descr', mb_substr($product['description'],0,25).'...', $temp);
                $temp = str_replace(':base_url:', base_url(), $temp);
                $temp = str_replace(':num', $product['id'], $temp);
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
 * Генератор HTML-шаблона для отображения информации о товаре
 *
 * @author Paintcast
 *
 * @param $product_info – массив с данными о товаре
 * @return string - HTML-код
 */
function getHtmlForProduct($product_info){
    // HTML-шаблон разметки информации о товаре
    $template = <<<END
<div class="col-md-12">
    <p>Title: :title</p>
    <p>Category: :category</p>
    <p>Brand: :brand</p>
    <p>Price: :price</p>
    <p>Description: :description</p>
    <p><a href=":base_url:user/add/:product_id" class="addProduct btn btn-primary btn-xs">В корзину</a></p>
</div>
END;

    // Заполняем шаблон данными
    $result = str_replace(
        array(
            ':title',
            ':category',
            ':brand',
            ':price',
            ':description',
            ':product_id',
            ':base_url:'
        ),
        array(
            $product_info->title,
            $product_info->category,
            $product_info->brand,
            $product_info->price,
            $product_info->description,
            $product_info->id,
            base_url()
        ),
        $template
    );

    return $result;
}

/**
 *
 * Генератор HTML-шаблона корзины
 *
 * @author Paintcast
 *
 * @param $basket – массив, содержимое корзины
 * @return string - HTML-код
 *
 */

function getHtmlForBasket($basket){

    // если корзина не пуста
    if($basket)
    {
        $line_number = 1;
        $total_price = 0;
        $result = <<<END
<div class="col-md-12">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Наименование товара</th>
                <th>Количество</th>
                <th>Цена</th>
            </tr>
        </thead>
        <tbody>
END;
        $line_template = <<<END
            <tr>
                <td>{line_number}</td>
                <td><a href="{url}">{title}</a></td>
                <td>{count}</td>
                <td>{price}</td>
            </tr>
END;

        foreach ( $basket as $item )
        {
            $result .= str_replace(
                array(
                    '{line_number}',
                    '{url}',
                    '{title}',
                    '{count}',
                    '{price}'
                ),
                array(
                    $line_number,
                    base_url() . 'products/product/' . $item['id_goods'],
                    $item['title'],
                    $item['cnt'],
                    $item['cnt'] * $item['price']
                ),
                $line_template
            );
            $line_number++;
            $total_price += $item['cnt'] * $item['price'];
        }

        $result .= '<tr><td colspan="3" align="right">Итого к оплате:</td><td>' . $total_price . '</td></tr>';

        $result .= '</tbody></table></div>';
    }

    // иначе в корзине пусто
    else
    {
        $result = 'В корзине пусто! Сорян!';
    }

    return $result;
}

