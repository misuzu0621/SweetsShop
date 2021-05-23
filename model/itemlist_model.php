<?php

/**
 * 商品一覧テーブルを取得する
 * @param  obj   $dbh  DBハンドル
 * @return array $rows 商品一覧情報配列
 */
function get_itemlist($dbh) {
    try {
        $sql = 'SELECT
                    item_id, name, price, tax, stock, img
                FROM
                    SS_items
                WHERE
                    status = 1;';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}