<?php

/**
 * リクエストメソッド取得
 * @return str   $request_method リクエストメソッド
 */
function get_request_method() {
    $request_method = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $request_method = $_SERVER['REQUEST_METHOD'];
    }
    return $request_method;
}

/**
 * POST値取得
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
 * DBハンドル取得
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
 * SQL文を実行してレコードを取得(二次元連想配列)
 * @param  obj   $dbh    DBハンドル
 * @param  str   $sql    SQL文
 * @param  array $params SQL文にバインドする値
 * @return array 取得したレコード
 */
function fetch_all_query($dbh, $sql, $params = array()) {
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    } catch (PCOException $e) {
        throw $e;
    }
}

/**
 * SQL文を実行してレコードを取得(連想配列)
 * @param  obj   $dbh    DBハンドル
 * @param  str   $sql    SQL文
 * @param  array $params SQL文にバインドする値
 * @return array 取得したレコード
 */
function fetch_query($dbh, $sql, $params = array()) {
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * SQL文を実行
 * @param  obj   $dbh    DBハンドル
 * @param  str   $sql    SQL文
 * @param  array $params SQL文にバインドする値
 */
function execute_query($dbh, $sql, $params = array()) {
    try {
        $stmt = $dbh->prepare($sql);
        $stmt->execute($params);
    } catch (PDOException $e) {
        throw $e;
    }
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
 * ログイン済のとき商品一覧ページへ
 */
function session_login() {
    if (isset($_SESSION['user_id'])) {
        header('Location: ' . ITEMLIST_URL);
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
        header('Location: ' . LOGIN_URL);
        exit;
    }
}

/**
 * ユーザ名取得(連想配列)
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @return array 取得したレコード
 */
function get_username($dbh, $user_id) {
    $sql = 'SELECT
                username
            FROM
                users
            WHERE
                user_id = ?';
    $params = array($user_id);
    return fetch_query($dbh, $sql, $params);
}

/**
 * ユーザ名取得, 取得できないときログアウトページへ
 * @param  array $row
 * @return str   $row['username']
 */
function confirmation_username($row) {
    if (isset($row['username'])) {
        return $row['username'];
    } else {
        header('Location: ' . LOGOUT_URL);
        exit;
    }
}

/**
 * 商品データ(在庫数)取得(連想配列)
 * @param  obj   $dbh     DBハンドル
 * @param  str   $item_id 商品ID
 * @return array 取得したレコード
 */
function get_stock($dbh, $item_id) {
    $sql = 'SELECT
                stock
            FROM
                items
            WHERE
                item_id = ?';
    $params = array($item_id);
    return fetch_query($dbh, $sql, $params);
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
 * カートデータ取得(連想配列)
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @param  str   $item_id 商品ID
 * @return array 取得したレコード
 */
function get_cart($dbh, $user_id, $item_id) {
    $sql = 'SELECT
                cart_id, amount
            FROM
                carts
            WHERE
                user_id = ?
                AND item_id = ?';
    $params = array($user_id, $item_id);
    return fetch_query($dbh, $sql, $params);
}

/**
 * カートデータ登録
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @param  str   $item_id 商品ID
 * @param  str   $amount  カートに入れる数量
 */
function insert_cart($dbh, $user_id, $item_id, $amount) {
    $sql = 'INSERT INTO carts
                (user_id, item_id, amount)
            VALUES
                (?, ?, ?)';
    $params = array($user_id, $item_id, $amount);
    execute_query($dbh, $sql, $params);
}

/**
 * カートデータアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $amount  カートに入れる数量
 * @param  str   $user_id ユーザID
 * @param  str   $item_id 商品ID
 * @param  array カートデータ
 */
function update_cart($dbh, $user_id, $item_id, $amount, $row) {
    $update_amount = $row['amount'] + $amount;
    $sql = 'UPDATE
                carts
            SET
                amount = ?,
                updatedate = NOW()
            WHERE
                user_id = ?
                AND item_id = ?';
    $params = array($update_amount, $user_id, $item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * カートデータ追加
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @param  str   $item_id 商品ID
 * @param  str   $amount  カートにいれる数量
 */
function add_cart($dbh, $user_id, $item_id, $amount) {
    $row = get_cart($dbh, $user_id, $item_id);
    if (empty($row)) {
        insert_cart($dbh, $user_id, $item_id, $amount);
    } else {
        update_cart($dbh, $user_id, $item_id, $amount, $row);
    }
}
