<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- head.php読み込み -->
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>SweetsShop ショッピングカート</title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'finish.css'; ?>">
    </head>
    <body>
        <header>
            <!-- header_logined.php読み込み -->
            <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        </header>
        <main>
            <div class="container">
                <div class="buy_msg">
                    <p>ありがとうございます</p>
                    <p>購入完了しました</p>
                </div>
                <!-- 商品 繰り返し -->
                <?php foreach ($rows as $row) { ?>
                <div class="buy_item">
                    <img src="<?php print ITEM_IMAGE_PATH . $row['img']; ?>" class="item_img">
                    <p><?php print $row['name']; ?></p>
                    <div class="buy_info">
                        <p><?php if ((int)$row['tax_history'] === 1) { print $row['price_history'] * TAX8K; } else { print $row['price_history'] * TAX10; } ?>円&nbsp;(税込)</p>
                        <p><?php print $row['amount']; ?>個</p>
                        <p>小計&nbsp;:&nbsp;<?php if ((int)$row['tax_history'] === 1) { print $row['price_history'] * $row['amount'] * TAX8K; } else { print $row['price_history'] * $row['amount'] * TAX10; } ?>円&nbsp;(税込)</p>
                    </div>
                </div>
                <?php } ?>
                <p class="sum">合計&nbsp;:&nbsp;<?php print $sum; ?>円&nbsp;(税込)</p>
            </div>
        </main>
        <footer>
            
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="<?php print SCRIPT_PATH . 'script.js'; ?>"></script>
    </body>
</html>