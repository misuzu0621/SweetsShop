<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * おすすめの商品データ取得(二次元連想配列)
 * @param  obj   $dbh  DBハンドル
 * @return array 取得したレコード
 */
function get_recommend_items($dbh) {
    $sql = 'SELECT
                name, img
            FROM
                items
            WHERE
                recommend = 1
                AND status = 1';
    return fetch_all_query($dbh, $sql);
}
