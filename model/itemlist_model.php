<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * 商品データ取得(二次元連想配列)
 * @param  obj   $dbh      DBハンドル
 * @param  str   $category カテゴリ名
 * @return array 取得したレコード
 */
function get_itemlist($dbh, $category) {
    $sql = 'SELECT
                item_id, name, price, tax, stock, img
            FROM
                items
            WHERE
                status = 1';
    if ($category === '焼き菓子') {
        $sql .= ' AND type = 1';
    } else if ($category === 'ショコラ') {
        $sql .= ' AND type = 2';
    } else if ($category === '洋菓子') {
        $sql .= ' AND type = 3';
    } else if ($category === '和菓子') {
        $sql .= ' AND type = 4';
    }
    return fetch_all_query($dbh, $sql);
}
