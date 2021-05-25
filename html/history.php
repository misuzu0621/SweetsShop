<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'history_model.php';


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
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// ユーザ名取得, 取得できないときログアウトページへ
$username = confirmation_username($row);

// リクエストメソッドがPOSTのとき
if (get_request_method() === 'POST') {
    
    // POST値取得
    $amount  = get_post_data('amount');
    $item_id = get_post_data('item_id');
    
    try {
        // 商品データ(在庫数)を取得(連想配列)
        $row = get_stock($dbh, $item_id);
        
    } catch (PDOException $e) {
        $err_msgs[] = $e->getMessage();
    }
    
    if (count($err_msgs) === 0) {
    
        // 入力値チェック
        $err_msgs = validate_into_cart($amount, $row['stock']);
    }
    
    if (count($err_msgs) === 0) {
        try {
            // カートデータ追加
            add_cart($dbh, $user_id, $item_id, $amount);
            
        } catch (PDOException $e) {
            $err_msgs[] = $e->getMessage();
        }
    }
}

try {
    // 購入履歴データ取得(二次元連想配列)
    $rows = get_history_items($dbh, $user_id);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}


// viewファイル読み込み
include_once VIEW_PATH . 'history_view.php';
