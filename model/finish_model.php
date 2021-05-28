<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * $_SESSION['history_id']取得, $_SESSION['history_id']破棄
 * 購入完了していないときカートページへ
 * @return array $_SESSION['history_id'] 履歴ID
 */
function get_order_id() {
    if (isset($_SESSION['order_id'])) {
        $history_id = $_SESSION['order_id'];
        unset($_SESSION['order_id']);
        return $history_id;
    } else {
        header('Location: ' . CART_URL);
        exit;
    }
}

/**
 * 購入した商品一覧を取得(二次元連想配列)
 * @param  obj   $dbh      DBハンドル
 * @param  int   $order_id 注文ID
 * @return array 取得したレコード
 */
function get_buy_items($dbh, $order_id) {
    $sql = 'SELECT
                order_details.order_id,
                order_details.order_price,
                order_details.order_tax,
                order_details.amount,
                items.name,
                items.img
            FROM
                order_details
                INNER JOIN items
                ON order_details.item_id = items.item_id
            WHERE
                order_id = ?';
    $params = array($order_id);
    return fetch_all_query($dbh, $sql, $params);
}

/**
 * 合計金額(税込)を取得
 * @param  array $rows 購入商品配列
 * @return int   $sum  合計金額(税込)
 */
function get_sum($rows) {
    $sum = 0;
    foreach ($rows as $row) {
        if ((int)$row['order_tax'] === 1) {
            $subtotal = (int)$row['order_price'] * (int)$row['amount'] * TAX8K;
        } else {
            $subtotal = (int)$row['order_price'] * (int)$row['amount'] * TAX10;
        }
        $sum += $subtotal;
    }
    return $sum;
}
