<!DOCTYPE html>
<html>
    <head>
        <!-- head.php読み込み -->
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>SweetsShop&nbsp;<?php print $category; ?></title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'itemlist.css'; ?>">
    </head>
    <body>
        <header>
            <!-- header_logined.php読み込み -->
            <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        </header>
        <main>
            <div class="container">
                <!-- カテゴリ名 -->
                <h2>&emsp;<?php print $category; ?></h2>
                <!-- エラーメッセージ挿入 -->
                <?php if (count($err_msgs) > 0) { foreach ($err_msgs as $err_msg) { ?>
                <p class="err_msg"><?php print $err_msg; ?></p>
                <?php } } ?>
                <div class="items">
                    <!-- 商品 繰り返し -->
                    <?php foreach ($rows as $row) { ?>
                    <div class="item">
                        <p><img src="<?php print ITEM_IMAGE_PATH . $row['img']; ?>" class="item_img"></p>
                        <p class="item_info"><?php print $row['name']; ?></p>
                        <p class="item_info"><?php if ((int)$row['tax'] === 1) { print $row['price'] * TAX8K; } else { print $row['price'] * TAX10; } ?>円&nbsp;(税抜&nbsp;:&nbsp;<?php print $row['price']; ?>円)</p>
                        <form method="post">
                            <p><input type="text" name="amount" value="1" class="amount">個</p>
                            <p>
                                <!-- 在庫無しのとき -->
                                <?php if ((int)$row['stock'] === 0) { ?>
                                <span>売り切れ</span>
                                <!-- 在庫有りのとき -->
                                <?php } else { ?>
                                <input type="submit" value="カートに入れる" class="submit">
                                <?php } ?>
                            </p>
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </main>
        <footer>
            
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="<?php print SCRIPT_PATH . 'script.js'; ?>"></script>
    </body>
</html>