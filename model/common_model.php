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
 * @return str   $_SESSION['user_id']
 * @return bool  false
 */
function get_user_id() {
    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    } else {
        return false;
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

/**
 * 特殊文字をエスケープ処理
 * @param  str   $str 文字列
 * @return str   エスケープ処理された文字列
 */
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

/**
 * トークン生成
 * return str   30文字のランダムな文字列
 */
function get_token() {
    $token = substr(base_convert(hash('sha256', uniqid()), 16, 36), 0, 30);
    $_SESSION['token'] = $token;
    return $token;
}

/**
 * トークンチェック
 * @param  str   $token トークン
 * @return bool
 */
function is_valid_token($token) {
    if ($token === $_SESSION['token']) {
        return true;
    } else {
        return false;
    }
}

/**
 * トークン破棄
 */
function delete_token() {
    unset($_SESSION['token']);
}

/**
 * リダイレクト
 * @param  str   $url URL
 */
function redirect_to($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * 税込価格を商品データに追加
 * @param  array $rows                       商品データ配列
 * @param  str   $price_key_name             価格のキー名
 * @param  str   $tax_key_name               税率のキー名
 * @param  str   $tax_include_price_key_name 税込価格のキー名
 * @return array $rows                       税込価格を追加した商品データ配列
 */
function get_tax_include_prices($rows, $price_key_name, $tax_key_name, $tax_include_price_key_name) {
    foreach ($rows as $key => $row) {
        if ($row[$tax_key_name] === 1) {
            $tax_include_price = $row[$price_key_name] * TAX8K;
        } else {
            $tax_include_price = $row[$price_key_name] * TAX10;
        }
        $rows[$key][$tax_include_price_key_name] = (int)$tax_include_price;
    }
    return $rows;
}

/**
 * 税込小計金額を商品データに追加
 * @param  array $rows                       商品データ配列
 * @param  str   $tax_include_price_key_name 税込価格のキー名
 * @return array $rows                       税込小計金額を追加した商品データ
 */
function get_subtotal_price($rows, $tax_include_price_key_name) {
    foreach ($rows as $key => $row) {
        $rows[$key]['subtotal_price'] = $row[$tax_include_price_key_name] * $row['amount'];
    }
    return $rows;
}

/**
 * 税込合計金額取得
 * @param  array $rows        商品データ配列
 * @return int   $total_price 税込合計金額
 */
function get_total_price($rows) {
    $tax_include_total_price = 0;
    foreach ($rows as $row) {
        $total_price += $row['subtotal_price'];
    }
    return $total_price;
}
