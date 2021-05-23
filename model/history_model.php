<?php

/**
 * 購入した商品の一覧を取得
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @return array $rows    購入した商品の一覧配列
 */
function get_history_items($dbh, $user_id) {
    try {
        $sql = 'SELECT
                    SS_history.item_id,
                    SS_history.price_history,
                    SS_history.tax_history,
                    SS_history.amount,
                    SS_history.createdate,
                    SS_items.name,
                    SS_items.price,
                    SS_items.tax,
                    SS_items.stock,
                    SS_items.img
                FROM
                    SS_history
                    INNER JOIN SS_items
                    ON SS_history.item_id = SS_items.item_id
                WHERE
                    SS_history.user_id = ?
                    AND SS_items.status = 1
                ORDER BY SS_history.history_id DESC
                LIMIT 10;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}