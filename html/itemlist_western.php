<?php

// 設定ファイル読み込み
require_once '../../include/SweetsShop/conf/const.php';

// 関数ファイル読み込み
require_once '../../include/SweetsShop/model/common_model.php';
require_once '../../include/SweetsShop/model/itemlist_western_model.php';


$category = '洋菓子';
$rows     = array();
$err_msgs = array();

session_start();

// セッション変数からユーザIDを取得
// 非ログインの場合、ログインページへリダイレクト
$user_id = get_user_id();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // データベースからユーザ名を取得
    $rows = get_username($dbh, $user_id);
    
    // 特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// ユーザ名を取得できたか確認
// 取得できない場合、ログアウト処理へリダイレクト
$username = confirmation_username($rows);

// リクエストメソッドを取得
$request_method = get_request_method();

if ($request_method === 'POST') {
    
    // POST値取得
    $amount  = get_post_data('amount');
    $item_id = get_post_data('item_id');
    
    try {
        // DB接続
        $dbh = get_db_connect();
        
        // 商品一覧テーブルから在庫数を取得
        $rows = get_stock($dbh, $item_id);
        
    } catch (PDOException $e) {
        $err_msgs[] = $e->getMessage();
    }
    
    if (count($err_msgs) === 0) {
    
        $stock = $rows[0]['stock'];
        
        // 入力値チェック
        $err_msgs = validate_into_cart($amount, $stock);
    }
    
    if (count($err_msgs) === 0) {
        try {
            // DB接続
            $dbh = get_db_connect();
            
            // データベースにカートに入れる商品情報を追加
            insert_cart($dbh, $user_id, $item_id, $amount);
            
        } catch (PDOException $e) {
            $err_msgs[] = $e->getMessage();
        }
    }
}

try {
    // DB接続
    $dbh = get_db_connect();
    
    // データベースから洋菓子の商品一覧テーブルを取得
    $rows = get_itemlist_western($dbh);
    
    // 特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}



// viewファイル読み込み
include_once '../../include/SweetsShop/view/itemlist_view.php';
