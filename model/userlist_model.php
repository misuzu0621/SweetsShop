<?php

/**
 * ユーザ一覧取得
 * @param  obj   $dbh  DBハンドル
 * @return array $rows ユーザ一覧配列
 */
function get_userlist($dbh) {
    try {
        $sql = 'SELECT
                    user_id, username, password
                FROM
                    SS_users;';
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
    } catch (PDOException $e) {
        throw $e;
    }
    return $rows;
}
