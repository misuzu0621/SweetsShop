<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'finish_model.php';


$rows = array();

session_start();

// セッション変数からユーザIDを取得
// 非ログインの場合、ログインページへリダイレクト
$user_id = get_user_id();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // ユーザテーブルからユーザ名を取得
    $rows = get_username($dbh, $user_id);
    
    // 特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);
    
} catch (PDOException $e) {
    throw $e;
}

// ユーザ名が取得できたか確認
// 確認できない場合、ログアウト処理へリダイレクト
$username = confirmation_username($rows);

// セッション変数から購入完了かどうか確認、セッション変数のbuyを削除
// 購入完了していない場合、ショッピングカートページへリダイレクト
$history_id = confirmation_history_id();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // 購入した商品一覧を取得
    $rows = get_buy_items($dbh, $history_id);
    
    // 特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// 合計金額(税込)を取得
$sum = get_sum($rows);


// viewファイル読み込み
include_once VIEW_PATH . 'finish_view.php';
