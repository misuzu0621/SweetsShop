<div class="container">
    <h1>Sweets Shop</h1>
    <div class="menus">
        <p>ようこそ、<?php print h($username); ?>さん</p>
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
            <p>ようこそ<br><?php print h($username); ?>さん</p>
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
