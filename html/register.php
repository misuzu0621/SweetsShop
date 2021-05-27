<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'register_model.php';


// セッション開始
session_start();

// ログイン済のとき商品一覧ページへ
session_login();

$err_msgs  = array();
$db_insert = false;

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
    
    // POST値取得
    $username = get_post_data('username');
    $password = get_post_data('password');
    
    // 入力値チェック
    $err_msgs = validate_post_data($username, $password);
    
    if (count($err_msgs) === 0) {
        
        try {
            // DB接続
            $dbh = get_db_connect();
            
            // 同じユーザ名のユーザデータを取得
            $row = get_same_username($dbh, $username);
            
            // 同じユーザ名のユーザがいないとき
            if (empty($row)) {
                
                // ユーザデータ登録
                insert_user($dbh, $username, $password);
                $db_insert = true;
                
            // 同じユーザ名のユーザがいるとき
            } else {
                $err_msgs[] = 'そのユーザ名は既に使われています';
            }
            
        } catch (PDOException $e) {
            $err_msgs[] = $e->getMessage();
        }
    }
}

// トークン生成
$token = get_token();


// viewファイル読み込み
include_once VIEW_PATH . 'register_view.php';
