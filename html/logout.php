<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'logout_model.php';


// セッション開始
session_start();

// セッション名取得
$session_name = session_name();

// セッション変数を全て削除
$_SESSION = array();

// ユーザのクッキーに保存されているセッションIDを削除
delete_session_id($session_name);

// セッションIDを無効化
session_destroy();

// ログアウト処理が完了したらログインページへ
header('Location: ' . LOGIN_URL);
exit;
