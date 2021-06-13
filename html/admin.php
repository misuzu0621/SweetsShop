<?php

// 設定ファイル読み込み
require_once '../conf/const.php';

// 関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';
require_once MODEL_PATH . 'admin_model.php';


$rows        = array();
$err_msgs    = array();
$success_msg = '';

try {
    // DB接続
    $dbh = get_db_connect();

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
        
        // 新規商品追加のとき
        if ($action === 'insert_item') {
            
            $name      = get_post_data('name');
            $price     = get_post_data('price');
            $tax       = get_post_data('tax');
            $stock     = get_post_data('stock');
            $type      = get_post_data('type');
            $recommend = get_post_data('recommend');
            $status    = get_post_data('status');
            
            // おすすめ商品に追加のチェックがないとき0を代入
            $recommend = not_recommend($recommend);
            
            // 画像ファイル名の取得と入力値チェック
            $result = validate_insert_item($name, $price, $tax, $stock, $type, $recommend, $status);
            if ($result['status'] === true) {
                $img = $result['img'];
            } else {
                $err_msgs = $result['err_msgs'];
            }
            
        // 商品画像変更のとき
        } else if ($action === 'update_img') {
            
            $item_id = get_post_data('item_id');
            
            // 画像ファイル名の取得と入力値チェック
            $result = validate_update_img();
            if ($result['status'] === true) {
                $img = $result['img'];
            } else {
                $err_msgs = $result['err_msgs'];
            }
            
        // 商品名変更のとき
        } else if ($action === 'update_name') {
            
            $name    = get_post_data('name');
            $item_id = get_post_data('item_id');
            
            // 入力値チェック
            $err_msgs = validate_update_name($name);
        
        // 価格変更のとき
        } else if ($action === 'update_price') {
            
            $price   = get_post_data('price');
            $item_id = get_post_data('item_id');
            
            // 入力値チェック
            $err_msgs = validate_update_price($price);
            
        // 税率変更のとき
        } else if ($action === 'update_tax') {
            
            $tax     = get_post_data('tax');
            $item_id = get_post_data('item_id');
            
            // 入力値チェック
            $err_msgs = validate_update_tax($tax);
            
        // 在庫数変更のとき
        } else if ($action === 'update_stock') {
            
            $stock   = get_post_data('stock');
            $item_id = get_post_data('item_id');
            
            // 入力値チェック
            $err_msgs = validate_update_stock($stock);
            
        // カテゴリ変更のとき
        } else if ($action === 'update_type') {
            
            $type    = get_post_data('type');
            $item_id = get_post_data('item_id');
            
            // 入力値チェック
            $err_msgs = validate_update_type($type);
            
        // おすすめ変更のとき
        } else if ($action === 'update_recommend') {
            
            $recommend = get_post_data('recommend');
            $item_id   = get_post_data('item_id');
            
            // おすすめ商品のチェックがないとき0を代入
            $recommend = not_recommend($recommend);
            
            // 入力値チェック
            $err_msgs = validate_update_recommend($recommend);
            
        // ステータス変更のとき
        } else if ($action === 'update_status') {
            
            $status  = get_post_data('status');
            $item_id = get_post_data('item_id');
            
            // 入力値チェック
            $err_msgs = validate_update_status($status);
            
        // 商品削除のとき
        } else if ($action === 'delete') {
            
            $item_id = get_post_data('item_id');
        }
        
        if (count($err_msgs) === 0) {
        
            // 新規商品追加のとき
            if ($action === 'insert_item') {
                
                // 新規商品追加
                insert_item($dbh, $name, $price, $tax, $stock, $type, $recommend, $img, $status);
                // 成功メッセージ取得
                $success_msg = get_success_msg($action);
                
            // 商品画像変更のとき
            } else if ($action === 'update_img') {
                
                update_img($dbh, $img, $item_id);
                $success_msg = get_success_msg($action);
                
            // 商品名変更のとき
            } else if ($action === 'update_name') {
                
                update_name($dbh, $name, $item_id);
                $success_msg = get_success_msg($action);
            
            // 価格変更のとき
            } else if ($action === 'update_price') {
                
                update_price($dbh, $price, $item_id);
                $success_msg = get_success_msg($action);
                
            // 税率変更のとき
            } else if ($action === 'update_tax') {
                
                update_tax($dbh, $tax, $item_id);
                $success_msg = get_success_msg($action);
                
            // 在庫数変更のとき
            } else if ($action === 'update_stock') {
                
                update_stock($dbh, $stock, $item_id);
                $success_msg = get_success_msg($action);
            
            // カテゴリ変更のとき
            } else if ($action === 'update_type') {
                
                update_type($dbh, $type, $item_id);
                $success_msg = get_success_msg($action);
                
            // おすすめ変更のとき
            } else if ($action === 'update_recommend') {
                
                update_recommend($dbh, $recommend, $item_id);
                $success_msg = get_success_msg($action);
                
            // ステータス変更のとき
            } else if ($action === 'update_status') {
                
                update_status($dbh, $status, $item_id);
                $success_msg = get_success_msg($action);
                
            // 商品削除のとき
            } else if ($action === 'delete') {
                
                delete_item($dbh, $item_id);
                $success_msg = get_success_msg($action);
            }
        }
    }

    // 商品一覧を取得
    $rows = get_itemlist($dbh);
    
} catch (PDOException $e) {
    $err_msgs[] = $e->getMessage();
}

// トークン生成
$token = get_token();


// viewファイル読み込み
include_once VIEW_PATH . 'admin_view.php';
