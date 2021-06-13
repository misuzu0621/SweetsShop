<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'top_model.php';


$rows     = array();
$err_msgs = array();

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
    }

    // おすすめ商品データ取得(二次元連想配列)
    $rows = get_recommend_items($dbh);

} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}


// viewファイル読み込み
include_once VIEW_PATH . 'top_view.php';
