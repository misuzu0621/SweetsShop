<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
        <title>SweetsShop&nbsp;<?php print $category; ?></title>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/10up-sanitize.css/4.1.0/sanitize.min.css">
        <link rel="stylesheet" href="./css/common.css">
        <link rel="stylesheet" href="./css/itemlist.css">
    </head>
    <body>
        <header>
            <div class="container">
                <h1>Sweets Shop</h1>
                <div class="menus">
                    <p>ようこそ、<?php print $username; ?>さん</p>
                    <div class="menu">
                        <a href="history.php"><img src="./img/history.png">購入履歴</a>
                        <a href="cart.php"><img src="./img/cart.png">カート</a>
                        <a href="logout.php"><img src="./img/logout.png">ログアウト</a>
                    </div>
                </div>
                <nav>
                    <ul class="category">
                        <li><a href="itemlist.php">商品一覧</a></li>
                        <li><a href="itemlist_baked.php">焼き菓子</a></li>
                        <li><a href="itemlist_chocolate.php">ショコラ</a></li>
                        <li><a href="itemlist_western.php">洋菓子</a></li>
                        <li><a href="itemlist_japanese.php">和菓子</a></li>
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
                                <li><a href="cart.php">カート</a></li>
                                <li><a href="history.php">購入履歴</a></li>
                                <li><a href="itemlist.php">商品一覧</a></li>
                                <li><a href="itemlist_baked.php">焼き菓子</a></li>
                                <li><a href="itemlist_chocolate.php">ショコラ</a></li>
                                <li><a href="itemlist_western.php">洋菓子</a></li>
                                <li><a href="itemlist_western.php">和菓子</a></li>
                                <li><a href="logout.php">ログアウト</a></li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
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
                        <p><img src="<?php print IMG_DIR . $row['img']; ?>" class="item_img"></p>
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
        <script src="./script/script.js"></script>
    </body>
</html>