<?php

/**
 * カートテーブルの商品情報を取得
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @return array $rows    商品一覧配列
 */
function get_cart_status($dbh, $user_id) {
    try {
        $sql = 'SELECT
                    SS_carts.item_id,
                    SS_carts.amount,
                    SS_items.price,
                    SS_items.tax,
                    SS_items.stock,
                    SS_items.status
                FROM
                    SS_carts
                    INNER JOIN SS_items
                    ON SS_carts.item_id = SS_items.item_id
                WHERE
                    SS_carts.user_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
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
 * カートテーブルの個数をアップデート
 * @param  obj   $dbh     DBハンドル
 * @param  str   $amount  個数
 * @param  str   $cart_id カートID
 */
function update_amount($dbh, $amount, $cart_id) {
    try {
        $sql = 'UPDATE
                    SS_carts
                SET
                    amount = ?,
                    updatedate = NOW()
                WHERE
                    cart_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $amount, PDO::PARAM_INT);
        $stmt->bindValue(2, $cart_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * カートテーブルの商品を削除
 * @param  obj   $dbh     DBハンドル
 * @param  str   $cart_id カートID
 */
function delete_item($dbh, $cart_id) {
    try {
        $sql = 'DELETE
                FROM
                    SS_carts
                WHERE
                    cart_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $cart_id, PDO::PARAM_INT);
        $stmt->execute();
    } catch (PDOException $e) {
        throw $e;
    }
}

/**
 * 商品一覧テーブルの在庫数をアップデート、カートテーブルを削除、履歴テーブルに追加
 * 成功したら購入完了ページへリダイレクト
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @param  array $rows    カートの商品一覧配列
 */
function buy($dbh, $user_id, $rows) {
    $history_id = array();
    foreach ($rows as $row) {
        $stock = $row['stock'] - $row['amount'];
        $dbh->beginTransaction();
        try {
            $sql = 'UPDATE
                        SS_items
                    SET
                        stock = ?,
                        updatedate = NOW()
                    WHERE
                        item_id = ?;';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $stock, PDO::PARAM_INT);
            $stmt->bindValue(2, $row['item_id'], PDO::PARAM_INT);
            $stmt->execute();
            
            $sql = 'DELETE
                    FROM
                        SS_carts
                    WHERE
                        user_id = ?;';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            $sql = 'INSERT INTO SS_history
                        (user_id, item_id, price_history, tax_history, amount, createdate)
                    VALUES
                        (?, ?, ?, ?, ?, NOW());';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $row['item_id'], PDO::PARAM_INT);
            $stmt->bindValue(3, $row['price'], PDO::PARAM_INT);
            $stmt->bindValue(4, $row['tax'], PDO::PARAM_INT);
            $stmt->bindValue(5, $row['amount'], PDO::PARAM_INT);
            $stmt->execute();
            
            $history_id[] = $dbh->lastInsertId();
            
            $dbh->commit();
        } catch (PDOException $e) {
            $dbh->rollback();
            throw $e;
        }
    }
    $_SESSION['history_id'] = $history_id;
    header('Location: finish.php');
    exit;
}

/**
 * カートに入っている非公開の商品を削除、カートに入れた商品一覧を取得
 * @param  obj   $dbh     DBハンドル
 * @param  str   $user_id ユーザID
 * @return array $rows    商品一覧配列
 */
function get_cart_items($dbh, $user_id) {
    $dbh->beginTransaction();
    try {
        $sql = 'SELECT
                    item_id
                FROM
                    SS_items
                WHERE
                    status = 0;';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        foreach ($rows as $row) {
            $sql = 'DELETE
                    FROM
                        SS_carts
                    WHERE
                        user_id = ?
                        AND item_id = ?;';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $row['item_id'], PDO::PARAM_INT);
            $stmt->execute();
        }
        
        $sql = 'SELECT
                    SS_carts.cart_id,
                    SS_carts.item_id,
                    SS_carts.amount,
                    SS_items.name,
                    SS_items.price,
                    SS_items.tax,
                    SS_items.stock,
                    SS_items.img
                FROM
                    SS_carts
                    INNER JOIN SS_items
                    ON SS_carts.item_id = SS_items.item_id
                WHERE
                    user_id = ?;';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1, $user_id, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        $dbh->commit();
    } catch (PDOException $e) {
        $dbh->rollback();
        throw $e;
    }
    return $rows;
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
