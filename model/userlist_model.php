<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * ユーザ一覧取得(二次元連想配列)
 * @param  obj   $dbh  DBハンドル
 * @return array 取得したレコード
 */
function get_userlist($dbh) {
    $sql = 'SELECT
                user_id, username, password
            FROM
                SS_users';
    return fetch_all_query($dbh, $sql);
}
