<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- head.php読み込み -->
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>SweetsShop ショッピングカート</title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'cart.css'; ?>">
    </head>
    <body>
        <header>
            <!-- header_logined.php読み込み -->
            <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        </header>
        <main>
            <div class="container">
                <h2>&emsp;カート</h2>
                <!-- エラーメッセージ挿入 -->
                <?php if (count($err_msgs) > 0) { foreach ($err_msgs as $err_msg) { ?>
                <p class="err_msg"><?php print $err_msg; ?></p>
                <?php } } ?>
                <!-- 商品 繰り返し -->
                <?php foreach($rows as $row) { ?>
                <div class="cart_item">
                    <p><img src="<?php print ITEM_IMAGE_PATH . $row['img']; ?>" class="item_img"></p>
                    <p><?php print $row['name']; ?></p>
                    <div class="cart_info">
                        <p><?php if ((int)$row['tax'] === 1) { print $row['price'] * TAX8K; } else { print $row['price'] * TAX10; } ?>円&nbsp;(税込)</p>
                        <form method="post">
                            <p>
                                <!-- 在庫が0のとき -->
                                <?php if ((int)$row['stock'] === 0) { ?>
                                <span>売り切れ</span>
                                <!-- 在庫があるとき -->
                                <?php } else { ?>
                                <input type="text" name="amount" value="<?php print $row['amount']; ?>" class="amount">個
                                <input type="submit" value="変更" class="submit update">
                                <?php } ?>
                            </p>
                            <input type="hidden" name="action" value="update_amount">
                            <input type="hidden" name="cart_id" value="<?php print $row['cart_id']; ?>">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                        <p>小計&nbsp;:&nbsp;<?php if ((int)$row['tax'] === 1) { print $row['price'] * $row['amount'] * TAX8K; } else { print $row['price'] * $row['amount'] * TAX10; } ?>円&nbsp;(税込)</p>
                        <form method="post">
                            <p><input type="submit" value="削除" class="submit update"></p>
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="cart_id" value="<?php print $row['cart_id']; ?>">
                        </form>
                    </div>
                </div>
                <?php } ?>
                <p class="sum">合計&nbsp;:&nbsp;<?php print $sum; ?>円&nbsp;(税込)</p>
                <?php if (!empty($rows)) { ?>
                <form method="post">
                    <input type="submit" value="購入する" class="submit buy">
                    <input type="hidden" name="action" value="buy">
                </form>
                <?php } ?>
            </div>
        </main>
        <footer>
            
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="<?php print SCRIPT_PATH . 'script.js'; ?>"></script>
    </body>
</html>
