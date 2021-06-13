<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'itemlist_model.php';


// $_GET['type_id']取得
$type_id = get_get_data('type_id');
// カテゴリ取得
$category = get_category($type_id);

$rows     = array();
$err_msgs = array();
$success_msg = '';

// セッション開始
session_start();

// セッション変数からユーザID取得
$user_id = get_user_id();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // ログイン済のとき
    if ($user_id !== false) {
        
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
        
        // セッションに商品IDがあるとき
        } else if (isset($_SESSION['item_id'])) {
            
            // $_SESSION['item_id']取得
            $item_id = $_SESSION['item_id'];
            // $_SESSION['amount']取得
            $amount  = $_SESSION['amount'];
        }
        
        // リクエストメソッドがPOST、またはセッションに商品IDがあるとき
        if (get_request_method() === 'POST' || isset($_SESSION['item_id'])) {
            
            // 商品データ(在庫数)取得(連想配列)
            $row = get_stock($dbh, $item_id);
                
            // 入力値チェック
            $err_msgs = validate_into_cart($amount, $row['stock']);
    
            if (count($err_msgs) === 0) {
    
                // カートデータ追加
                add_cart($dbh, $user_id, $item_id, $amount);

                // 成功メッセージ代入
                $success_msg = 'カートに追加しました';
            }
            
            // セッションの商品ID・数量を削除
            unset($_SESSION['item_id']);
            unset($_SESSION['amount']);
        }
    
    // 未ログインかつカートに入れるボタンを押したとき
    } else if ($user_id === false && get_request_method() === 'POST') {
        
        // トークン取得
        $token = get_post_data('token');
        // トークンが正しくないとき
        if (is_valid_token($token) === false) {
            // ログインページへ
            redirect_to(LOGIN_URL);
        }
        // トークン破棄
        delete_token();
        
        // セッションに$_POST['amount']登録
        $_SESSION['amount']  = get_post_data('amount');
        // セッションに$_POST['item_id']登録
        $_SESSION['item_id'] = get_post_data('item_id');
        
        // ログインページへ
        redirect_to(LOGIN_URL);
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
