<?php

/**
 * セッション変数から購入完了かどうか確認、セッション変数のhistory_idを削除
 * 購入完了していない場合、ショッピングカートページへリダイレクト
 * @return array $_SESSION['history_id'] 履歴ID
 */
function confirmation_history_id() {
    if (isset($_SESSION['history_id'])) {
        return $_SESSION['history_id'];
        unset($_SESSION['history_id']);
    } else {
        header('Location: cart.php');
        exit;
    }
}

/**
 * 購入した商品一覧を取得
 * @param  obj   $dbh        DBハンドル
 * @param  array $history_id 履歴ID配列
 * @return array $rows       購入した商品一覧配列
 */
function get_buy_items($dbh, $history_id) {
    $rows = array();
    $dbh->beginTransaction();
    try {
        foreach ($history_id as $key => $value) {
            $sql = 'SELECT
                        SS_history.item_id,
                        SS_history.price_history,
                        SS_history.tax_history,
                        SS_history.amount,
                        SS_items.name,
                        SS_items.img
                    FROM
                        SS_history
                        INNER JOIN SS_items
                        ON SS_history.item_id = SS_items.item_id
                    WHERE
                        history_id = ?;';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1, $value, PDO::PARAM_INT);
            $stmt->execute();
            $rows[] = $stmt->fetch();
        }
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
        if ((int)$row['tax_history'] === 1) {
            $subtotal = (int)$row['price_history'] * (int)$row['amount'] * TAX8K;
        } else {
            $subtotal = (int)$row['price_history'] * (int)$row['amount'] * TAX10;
        }
        $sum += $subtotal;
    }
    return $sum;
}
