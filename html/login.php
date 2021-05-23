<?php

// 設定ファイル読み込み
require_once '../../include/SweetsShop/conf/const.php';

// 関数ファイル読み込み
require_once '../../include/SweetsShop/model/common_model.php';
require_once '../../include/SweetsShop/model/login_model.php';


session_start();

// セッション変数からログイン済みか確認
// セッション変数にユーザIDがセットされていたら商品一覧ページへリダイレクト
session_login();

// クッキー情報からユーザ名を取得
$username = cookie_get_username();

$rows     = array();
$err_msgs = array();

// リクエストメソッドを取得
$request_method = get_request_method();

if ($request_method === 'POST') {
    
    // POST値取得
    $username = get_post_data('username');
    $password = get_post_data('password');
    
    // ユーザ名をクッキーに保存
    setcookie('username', $username, time() + 60 * 60 * 24 * 365);
    
    // ユーザIDの取得
    try {
        // DB接続
        $dbh = get_db_connect();
        
        // ユーザIDを取得
        $rows = db_get_user_id($dbh, $username, $password);
    
    } catch (PDOException $e) {
        $err_msgs[] = $e->getMessage();
    }
    
    // 登録データを取得できたか確認、セッション変数にユーザIDを保存し、商品一覧ページへリダイレクト
    // 取得できない場合、エラーメッセージを取得
    $err_msgs[] = confirmation_user_id($rows);
}

try {
    // DB接続
    $dbh = get_db_connect();
    
    // おすすめ商品情報テーブルを取得
    $rows = db_get_recommend_items($dbh);
    
    // 特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}


// viewファイル読み込み
include_once '../../include/SweetsShop/view/login_view.php';
