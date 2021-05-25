<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * おすすめ商品に追加のチェックがないとき0を代入する
 * @param  str   $recommend POST値
 * @return str   $recommend
 */
function not_recommend($recommend) {
    if ($recommend === '') {
        $recommend = '0';
    }
    return $recommend;
}

/**
 * 画像ファイルの取得と入力値チェック
 * @param  str   $name      POST値
 * @param  str   $price     POST値
 * @param  str   $tax       POST値
 * @param  str   $stock     POST値
 * @param  str   $type      POST値
 * @param  str   $recommend POST値
 * @param  str   $status    POST値
 * @return array $result
 */
function validate_insert_item($name, $price, $tax, $stock, $type, $recommend, $status) {
    $result = array(
        'status'   => false,
        'img'      => '',
        'err_msgs' => array()
    );
    $err_msgs = array();
    
    if ($name === '') {
        $err_msgs[] = '商品名を入力してください';
    }
    if ($price === '') {
        $err_msgs[] = '価格を入力してください';
    } else if (preg_match('/^[0-9]+$/', $price) === 0) {
        $err_msgs[] = '価格は半角数字を入力してください';
    }
    if ($tax === '') {
        $err_msgs[] = '税率を選択してください';
    } else if (preg_match('/^[12]$/', $tax) === 0) {
        $err_msgs[] = '税率が選択されていません';
    }
    if ($stock === '') {
        $err_msgs[] = '個数を入力してください';
    } else if (preg_match('/^[0-9]+$/', $stock) === 0) {
        $err_msgs[] = '個数は半角数字を入力してください';
    }
    if ($type === '') {
        $err_msgs[] = 'カテゴリを選択してください';
    } else if (preg_match('/^[1234]$/', $type) === 0) {
        $err_msgs[] = 'カテゴリが選択されていません';
    }
    if (preg_match('/^[01]$/', $recommend) === 0) {
        $err_msgs[] = 'おすすめ商品に追加が不正です';
    }
    if ($status === '') {
        $err_msgs[] = 'ステータスを選択してください';
    } else if (preg_match('/^[01]$/', $status) === 0) {
        $err_msgs[] = 'ステータスが選択されていません';
    }
    
    if (is_uploaded_file($_FILES['img']['tmp_name'])) {
        $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        if (preg_match('/jpg/i', $extension) === 1 || preg_match('/jpeg/i', $extension) === 1 || preg_match('/png/i', $extension) === 1) {
            $img = sha1(uniqid(mt_rand(), true)) . '.' . $extension;
            if (is_file(IMG_DIR . $img) === FALSE) {
                if (move_uploaded_file($_FILES['img']['tmp_name'], IMG_DIR . $img)) {
                    $result['status'] = true;
                    $result['img']    = $img;
                } else {
                    $err_msgs[] = 'ファイルアップロードに失敗しました';
                }
            } else {
                $err_msgs[] = 'ファイルアップロードに失敗しました。再度お試しください';
            }
        } else {
            $err_msgs[] = 'ファイル形式が異なります。画像ファイルはJPEGまたはPNGのみ利用可能です。';
        }
    } else {
        $err_msgs[] = 'ファイルを選択してください';
    }
    
    $result['err_msgs'] = $err_msgs;
    
    return $result;
}

/**
 * 画像ファイル名の取得と入力値チェック
 * @return array $result
 */
function validate_update_img() {
    $result = array(
        'status'   => false,
        'img'      => '',
        'err_msgs' => array()
    );
    $err_msgs = array();
    
    if (is_uploaded_file($_FILES['img']['tmp_name'])) {
        $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        if (preg_match('/jpg/i', $extension) === 1 || preg_match('/jpeg/i', $extension) === 1 || preg_match('/png/i', $extension) === 1) {
            $img = sha1(uniqid(mt_rand(), true)) . '.' . $extension;
            if (is_file(IMG_DIR . $img) === FALSE) {
                if (move_uploaded_file($_FILES['img']['tmp_name'], IMG_DIR . $img)) {
                    $result['status'] = true;
                    $result['img']    = $img;
                } else {
                    $err_msgs[] = 'ファイルアップロードに失敗しました';
                }
            } else {
                $err_msgs[] = 'ファイルアップロードに失敗しました。再度お試しください';
            }
        } else {
            $err_msgs[] = 'ファイル形式が異なります。画像ファイルはJPEGまたはPNGのみ利用可能です。';
        }
    } else {
        $err_msgs[] = 'ファイルを選択してください';
    }
    
    $result['err_msgs'] = $err_msgs;
    
    return $result;
}


/**
 * 入力値チェック
 * @param  str   $name POST値
 * @return array $err_msgs
 */
function validate_update_name($name) {
    $err_msgs = array();
    if ($name === '') {
        $err_msgs[] = '商品名を入力してください';
    }
    return $err_msgs;
}

/**
 * 入力値チェック
 * @param  str   $price POST値
 * @return array $err_msgs
 */
function validate_update_price($price) {
    $err_msgs = array();
    if ($price === '') {
        $err_msgs[] = '価格を入力してください';
    } else if (preg_match('/^[0-9]+$/', $price) === 0) {
        $err_msgs[] = '価格は半角数字を入力してください';
    }
    return $err_msgs;
}

/**
 * 入力値チェック
 * @param  str   $tax POST値
 * @return array $err_msgs
 */
function validate_update_tax($tax) {
    $err_msgs = array();
    if ($tax === '') {
        $err_msgs[] = '税率を選択してください';
    } else if (preg_match('/^[12]$/', $tax) === 0) {
        $err_msgs[] = '税率が選択されていません';
    }
    return $err_msgs;
}

/**
 * 入力値チェック
 * @param  str   $stock POST値
 * @return array $err_msgs
 */
function validate_update_stock($stock) {
    $err_msgs = array();
    if ($stock === '') {
        $err_msgs[] = '在庫数を入力してください';
    } else if (preg_match('/^[0-9]+$/', $stock) === 0) {
        $err_msgs[] = '在庫数は半角数字を入力してください';
    }
    return $err_msgs;
}

/**
 * 入力値チェック
 * @param  str   $type POST値
 * @return array $err_msgs
 */
function validate_update_type($type) {
    $err_msgs = array();
    if ($type === '') {
        $err_msgs[] = 'カテゴリを選択してください';
    } else if (preg_match('/^[1234]$/', $type) === 0) {
        $err_msgs[] = 'カテゴリが選択されていません';
    }
    return $err_msgs;
}

/**
 * 入力値チェック
 * @param  str   $recommend POST値
 * @return array $err_msgs
 */
function validate_update_recommend($recommend) {
    $err_msgs = array();
    if (preg_match('/^[01]$/', $recommend) === 0) {
        $err_msgs[] = 'おすすめ商品のチェックが不正です';
    }
    return $err_msgs;
}

/**
 * 入力値チェック
 * @param  str   $status POST値
 * @return array $err_msgs
 */
function validate_update_status($status) {
    $err_msgs = array();
    if ($status === '') {
        $err_msgs[] = 'ステータスを選択してください';
    } else if (preg_match('/^[01]$/', $status) === 0) {
        $err_msgs[] = 'ステータスが選択されていません';
    }
    return $err_msgs;
}

/**
 * 成功メッセージを取得する
 * @param  str   $action
 * @return str   $success_msg
 */
function get_success_msg($action) {
    $success_msg = '';
    if ($action === 'insert_item') {
        $success_msg = '新規商品追加成功しました';
    } else if ($action === 'update_img') {
        $success_msg = '商品画像変更成功しました';
    } else if ($action === 'update_name') {
        $success_msg = '商品名変更成功しました';
    } else if ($action === 'update_price') {
        $success_msg = '価格変更成功しました';
    } else if ($action === 'update_tax') {
        $success_msg = '税率変更成功しました';
    } else if ($action === 'update_stock') {
        $success_msg = '在庫数変更成功しました';
    } else if ($action === 'update_type') {
        $success_msg = 'カテゴリ変更成功しました';
    } else if ($action === 'update_recommend') {
        $success_msg = 'おすすめ変更成功しました';
    } else if ($action === 'update_status') {
        $success_msg = 'ステータス変更成功しました';
    } else if ($action === 'delete') {
        $success_msg = '商品削除成功しました';
    }
    return $success_msg;
}

/**
 * 新規商品登録
 * @param  obj   $dbh       DBハンドル
 * @param  str   $name      商品名
 * @param  str   $price     値段
 * @param  str   $tax       税率
 * @param  str   $stock     在庫数
 * @param  str   $type      カテゴリ
 * @param  str   $recommend おすすめ
 * @param  str   $img       画像ファイル名
 * @param  str   $status    ステータス
 */
function insert_item($dbh, $name, $price, $tax, $stock, $type, $recommend, $img, $status) {
    $sql = 'INSERT INTO items
                (name, price, tax, stock, type, recommend, img, status)
            VALUES
                (?, ?, ?, ?, ?, ?, ?, ?)';
    $params = array($name, $price, $tax, $stock, $type, $recommend, $img, $status);
    execute_query($dbh, $sql, $params);
}

/**
 * 商品画像をアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $img     画像ファイル名
 * @param  str   $item_id 商品ID
 */
function update_img($dbh, $img, $item_id) {
    $sql = 'UPDATE
                items
            SET
                img = ?,
                updatedate = NOW()
            WHERE
                item_id = ?';
    $params = array($img, $item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * 商品名をアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $name    商品名
 * @param  str   $item_id 商品ID
 */
function update_name($dbh, $name, $item_id) {
    $sql = 'UPDATE
                items
            SET
                name = ?,
                updatedate = NOW()
            WHERE
                item_id = ?';
    $params = array($name, $item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * 価格をアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $price   価格
 * @param  str   $item_id 商品ID
 */
function update_price($dbh, $price, $item_id) {
    $sql = 'UPDATE
                items
            SET
                price = ?,
                updatedate = NOW()
            WHERE
                item_id = ?';
    $params = array($price, $item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * 税率をアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $tax     税率
 * @param  str   $item_id 商品ID
 */
function update_tax($dbh, $tax, $item_id) {
    $sql = 'UPDATE
                items
            SET
                tax = ?,
                updatedate = NOW()
            WHERE
                item_id = ?';
    $params = array($tax, $item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * 在庫数をアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $stock   在庫数
 * @param  str   $item_id 商品ID
 */
function update_stock($dbh, $stock, $item_id) {
    $sql = 'UPDATE
                items
            SET
                stock = ?,
                updatedate = NOW()
            WHERE
                item_id = ?';
    $params = array($stock, $item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * カテゴリをアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $type    カテゴリ
 * @param  str   $item_id 商品ID
 */
function update_type($dbh, $type, $item_id) {
    $sql = 'UPDATE
                items
            SET
                type = ?,
                updatedate = NOW()
            WHERE
                item_id = ?';
    $params = array($type, $item_id);
}

/**
 * おすすめをアップデート
 * @param  obj   $dbh       DBハンドル
 * @param  str   $recommend おすすめ
 * @param  str   $item_id   商品ID
 */
function update_recommend($dbh, $recommend, $item_id) {
    $sql = 'UPDATE
                items
            SET
                recommend = ?,
                updatedate = NOW()
            WHERE
                item_id = ?';
    $params = array($recommend, $item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * ステータスをアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $status  ステータス
 * @param  str   $item_id 商品ID
 */
function update_status($dbh, $status, $item_id) {
    if ((int)$status === 0) {
        $sql = 'UPDATE
                    items
                SET
                    status = 1,
                    updatedate = NOW()
                WHERE
                    item_id = ?';
    } else {
        $sql = 'UPDATE
                    items
                SET
                    status = 0,
                    updatedate = NOW()
                WHERE
                    item_id = ?';
    }
    $params = array($item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * 商品削除
 * @param  obj   $dbh     DBハンドル
 * @param  str   $item_id 商品ID
 */
function delete_item($dbh, $item_id) {
    $sql = 'DELETE
            FROM
                items
            WHERE
                item_id = ?';
    $params = array($item_id);
    execute_query($dbh, $sql, $params);
}

/**
 * 商品一覧取得(二次元連想配列)
 * @param  obj   $dbh  DBハンドル
 * @return array 取得したレコード
 */
function get_itemlist($dbh) {
    $sql = 'SELECT
                item_id, name, price, tax, stock, type, recommend, img, status
            FROM
                items';
    return fetch_all_query($dbh, $sql, $params);
}
