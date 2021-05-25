<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * 入力値チェック
 * @param  str   $username ユーザ名
 * @param  str   $password パスワード
 * @return array $err_msgs エラーメッセージ
 */
function validate_post_data($username, $password) {
    $err_msgs = array();
    if ($username === '') {
        $err_msgs[] = 'ユーザ名を入力してください';
    } else if (preg_match('/^[a-zA-Z0-9]{5}[a-zA-Z0-9]+$/', $username) === 0) {
        $err_msgs[] = 'ユーザ名は半角英数字6文字以上で入力してください';
    }
    if ($password === '') {
        $err_msgs[] = 'パスワードを入力してください';
    } else if (preg_match('/^[a-zA-Z0-9]{5}[a-zA-Z0-9]+$/', $password) === 0) {
        $err_msgs[] = 'パスワードは半角英数字6文字以上で入力してください';
    }
    return $err_msgs;
}

/**
 * $usernameと同じユーザ名のユーザIDを取得(連想配列)
 * @param  obj   $dbh      DBハンドル
 * @param  str   $username ユーザ名
 * @return array 取得したレコード
 */
function get_same_username($dbh, $username) {
    $sql = 'SELECT
                user_id
            FROM
                users
            WHERE
                username = ?';
    $params = array($username);
    return fetch_query($dbh, $sql, $params);
}

/**
 * ユーザデータ登録
 * @param  obj   $dbh      DBハンドル
 * @param  str   $username ユーザ名
 * @param  str   $password パスワード
 */
function insert_user($dbh, $username, $password) {
    $sql = 'INSERT INTO users
                (username, password)
            VALUES
                (?, ?)';
    $params = array($username, $password);
    execute_query($dbh, $sql, $params);
}
