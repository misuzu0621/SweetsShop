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

$err_msgs = array();

try {
    // DB接続
    $dbh = get_db_connect();

    // リクエストメソッドがPOSTのとき
    if (get_request_method() === 'POST') {
        
        // トークン取得
        $token = get_post_data('token');
        // トークンが正しくないとき
        if (is_valid_token($token) === false) {
            // ログインページへ
            redirect_to(LOGIN_URL);
        }
        // トークン破棄
        delete_token();
        
        // $_POST['username']取得
        $username = get_post_data('username');
        // $_POST['password']取得
        $password = get_post_data('password');
        
        // ユーザ名をクッキーに保存
        setcookie('username', $username, time() + 60 * 60 * 24 * 365);
        
        // ユーザデータ(ユーザID)取得(連想配列)
        $row = get_user($dbh, $username, $password);
        
        // ユーザデータ(ユーザID)を取得できたとき、セッション変数にユーザIDを保存しトップページへ
        // 取得出来ないとき、エラーメッセージを取得
        $err_msgs[] = confirmation_user_id($row);
    }
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// トークン生成
$token = get_token();


// viewファイル読み込み
include_once VIEW_PATH . 'login_view.php';
