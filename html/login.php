<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'login_model.php';


// セッション開始
session_start();

// ログイン済のとき商品一覧ページへ
session_login();

// クッキーからユーザ名を取得
$username = cookie_get_username();

$rows     = array();
$err_msgs = array();

try {
    // DB接続
    $dbh = get_db_connect();
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// リクエストメソッドがPOSTのとき
if (get_request_method() === 'POST') {
    
    // POST値取得
    $username = get_post_data('username');
    $password = get_post_data('password');
    
    // ユーザ名をクッキーに保存
    setcookie('username', $username, time() + 60 * 60 * 24 * 365);
    
    try {
        // ユーザデータ(ユーザID)取得(連想配列)
        $row = get_user($dbh, $username, $password);
        
    } catch (PDOException $e) {
        $err_msgs[] = $e->getMessage();
    }
    
    // ユーザデータ(ユーザID)を取得できたとき、セッション変数にユーザIDを保存し商品一覧ページへ
    // 取得出来ないとき、エラーメッセージを取得
    $err_msgs[] = confirmation_user_id($row);
}

try {
    // おすすめ商品データ取得(二次元連想配列)
    $rows = get_recommend_items($dbh);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}


// viewファイル読み込み
include_once VIEW_PATH . 'login_view.php';
