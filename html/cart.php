<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'cart_model.php';


$rows     = array();
$err_msgs = array();
$success_msg = '';

// セッション開始
session_start();

// セッション変数からユーザID取得
$user_id = get_user_id();
// 取得できないときログインページへ
if ($user_id === false) {
    redirect_to(LOGIN_URL);
}

try {
    // DB接続
    $dbh = get_db_connect();
    
    // ユーザデータ(ユーザ名)取得(連想配列)
    $row = get_username($dbh, $user_id);

    // ユーザ名取得, 取得出来ないときログアウトページへ
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
        
        // $_POST['action']取得
        $action = get_post_data('action');
        
        // 個数変更のとき
        if ($action === 'update_amount') {
            
            // $_POST['amount']取得
            $amount  = get_post_data('amount');
            // $_POST['cart_id']取得
            $cart_id = get_post_data('cart_id');
            // $_POST['item_id']取得
            $item_id = get_post_data('item_id');
            
            // 商品データ(在庫数)取得(連想配列)
            $row = get_stock($dbh, $item_id);
            
            // 入力値チェック
            $err_msgs = validate_into_cart($amount, $row['stock']);
            
        // 商品削除のとき
        } else if ($action === 'delete') {
            
            // $_POST['cart_id']取得
            $cart_id = get_post_data('cart_id');
            
        // 購入のとき
        } else if ($action === 'buy') {
            
            // カートの商品データ取得(二次元連想配列)
            $rows = get_cart_items($dbh, $user_id);
            
            // 購入商品状態チェック
            $err_msgs = validate_cart_status($rows);
        }
        
        if (count($err_msgs) === 0) {
            
            // 個数変更のとき
            if ($action === 'update_amount') {
                
                // カートデータ(数量)アップデート
                update_cart_amount($dbh, $amount, $cart_id);

                // 成功メッセージ
                $success_msg = '個数を変更しました';
                
            // 商品削除のとき
            } else if ($action === 'delete') {
                
                // カートの商品削除
                delete_cart_item($dbh, $cart_id);

                // 成功メッセージ代入
                $success_msg = 'カートから削除しました';
                
            // 購入のとき
            } else if ($action === 'buy') {
                
                // 商品データ(在庫数)アップデート,カートデータ削除,購入履歴登録, 成功したら購入完了ページへ
                buy($dbh, $user_id, $rows);
            }
        }
    }

    // カートの商品データ取得(二次元連想配列)
    $rows = get_cart_items($dbh, $user_id);

    // 税込価格を商品データに追加
    $rows = get_tax_include_prices($rows, 'price', 'tax', 'tax_include_price');

    // 税込小計金額を商品データに追加
    $rows = get_subtotal_price($rows, 'tax_include_price');
    
    // 税込合計金額取得
    $total_price = get_total_price($rows);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// トークン生成
$token = get_token();


// viewファイル読み込み
include_once VIEW_PATH . 'cart_view.php';
