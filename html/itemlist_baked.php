<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'itemlist_model.php';


$category = '焼き菓子';
$rows     = array();
$err_msgs = array();

// セッション開始
session_start();

// セッション変数からユーザID取得, 非ログインのときログインページへ
$user_id = get_user_id();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // ユーザデータ(ユーザ名)取得(連想配列)
    $row = get_username($dbh, $user_id);

    // ユーザ名取得, 取得できないときログアウトページへ
    $username = confirmation_username($row);
    
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
        
        // $_POST['amount']取得
        $amount  = get_post_data('amount');
        // $_POST['item_id']取得
        $item_id = get_post_data('item_id');
        
        // 商品データ(在庫数)取得(連想配列)
        $row = get_stock($dbh, $item_id);
            
        // 入力値チェック
        $err_msgs = validate_into_cart($amount, $row['stock']);

        if (count($err_msgs) === 0) {

            // カートデータ追加
            add_cart($dbh, $user_id, $item_id, $amount);
        }
    }

    // 商品一覧取得(二次元連想配列)
    $rows = get_itemlist($dbh, $category);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// トークン生成
$token = get_token();


// viewファイル読み込み
include_once VIEW_PATH . 'itemlist_view.php';
