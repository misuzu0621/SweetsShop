<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- head.php読み込み -->
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>SweetsShop 新規ユーザ登録</title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common_2.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'register.css'; ?>">
    </head>
    <body>
        <header>
            <!-- header.php読み込み -->
            <?php include VIEW_PATH . 'templates/header.php'; ?>
        </header>
        <main>
            <div class="container">
                <!-- エラーメッセージ挿入 -->
                <?php if (count($err_msgs) > 0) { ?>
                <div class="err_msg">
                    <?php foreach ($err_msgs as $err_msg) { ?>
                    <p><?php print $err_msg; ?></p>
                    <?php } ?>
                </div>
                <?php } ?>
                <!-- 成功メッセージ挿入 -->
                <?php if ($db_insert === true) { ?>
                <div class="success_msg">
                    <p>ありがとうございます</p>
                    <p>新規ユーザ登録が完了しました</p>
                    <a href="login.php" class="login_register">&rsaquo;&rsaquo;&nbsp;ログインページへ</a>
                </div>
                <?php } else { ?>
                <form method="post">
                    <p><label>ユーザ名　&emsp;<input type="text" name="username" class="textbox"></label></p>
                    <p><label>パスワード&emsp;<input type="password" name="password" class="textbox"></label></p>
                    <p class="comment">ユーザ名とパスワードは半角英数字6文字以上で入力してください</p>
                    <p><input type="submit" value="&rsaquo;&rsaquo;&nbsp;新規登録" class="login_register"></p>
                    <input type="hidden" name="token" value="<?php print $token; ?>">
                </form>
                <?php } ?>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>