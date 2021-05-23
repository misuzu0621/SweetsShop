<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
        <title>SweetsShop ログイン</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/10up-sanitize.css/4.1.0/sanitize.min.css">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'login.css'; ?>">
    </head>
    <body>
        <header>
            <div class="container">
                <h1>Sweets Shop</h1>
            </div>
        </header>
        <main>
            <div class="container">
                <img src="<?php print IMAGE_PATH . 'main_view.jpg'; ?>" class="main_view">
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
                        <p class="textbox"><label>ユーザ名&emsp;&emsp;<input type="text" name="username" value="<?php print $username; ?>"></label></p>
                        <p class="textbox"><label>パスワード&emsp;<input type="password" name="password"></label></p>
                        <p><input type="submit" value="&rsaquo;&rsaquo;&nbsp;ログイン" class="login login_register"></p>
                    </form>
                    <a href="<?php print REGISTER_URL; ?>" class="login_register">&rsaquo;&rsaquo;&nbsp;新規登録はこちら</a>
                </div>
                <section>
                    <h2>&emsp;今月のおすすめ</h2>
                    <div class="items">
                        <!-- おすすめ商品 繰り返し -->
                        <?php foreach ($rows as $row) { ?>
                        <div class="item">
                            <p><img src="<?php print ITEM_IMAGE_PATH . $row['img']; ?>" class="item_img"></p>
                            <p><?php print $row['name']; ?></p>
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