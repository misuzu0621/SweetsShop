<?php

/**
 * データベースから焼き菓子の商品一覧テーブルを取得する
 * @param  obj   $dbh  DBハンドル
 * @return array $rows 商品一覧配列
 */
function get_itemlist_baked($dbh) {
    try {
        $sql = 'SELECT
                    item_id, name, price, tax, stock, img
                FROM
                    SS_items
                WHERE
                    type = 1
                    AND status = 1;';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}