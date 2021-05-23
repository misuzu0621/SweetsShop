<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
        <title>SweetsShop ショッピングカート</title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/10up-sanitize.css/4.1.0/sanitize.min.css">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'common.css'; ?>">
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'cart.css'; ?>">
    </head>
    <body>
        <header>
            <div class="container">
                <h1>Sweets Shop</h1>
                <div class="menus">
                    <p>ようこそ、<?php print $username; ?>さん</p>
                    <div class="menu">
                        <a href="<?php print HISTORY_URL; ?>"><img src="<?php print IMAGE_PATH . 'history.png'; ?>">購入履歴</a>
                        <a href="<?php print CART_URL; ?>"><img src="<?php print IMAGE_PATH . 'cart.png'; ?>">カート</a>
                        <a href="<?php print LOGOUT_URL; ?>"><img src="<?php print IMAGE_PATH . 'logout.png'; ?>">ログアウト</a>
                    </div>
                </div>
                <nav>
                    <ul class="category">
                        <li><a href="<?php print ITEMLIST_URL; ?>">商品一覧</a></li>
                        <li><a href="<?php print ITEMLIST_BAKED_URL; ?>">焼き菓子</a></li>
                        <li><a href="<?php print ITEMLIST_CHOCOLATE_URL; ?>">ショコラ</a></li>
                        <li><a href="<?php print ITEMLIST_WESTERN_URL; ?>">洋菓子</a></li>
                        <li><a href="<?php print ITEMLIST_JAPANESE_URL; ?>">和菓子</a></li>
                    </ul>
                </nav>
                
                <!-- スマホ用 メニュー -->
                <div class="hum_menu">
                    <div id="menu_icon">
                        <span class="line" id="line1"></span>
                        <span class="line" id="line2"></span>
                        <span class="line" id="line3"></span>
                    </div>
                    <div id="menu_contents">
                        <p>ようこそ<br><?php print $username; ?>さん</p>
                        <nav>
                            <ul>
                                <li><a href="<?php print CART_URL; ?>">カート</a></li>
                                <li><a href="<?php print HISTORY_URL; ?>">購入履歴</a></li>
                                <li><a href="<?php print ITEMLIST_URL; ?>">商品一覧</a></li>
                                <li><a href="<?php print ITEMLIST_BAKED_URL; ?>">焼き菓子</a></li>
                                <li><a href="<?php print ITEMLIST_CHOCOLATE_URL; ?>">ショコラ</a></li>
                                <li><a href="<?php print ITEMLIST_WESTERN_URL; ?>">洋菓子</a></li>
                                <li><a href="<?php print ITEMLIST_JAPANESE_URL; ?>">和菓子</a></li>
                                <li><a href="<?php print LOGOUT_URL; ?>">ログアウト</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
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
        <script src="<?php print SCRIPT_PATH . 'script.js'; ?>"></script>
    </body>
</html>
