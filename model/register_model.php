<?php

/**
 * 入力値チェック
 * @param  str   $username POST値
 * @param  str   $password POST値
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
 * $usernameと同じユーザ名のユーザIDを取得
 * @param  obj   $dbh      DBハンドル
 * @param  str   $username POST値
 * @return array $row      ユーザID配列
 */
function get_same_username($dbh, $username) {
    try {
        $sql = 'SELECT
                    user_id
                FROM
                    SS_users
                WHERE
                    username = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}

/**
 * データベースにユーザ情報を登録する
 * @param  obj   $dbh      DBハンドル
 * @param  str   $username POST値
 * @param  str   $password POST値
 */
function insert_user($dbh, $username, $password) {
    try {
        $sql = 'INSERT INTO SS_users
                    (username, password, createdate, updatedate)
                VALUES
                    (?, ?, NOW(), NOW());';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $password, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}
