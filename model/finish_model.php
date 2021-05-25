<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * $_SESSION['history_id']取得, $_SESSION['history_id']破棄
 * 購入完了していないときカートページへ
 * @return array $_SESSION['history_id'] 履歴ID
 */
function confirmation_history_id() {
    if (isset($_SESSION['history_id'])) {
        $history_id = $_SESSION['history_id'];
        unset($_SESSION['history_id']);
        return $history_id;
    } else {
        header('Location: ' . CART_URL);
        exit;
    }
}

/**
 * 購入した商品一覧を取得
 * @param  obj   $dbh        DBハンドル
 * @param  array $history_id 履歴ID配列
 * @return array $rows       購入した商品一覧配列
 */
function get_buy_items($dbh, $history_id) {
    $rows = array();
    foreach ($history_id as $key => $value) {
        $sql = 'SELECT
                    SS_history.item_id,
                    SS_history.price_history,
                    SS_history.tax_history,
                    SS_history.amount,
                    items.name,
                    items.img
                FROM
                    SS_history
                    INNER JOIN items
                    ON SS_history.item_id = items.item_id
                WHERE
                    history_id = ?';
        $params = array($value);
        $rows[] = fetch_query($dbh, $sql, $params);
    }
    return $rows;
}

/**
 * 合計金額(税込)を取得
 * @param  array $rows 購入商品配列
 * @return int   $sum  合計金額(税込)
 */
function get_sum($rows) {
    $sum = 0;
    foreach ($rows as $row) {
        if ((int)$row['tax_history'] === 1) {
            $subtotal = (int)$row['price_history'] * (int)$row['amount'] * TAX8K;
        } else {
            $subtotal = (int)$row['price_history'] * (int)$row['amount'] * TAX10;
        }
        $sum += $subtotal;
    }
    return $sum;
}
