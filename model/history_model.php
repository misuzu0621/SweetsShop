<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * 購入履歴データ取得(二次元連想配列)
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @return array 取得したレコード
 */
function get_history_items($dbh, $user_id) {
    $sql = 'SELECT
                SS_history.item_id,
                SS_history.price_history,
                SS_history.tax_history,
                SS_history.amount,
                SS_history.createdate,
                items.name,
                items.price,
                items.tax,
                items.stock,
                items.img
            FROM
                SS_history
                INNER JOIN items
                ON SS_history.item_id = items.item_id
            WHERE
                SS_history.user_id = ?
                AND items.status = 1
            ORDER BY SS_history.history_id DESC
            LIMIT 10';
    $params = array($user_id);
    return fetch_all_query($dbh, $sql, $params);
}
