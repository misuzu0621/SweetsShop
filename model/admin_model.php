<?php

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
 * データベースに新規商品を追加
 * @param  obj   $dbh       DBハンドル
 * @param  str   $name      POST値
 * @param  str   $price     POST値
 * @param  str   $tax       POST値
 * @param  str   $stock     POST値
 * @param  str   $type      POST値
 * @param  str   $recommend POST値
 * @param  str   $img       POST値
 * @param  str   $status    POST値
 */
function insert_item($dbh, $name, $price, $tax, $stock, $type, $recommend, $img, $status) {
    try {
        $sql = 'INSERT INTO SS_items
                    (name, price, tax, stock, type, recommend, img, status, createdate, updatedate)
                VALUES
                    (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW());';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $price, PDO::PARAM_INT);
        $stmt->bindValue(3, $tax, PDO::PARAM_INT);
        $stmt->bindValue(4, $stock, PDO::PARAM_INT);
        $stmt->bindValue(5, $type, PDO::PARAM_INT);
        $stmt->bindValue(6, $recommend, PDO::PARAM_INT);
        $stmt->bindValue(7, $img, PDO::PARAM_STR);
        $stmt->bindValue(8, $status, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * データベースの商品画像をアップデートする
 * @param  obj   $dbh     DBハンドル
 * @param  str   $img     POST値
 * @param  str   $item_id POST値
 */
function update_img($dbh, $img, $item_id) {
    try {
        $sql = 'UPDATE
                    SS_items
                SET
                    img = ?,
                    updatedate = NOW()
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $img, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * データベースの商品名をアップデートする
 * @param  obj   $dbh     DBハンドル
 * @param  str   $name    POST値
 * @param  str   $item_id POST値
 */
function update_name($dbh, $name, $item_id) {
    try {
        $sql = 'UPDATE
                    SS_items
                SET
                    name = ?,
                    updatedate = NOW()
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $name, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * データベースの価格をアップデートする
 * @param  obj   $dbh     DBハンドル
 * @param  str   $price   POST値
 * @param  str   $item_id POST値
 */
function update_price($dbh, $price, $item_id) {
    try {
        $sql = 'UPDATE
                    SS_items
                SET
                    price = ?,
                    updatedate = NOW()
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $price, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * データベースの税率をアップデートする
 * @param  obj   $dbh     DBハンドル
 * @param  str   $tax     POST値
 * @param  str   $item_id POST値
 */
function update_tax($dbh, $tax, $item_id) {
    try {
        $sql = 'UPDATE
                    SS_items
                SET
                    tax = ?,
                    updatedate = NOW()
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $tax, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * データベースの在庫数をアップデートする
 * @param  obj   $dbh     DBハンドル
 * @param  str   $stock   POST値
 * @param  str   $item_id POST値
 */
function update_stock($dbh, $stock, $item_id) {
    try {
        $sql = 'UPDATE
                    SS_items
                SET
                    stock = ?,
                    updatedate = NOW()
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $stock, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * データベースのカテゴリをアップデートする
 * @param  obj   $dbh     DBハンドル
 * @param  str   $type    POST値
 * @param  str   $item_id POST値
 */
function update_type($dbh, $type, $item_id) {
    try {
        $sql = 'UPDATE
                    SS_items
                SET
                    type = ?,
                    updatedate = NOW()
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $type, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * データベースのおすすめをアップデートする
 * @param  obj   $dbh       DBハンドル
 * @param  str   $recommend POST値
 * @param  str   $item_id   POST値
 */
function update_recommend($dbh, $recommend, $item_id) {
    try {
        $sql = 'UPDATE
                    SS_items
                SET
                    recommend = ?,
                    updatedate = NOW()
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $recommend, PDO::PARAM_STR);
        $stmt->bindValue(2, $item_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * データベースのステータスをアップデートする
 * @param  obj   $dbh     DBハンドル
 * @param  str   $status  POST値
 * @param  str   $item_id POST値
 */
function update_status($dbh, $status, $item_id) {
    if ((int)$status === 0) {
        try {
            $sql = 'UPDATE
                        SS_items
                    SET
                        status = 1,
                        updatedate = NOW()
                    WHERE
                        item_id = ?;';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    } else {
        try {
            $sql = 'UPDATE
                        SS_items
                    SET
                        status = 0,
                        updatedate = NOW()
                    WHERE
                        item_id = ?;';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            throw $e;
        }
    }
}

/**
 * 商品を削除する
 * @param  obj   $dbh     DBハンドル
 * @param  str   $item_id POST値
 */
function delete_item($dbh, $item_id) {
    try {
        $sql = 'DELETE
                FROM
                    SS_items
                WHERE
                    item_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $item_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * 商品一覧を取得する
 * @param  obj   $dbh  DBハンドル
 * @return array $rows 商品一覧配列データ
 */
function get_itemlist($dbh) {
    try {
        $sql = 'SELECT
                    item_id, name, price, tax, stock, type, recommend, img, status
                FROM
                    SS_items;';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}