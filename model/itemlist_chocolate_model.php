<?php

/**
 * データベースからショコラの商品一覧テーブルを取得
 * @param  obj   $dbh  DBハンドル
 * @return array $rows 商品一覧配列
 */
function get_itemlist_chocolate($dbh) {
    try {
        $sql = 'SELECT
                    item_id, name, price, tax, stock, img
                FROM
                    SS_items
                WHERE
                    type = 2
                    AND status = 1;';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}