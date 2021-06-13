<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- head.php読み込み -->
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>SweetsShop トップページ</title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common_2.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'top.css'; ?>">
    </head>
    <body>
        <header>
            <!-- ヘッダー読み込み -->
            <?php
                if ($user_id !== false) {
                    include VIEW_PATH . 'templates/header_logined.php';
                } else {
                    include VIEW_PATH . 'templates/header_non_logined.php';
                }
            ?>
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
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="<?php print SCRIPT_PATH . 'script.js'; ?>"></script>
    </body>
</html>
