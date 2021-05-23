<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'register_model.php';


session_start();

// セッション変数からログイン済みか確認
// セッション変数にユーザIDがセットされていたら商品一覧ページへリダイレクト
session_login();

$err_msgs  = array();
$db_insert = false;

// リクエストメソッドを取得
$request_method = get_request_method();

if ($request_method === 'POST') {
    
    // POST値取得
    $username = get_post_data('username');
    $password = get_post_data('password');
    
    // 入力値チェック
    $err_msgs = validate_post_data($username, $password);
    
    if (count($err_msgs) === 0) {
        
        try {
            // DB接続
            $dbh = get_db_connect();
            
            // データベースから同じユーザ名のユーザIDを取得
            $rows = get_same_username($dbh, $username);
            
            // 取得した配列が空のとき
            if (empty($rows)) {
                
                // データベースにユーザ情報を登録
                insert_user($dbh, $username, $password);
                $db_insert = true;
                
            // 取得した配列が空でないとき
            } else {
                $err_msgs[] = 'そのユーザ名は既に使われています';
            }
            
        } catch (PDOException $e) {
            $err_msgs[] = $e->getMessage();
        }
    }
}


// viewファイル読み込み
include_once VIEW_PATH . 'register_view.php';
