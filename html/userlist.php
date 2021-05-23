<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'userlist_model.php';


$rows     = array();
$err_msgs = array();

try {
    // DB接続
    $dbh = get_db_connect();
    
    // ユーザ一覧を取得
    $rows = get_userlist($dbh);
    
    // 特殊文字をHTMLエンティティに変換
    $rows = entity_assoc_array($rows);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}


// viewファイル読み込み
include_once VIEW_PATH . 'userlist_view.php';
