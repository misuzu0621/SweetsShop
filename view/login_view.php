<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- head.php読み込み -->
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>SweetsShop ログイン</title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'login_2.css'; ?>">
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
                <div class="form">
                    <form method="post">
                        <p class="textbox"><label>ユーザ名&emsp;&emsp;<input type="text" name="username" value="<?php print h($username); ?>"></label></p>
                        <p class="textbox"><label>パスワード&emsp;<input type="password" name="password"></label></p>
                        <p><input type="submit" value="&rsaquo;&rsaquo;&nbsp;ログイン" class="login login_register"></p>
                        <input type="hidden" name="token" value="<?php print $token; ?>">
                    </form>
                    <a href="<?php print REGISTER_URL; ?>" class="login_register">&rsaquo;&rsaquo;&nbsp;新規登録はこちら</a>
                </div>
                <div class="sample_user">
                    <p>↑ このアカウントでもログイン出来ます ↑</p>
                    <p>ユーザ名　：sample &ensp;</p>
                    <p>パスワード：password</p>
                </div>
                <section>
                    <h2>&emsp;今月のおすすめ</h2>
                    <div class="items">
                        <!-- おすすめ商品 繰り返し -->
                        <?php foreach ($rows as $row) { ?>
                        <div class="item">
                            <p><img src="<?php print ITEM_IMAGE_PATH . $row['img']; ?>" class="item_img"></p>
                            <p><?php print h($row['name']); ?></p>
                        </div>
                        <?php } ?>
                    </div>
                </section>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>