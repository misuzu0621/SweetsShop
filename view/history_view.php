<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- head.php読み込み -->
        <?php include VIEW_PATH . 'templates/head.php'; ?>
        <title>SweetsShop 購入履歴</title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'history_2.css'; ?>">
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
                <!-- 購入履歴 繰り返し -->
                <?php foreach ($orders as $key => $order) { ?>
                <div class="orders">
                    <p><?php print $order['createdate']; ?></p>
                    <!-- 購入明細 繰り返し -->
                    <?php foreach ($order_details[$key] as $order_detail) { ?>
                    <div class="order_details">
                        <img src="<?php print ITEM_IMAGE_PATH . $order_detail['img']; ?>" class="item_img">
                        <div class="order_detail">
                            <p><?php print h($order_detail['name']); ?></p>
                            <div class="history_info">
                                <p>購入時&nbsp;:&nbsp;<?php print get_tax_include_price_order($order_detail); ?>円&nbsp;(税込)</p>
                                <p><?php print $order_detail['amount']; ?>個</p>
                                <p>小計&emsp;&nbsp;:&nbsp;<?php print get_tax_include_subtotal_price($order_detail); ?>円&nbsp;(税込)</p>
                            </div>
                            <div class="history_info">
                                <p class="ppp">現在&emsp;&nbsp;:&nbsp;<?php print get_tax_include_price_now($order_detail); ?>円&nbsp;(税込)</p>
                                <form method="post" class="history_info">
                                    <!-- 在庫なしのとき -->
                                    <?php if ((int)$order_detail['stock'] === 0) { ?>
                                    <p><span>売り切れ</span></p>
                                    <!-- 在庫ありのとき -->
                                    <?php } else { ?>
                                    <p><input type="text" name="amount" class="amount">個</p>
                                    <p><input type="submit" value="もう一度カートに入れる" class="submit"></p>
                                    <?php } ?>
                                    <input type="hidden" name="item_id" value="<?php print $order_detail['item_id']; ?>">
                                    <input type="hidden" name="token" value="<?php print $token; ?>">
                                </form>
                            </div>
                        <?php } ?>
                        </div>
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