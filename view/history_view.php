<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- head.php読み込み -->
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>SweetsShop 購入履歴</title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'history.css'; ?>">
    </head>
    <body>
        <header>
            <!-- header_logined.php読み込み -->
            <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
        </header>
        <main>
            <div class="container">
                <h2>&emsp;購入履歴</h2>
                <!-- エラーメッセージ挿入 -->
                <?php if (count($err_msgs) > 0) { foreach ($err_msgs as $err_msg) { ?>
                <p class="err_msg"><?php print $err_msg; ?></p>
                <?php } } ?>
                <!-- 商品 繰り返し -->
                <?php foreach ($rows as $row) { ?>
                <div>
                    <p class="date"><?php print $row['createdate']; ?></p>
                    <img src="<?php print ITEM_IMAGE_PATH . $row['img']; ?>" class="item_img">
                    <p><?php print h($row['name']); ?></p>
                    <div class="history_info">
                        <p>購入時&nbsp;:&nbsp;<?php if ((int)$row['tax_history'] === 1) { print $row['price_history'] * TAX8K; } else { print $row['price_history'] * TAX10; } ?>円&nbsp;(税込)</p>
                        <p><?php print $row['amount']; ?>個</p>
                        <p>小計&emsp;&nbsp;:&nbsp;<?php if ((int)$row['tax_history'] === 1) { print $row['price_history'] * $row['amount'] * TAX8K; } else { $row['price_history'] * $row['amount'] * TAX10; } ?>円&nbsp;(税込)</p>
                    </div>
                    <div class="history_info">
                        <p>現在&emsp;&nbsp;:&nbsp;<?php if ((int)$row['tax'] === 1) { print $row['price'] * TAX8K; } else { print $row['price'] * TAX10; } ?>円&nbsp;(税込)</p>
                        <form method="post" class="history_info">
                            <!-- 在庫なしのとき -->
                            <?php if ((int)$row['stock'] === 0) { ?>
                            <p><span>売り切れ</span></p>
                            <!-- 在庫ありのとき -->
                            <?php } else { ?>
                            <p><input type="text" name="amount" class="amount">個</p>
                            <p><input type="submit" value="もう一度カートに入れる" class="submit"></p>
                            <?php } ?>
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                            <input type="hidden" name="token" value="<?php print $token; ?>">
                        </form>
                    </div>
                </div>
                <?php } ?>
            </div>
        </main>
        <footer>
            
        </footer>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="<?php print SCRIPT_PATH . 'script.js'; ?>"></script>
    </body>
</html>