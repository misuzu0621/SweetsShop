<?php

define('MODEL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/../model/'); // 関数ファイルのディレクトリ
define('VIEW_PATH' , $_SERVER['DOCUMENT_ROOT'] . '/../view/');  // ビューファイルのディレクトリ

//define('ITEM_IMAGE_DIR' ,　$_SERVER['DOCUMENT_ROOT'] . '/item_img/');  // 商品画像のディレクトリ
define('ITEM_IMAGE_PATH', '/item_img/');                               // 商品画像のディレクトリ
define('IMAGE_PATH'     , '/img/');                                    // 画像のディレクトリ
define('STYLESHEET_PATH', '/css/');                                    // スタイルシートのディレクトリ
define('SCRIPT_PATH'    , '/script/');                                 // JavaScriptのディレクトリ

define('HOST', 'mysql');
define('DB_USER', 'testuser');        // MySQLのユーザ名
define('DB_PASS', 'password');        // MySQLのパスワード
define('DB_NAME', 'sample');        // MySQLのDB名
define('DB_CHARSET', 'SET NAMES utf8mb4'); // MySQLのcharset
define('CHARSET', 'utf8');

define('DSN', 'mysql:dbname=' . DB_NAME . ';host=' . HOST . ';charset=' . CHARSET);

define('REGISTER_URL'          , '/register.php');           // ユーザ登録ページ
define('LOGIN_URL'             , '/login.php');              // ログインページ
define('ADMIN_URL'             , '/admin.php');              // 管理ページ
define('USERLIST_URL'          , '/userlist.php');           // ユーザリストページ
define('ITEMLIST_URL'          , '/itemlist.php');           // 商品一覧ページ
define('ITEMLIST_BAKED_URL'    , '/itemlist.php?type_id=1'); // 焼き菓子の商品一覧ページ
define('ITEMLIST_CHOCOLATE_URL', '/itemlist.php?type_id=2'); // ショコラの商品一覧ページ
define('ITEMLIST_WESTERN_URL'  , '/itemlist.php?type_id=3'); // 洋菓子の商品一覧ページ
define('ITEMLIST_JAPANESE_URL' , '/itemlist.php?type_id=4'); // 和菓子の商品一覧ページ
define('CART_URL'              , '/cart.php');               // カートページ
define('HISTORY_URL'           , '/history.php');            // 購入履歴ページ
define('FINISH_URL'            , '/finish.php');             // 購入完了ページ
define('LOGOUT_URL'            , '/logout.php');             // ログアウトページ

define('TAX8K', 1.08);
define('TAX10', 1.1);
