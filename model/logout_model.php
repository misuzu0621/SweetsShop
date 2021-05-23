<?php

/**
 * ユーザのクッキーに保存されているセッションIDを削除
 * @param  str   $session_name セッション名
 */
function delete_session_id($session_name) {
    if (isset($_COOKIE[$session_name])) {
        // セッションに関連する設定を取得
        $params = session_get_cookie_params();
        // セッションに利用しているクッキーの有効期限を過去にすることで無効化
        setcookie($session_name, '', time() - 100, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
}
