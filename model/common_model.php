<?php

/**
 * リクエストメソッドを取得する
 * @return str   $request_method
 */
function get_request_method() {
    $request_method = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $request_method = $_SERVER['REQUEST_METHOD'];
    }
    return $request_method;
}

/**
 * POST値を取得する
 * @param  str   $key 配列キー
 * @return str   $str
 */
function get_post_data($key) {
    $str = '';
    if (isset($_POST[$key])) {
        $str = $_POST[$key];
        $str = preg_replace('/^[ 　]/u', '', $str);
        $str = preg_replace('/[ 　]$/u', '', $str);
    }
    return $str;
}

/**
 * DBハンドルを取得
 * @return obj   $dbh DBハンドル
 */
function get_db_connect() {
    try {
        $dbh = new PDO(DSN, DB_USER, DB_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => DB_CHARSET));
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    } catch (PDOException $e) {
        throw $e;
    }
    return $dbh;
}

/**
 * 特殊文字をHTMLエンティティに変換する(2次元配列)
 * @param  array $assoc_array 変換前配列
 * @return array $assoc_array 変換後配列
 */
function entity_assoc_array($assoc_array) {
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            $assoc_array[$key][$keys] = htmlspecialchars($values, ENT_QUOTES, 'UTF-8');
        }
    }
    return $assoc_array;
}

/**
 * セッション変数にユーザIDがセットされていたら商品一覧ページへリダイレクト
 */
function session_login() {
    if (isset($_SESSION['user_id'])) {
        header('Location: itemlist.php');
        exit;
    }
}

/**
 * セッション変数からユーザIDを取得する
 * 非ログインの場合、ログインページへリダイレクト
 * @return str   $_SESSION['user_id']
 */
function get_user_id() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } else {
        header('Location: login.php');
        exit;
    }
}

/**
 * データベースからユーザ名を取得する
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @return array $rows    ユーザ情報配列
 */
function get_username($dbh, $user_id) {
    try {
        $sql = 'SELECT
                    username
                FROM
                    SS_users
                WHERE
                    user_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}

/**
 * ユーザ名が取得できたか確認
 * 取得できない場合、ログアウト処理へリダイレクト
 * @param  array $rows
 * @return str   $rows[0]['username']
 */
function confirmation_username($rows) {
    if (isset($rows[0]['username'])) {
        return $rows[0]['username'];
    } else {
        header('Location: logout.php');
        exit;
    }
}

/**
 * 商品一覧テーブルから在庫数を取得
 * @param  obj   $dbh     DBハンドル
 * @param  str   $item_id 商品ID
 * @return array $rows    在庫数
 */
function get_stock($dbh, $item_id) {
    try {
        $sql = 'SELECT
                    stock
                FROM
                    SS_items
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}

/**
 * 入力値チェック
 * @param  str   $amount   POST値
 * @return array $err_msgs エラーメッセージ
 */
function validate_into_cart($amount, $stock) {
    $err_msgs = array();
    if ($amount === '' || $amount === '0') {
        $err_msgs[] = '個数を入力してください';
    } else if (preg_match('/^[0-9]+$/', $amount) === 0) {
        $err_msgs[] = '個数は半角数字を入力してください';
    } else if ((int)$amount > (int)$stock) {
        $err_msgs[] = '商品が足りません';
    }
    return $err_msgs;
}

/**
 * データベースにカートに入れる情報を追加する
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @param  str   $item_id 商品ID
 * @param  str   $amount  個数
 */
function insert_cart($dbh, $user_id, $item_id, $amount) {
    $dbh->beginTransaction();
    try {
        $sql = 'SELECT
                    cart_id, amount
                FROM
                    SS_carts
                WHERE
                    user_id = ?
                    AND item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        if (empty($rows)) {
            $sql = 'INSERT INTO SS_carts
                        (user_id, item_id, amount, createdate, updatedate)
                    VALUES
                        (?, ?, ?, NOW(), NOW());';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
            $stmt->bindValue(3, $amount, PDO::PARAM_INT);
            $stmt->execute();
            
        } else {
            $new_amount = $rows[0]['amount'] + $amount;
            $sql = 'UPDATE
                        SS_carts
                    SET
                        amount = ?,
                        updatedate = NOW()
                    WHERE
                        user_id = ?
                        AND item_id = ?;';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $new_amount, PDO::PARAM_INT);
            $stmt->bindValue(2, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(3, $item_id, PDO::PARAM_INT);
            $stmt->execute();
        }
        $dbh->commit();
    } catch (PDOException $e) {
        $dbh->rollback();
        throw $e;
    }
}
