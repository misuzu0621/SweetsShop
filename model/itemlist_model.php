<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * GET値取得
 * @param  str   $str    配列キー
 * @return str   $string GET値
 */
function get_get_data($str) {
    $string = '';
    if (isset($_GET[$str])) {
        $string = $_GET[$str];
    }
    return $string;
}

/**
 * カテゴリ取得
 * @param  str   $type_id  GET値
 * @return str   $category カテゴリ
 */
function get_category($type_id) {
    if ($type_id === '1') {
        $category = '焼き菓子';
    } else if ($type_id === '2') {
        $category = 'ショコラ';
    } else if ($type_id === '3') {
        $category = '洋菓子';
    } else if ($type_id === '4') {
        $category = '和菓子';
    } else {
        $category = '商品一覧';
    }
    return $category;
}

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
