<?php

// 汎用関数ファイル読み込み
require_once MODEL_PATH . 'common_model.php';


/**
 * カートの商品データ取得(二次元連想配列)
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @return array 取得したレコード
 */
function get_cart_items($dbh, $user_id) {
    $sql = 'SELECT
                carts.cart_id,
                carts.item_id,
                carts.amount,
                items.name,
                items.price,
                items.tax,
                items.stock,
                items.img,
                items.status
            FROM
                carts
                INNER JOIN items
                ON carts.item_id = items.item_id
            WHERE
                carts.user_id = ?
                AND items.status = 1';
    $params = array($user_id);
    return fetch_all_query($dbh, $sql, $params);
}

/**
 * 購入商品状態チェック
 * @param  array $rows     購入商品配列
 * @return array $err_msgs エラーメッセージ
 */
function validate_cart_status($rows) {
    $err_msgs = array();
    foreach ($rows as $row) {
        if ((int)$row['status'] === 0) {
            $err_msgs[] = '非公開の商品があります';
        }
        if ((int)$row['stock'] === 0) {
            $err_msgs[] = '売り切れの商品があります';
        }
        if ((int)$row['amount'] > (int)$row['stock']) {
            $err_msgs[] = '在庫数が足りない商品があります';
        }
    }
    return $err_msgs;
}

/**
 * カートデータ(数量)アップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $amount  カートに入れる数量
 * @param  str   $cart_id カートID
 */
function update_cart_amount($dbh, $amount, $cart_id) {
    $sql = 'UPDATE
                carts
            SET
                amount = ?,
                updatedate = NOW()
            WHERE
                cart_id = ?';
    $params = array($amount, $cart_id);
    execute_query($dbh, $sql, $params);
}

/**
 * カートの商品削除
 * @param  obj   $dbh     DBハンドル
 * @param  str   $cart_id カートID
 */
function delete_cart_item($dbh, $cart_id) {
    $sql = 'DELETE
            FROM
                carts
            WHERE
                cart_id = ?';
    $params = array($cart_id);
    execute_query($dbh, $sql, $params);
}

/**
 * 商品データ(在庫数)アップデート
 * @param  obj   $dbh  DBハンドル
 * @param  array カートデータ
 */
function update_items_stock($dbh, $rows) {
    foreach ($rows as $row) {
        $stock = $row['stock'] - $row['amount'];
        $sql = 'UPDATE
                    items
                SET
                    stock = ?,
                    updatedate = NOW()
                WHERE
                    item_id = ?';
        $params = array($stock, $row['item_id']);
        execute_query($dbh, $sql, $params);
    }
}

/**
 * カートデータ削除
 * @param  obj   $dbh  DBハンドル
 * @param  array カートデータ
 */
function delete_carts($dbh, $rows) {
    foreach ($rows as $row) {
        $sql = 'DELETE
                FROM
                    carts
                WHERE
                    cart_id = ?';
        $params = array($row['cart_id']);
        execute_query($dbh, $sql, $params);
    }
}

/**
 * 購入履歴登録
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * return  int   追加された注文ID
 */
function insert_orders($dbh, $user_id) {
    $sql = 'INSERT INTO orders
                (user_id)
            VALUES
                (?)';
    $params = array($user_id);
    execute_query($dbh, $sql, $params);
    return $dbh->lastInsertId();
}

/**
 * 購入明細登録
 * @param  obj   $dbh      DBハンドル
 * @param  int   $order_id 注文ID
 * @param  array $rows     カートデータ
 */
function insert_order_details($dbh, $order_id, $rows) {
    foreach ($rows as $row) {
        $sql = 'INSERT INTO order_details
                    (order_id, item_id, order_price, order_tax, amount)
                VALUES
                    (?, ?, ?, ?, ?)';
        $params = array($order_id, $row['item_id'], $row['price'], $row['tax'], $row['amount']);
        execute_query($dbh, $sql, $params);
    }
}

/**
 * 商品データ(在庫数)アップデート,カートデータ削除,購入履歴登録
 * 成功したら購入完了ページへリダイレクト
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @param  array $rows    カートの商品一覧配列
 */
function buy($dbh, $user_id, $rows) {
    try {
        $dbh->beginTransaction();
        update_items_stock($dbh, $rows);
        delete_carts($dbh, $rows);
        $order_id = insert_orders($dbh, $user_id);
        insert_order_details($dbh, $order_id, $rows);
        $dbh->commit();
    } catch (PDOException $e) {
        throw $e;
    }
    $_SESSION['order_id'] = $order_id;
    header('Location: ' . FINISH_URL);
    exit;
}

/**
 * 合計金額(税込)を取得
 * @param  array $rows 購入商品配列
 * @return int   $sum  合計金額(税込)
 */
function get_sum($rows) {
    $sum = 0;
    foreach ($rows as $row) {
        if ((int)$row['tax'] === 1) {
            $subtotal = (int)$row['price'] * (int)$row['amount'] * TAX8K;
        } else {
            $subtotal = (int)$row['price'] * (int)$row['amount'] * TAX10;
        }
        $sum += $subtotal;
    }
    return $sum;
}
