<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * 購入履歴取得(二次元連想配列)
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @return array 取得したレコード
 */
function get_orders($dbh, $user_id) {
    $sql = 'SELECT
                order_id, createdate
            FROM
                orders
            WHERE
                user_id = ?
            ORDER BY order_id DESC
            LIMIT 5';
    $params = array($user_id);
    return fetch_all_query($dbh, $sql, $params);
}

/**
 * 購入明細取得(三次元連想配列)
 * @param  obj   $dbh           DBハンドル
 * @param  array $orders        購入履歴データ
 * @return array $order_details 購入明細データ
 */
function get_order_details($dbh, $orders) {
    $order_details = array();
    foreach ($orders as $order) {
        $sql = 'SELECT
                order_details.item_id,
                order_details.order_price,
                order_details.order_tax,
                order_details.amount,
                items.name,
                items.price,
                items.tax,
                items.stock,
                items.img
            FROM
                order_details
                INNER JOIN items
                ON order_details.item_id = items.item_id
            WHERE
                order_details.order_id = ?';
        $params = array($order['order_id']);
        $order_details[] = fetch_all_query($dbh, $sql, $params);
    }
    return $order_details;
}

/**
 * 現在の税込価格取得
 * @param  array $order_details         購入明細データ
 * @return int   $tax_include_price_now 現在の税込価格
 */
function get_tax_include_price_now($order_detail) {
    if ($order_detail['tax'] === 1) {
        $tax_include_price_now = $order_detail['price'] * TAX8K;
    } else {
        $tax_include_price_now = $order_detail['price'] * TAX10;
    }
    return $tax_include_price_now;
}

/**
 * 購入時の税込価格取得
 * @param  array $order_detail            購入明細データ
 * @return int   $tax_include_price_order 購入時の税込価格
 */
function get_tax_include_price_order($order_detail) {
    if ($order_detail['order_tax'] === 1) {
        $tax_include_price_order = $order_detail['order_price'] * TAX8K;
    } else {
        $tax_include_price_order = $order_detail['order_price'] * TAX10;
    }
    return $tax_include_price_order;
}

/**
 * 税込小計取得
 * @param  array $order_detail 購入明細データ
 * @return int   税込小計
 */
function get_tax_include_subtotal_price($order_detail) {
    return get_tax_include_price_order($order_detail) * $order_detail['amount'];
}
