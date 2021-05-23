<?php

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
 * ユーザIDを取得する
 * @param  obj   $dbh      DBハンドル
 * @param  str   $username POST値
 * @param  str   $password POST値
 * @return array $row      ユーザ情報配列
 */
function db_get_user_id($dbh, $username, $password) {
    try {
        $sql = 'SELECT
                    user_id
                FROM
                    SS_users
                WHERE
                    username = ?
                    AND password = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $username, PDO::PARAM_STR);
        $stmt->bindValue(2, $password, PDO::PARAM_STR);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}

/**
 * 登録データを取得できたか確認、セッション変数にユーザIDを保存し、商品一覧ページへリダイレクト
 * 取得できない場合、エラーメッセージを取得
 * @param  array $rows     ユーザID配列
 * @return array $err_msgs エラーメッセージ
 */
function confirmation_user_id($rows) {
    $err_msg = '';
    if (isset($rows[0]['user_id'])) {
        $_SESSION['user_id'] = $rows[0]['user_id'];
        header('Location: ' . ITEMLIST_URL);
        exit;
    } else {
        $err_msg = 'ユーザ名またはパスワードが正しくありません';
    }
    return $err_msg;
}

/**
 * おすすめ商品情報テーブルを取得
 * @param  obj   $dbh  DBハンドル
 * @return array $rows おすすめ商品情報配列
 */
function db_get_recommend_items($dbh) {
    try {
        $sql = 'SELECT
                    name, img
                FROM
                    SS_items
                WHERE
                    recommend = 1
                    AND status = 1;';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}
