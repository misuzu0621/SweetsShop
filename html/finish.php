<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'finish_model.php';


$rows     = array();
$err_msgs = array();

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
    
    // ユーザデータ(ユーザ名)取得
    $row = get_username($dbh, $user_id);

    // ユーザ名取得, 取得できないときログアウトページへ
    $username = confirmation_username($row);
    
    // 購入完了のとき$_SESSION['history_id']取得, そうでないときカートページへ
    $order_id = get_order_id();

    // 購入した商品データ取得
    $rows = get_buy_items($dbh, $order_id);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// 合計金額(税込)を取得
$sum = get_sum($rows);


// viewファイル読み込み
include_once VIEW_PATH . 'finish_view.php';
