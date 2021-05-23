<?php

// 設定ファイル読み込み
require_once '../../include/SweetsShop/conf/const.php';

// 関数ファイル読み込み
require_once '../../include/SweetsShop/model/common_model.php';
require_once '../../include/SweetsShop/model/cart_model.php';


$rows     = array();
$err_msgs = array();

session_start();

// セッション変数からユーザIDを取得
// 非ログインの場合、ログインページへリダイレクト
$user_id = get_user_id();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // データーベースからユーザ名を取得
    $rows = get_username($dbh, $user_id);
    
    // 特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// ユーザ名が取得できたか確認
// 取得できない場合、ログアウト処理へリダイレクト
$username = confirmation_username($rows);

// リクエストメソッド取得
$request_method = get_request_method();

if ($request_method === 'POST') {
    $action = get_post_data('action');
    
    // 個数変更のとき
    if ($action === 'update_amount') {
        
        // POST値取得
        $amount  = get_post_data('amount');
        $cart_id = get_post_data('cart_id');
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
        
    // 商品削除のとき
    } else if ($action === 'delete') {
        
        // POST値取得
        $cart_id = get_post_data('cart_id');
        
    // 購入のとき
    } else if ($action === 'buy') {
        
        try {
            // DB接続
            $dbh = get_db_connect();
            
            // カートテーブルの商品情報を取得
            $rows = get_cart_status($dbh, $user_id);
            
        } catch (PDOException $e) {
            $err_msgs[] = $e->getMessage();
        }
        
        // 購入商品状態チェック
        $err_msgs = validate_cart_status($rows);
    }
    
    if (count($err_msgs) === 0) {
        try {
            // DB接続
            $dbh = get_db_connect();
            
            // 個数変更のとき
            if ($action === 'update_amount') {
                
                // カートテーブルの個数をアップデート
                update_amount($dbh, $amount, $cart_id);
                
            // 商品削除のとき
            } else if ($action === 'delete') {
                
                // カートテーブルの商品を削除する
                delete_item($dbh, $cart_id);
                
            // 購入のとき
            } else if ($action === 'buy') {
                
                // 商品一覧テーブルの在庫数をアップデート、カートテーブルの商品を削除、履歴テーブルに追加
                // 成功したら購入完了ページへリダイレクト
                buy($dbh, $user_id, $rows);
            }
        } catch (PDOException $e) {
            $err_msgs[] = $e->getMessage();
        }
    }
}

try {
    // DB接続
    $dbh = get_db_connect();
    
    // カートに入っている非公開の商品を削除、商品一覧を取得
    $rows = get_cart_items($dbh, $user_id);
    
    // 特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// 合計金額(税込)を取得
$sum = get_sum($rows);


// viewファイル読み込み
include_once '../../include/SweetsShop/view/cart_view.php';
