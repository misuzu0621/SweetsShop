<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * クッキー情報からユーザ名を取得する
 * @return str   $username ユーザ名
 */
function cookie_get_username() {
    $username = '';
    if (isset($_COOKIE['username'])) {
        $username = $_COOKIE['username'];
    }
    return $username;
}

/**
 * ユーザデータ(ユーザID)取得(連想配列)
 * @param  obj   $dbh      DBハンドル
 * @param  str   $username ユーザ名
 * @param  str   $password パスワード
 * @return array 取得したレコード
 */
function get_user($dbh, $username, $password) {
    $sql = 'SELECT
                user_id
            FROM
                users
            WHERE
                username = ?
                AND password = ?';
    $params = array($username, $password);
    return fetch_query($dbh, $sql, $params);
}

/**
 * ユーザデータ(ユーザID)を取得できたとき、セッション変数にユーザIDを保存し商品一覧ページへ
 * 取得出来ないとき、エラーメッセージ取得
 * @param  array $row      ユーザデータ(ユーザID)
 * @return array $err_msgs エラーメッセージ
 */
function confirmation_user_id($row) {
    $err_msg = '';
    if (isset($row['user_id'])) {
        $_SESSION['user_id'] = $row['user_id'];
        header('Location: ' . ITEMLIST_URL);
        exit;
    } else {
        $err_msg = 'ユーザ名またはパスワードが正しくありません';
    }
    return $err_msg;
}

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
