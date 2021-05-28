<div class="container">
    <h1>Sweets Shop</h1>
    <div class="menus">
        <div class="menu">
            <a href="<?php print LOGIN_URL; ?>">ログイン</a>
            <a href="<?php print REGISTER_URL; ?>">新規登録</a>
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
            <nav>
                <ul>
                    <li><a href="<?php print LOGIN_URL; ?>">ログイン</a></li>
                    <li><a href="<?php print REGISTER_URL; ?>">新規登録</a></li>
                    <li><a href="<?php print ITEMLIST_URL; ?>">商品一覧</a></li>
                    <li><a href="<?php print ITEMLIST_BAKED_URL; ?>">焼き菓子</a></li>
                    <li><a href="<?php print ITEMLIST_CHOCOLATE_URL; ?>">ショコラ</a></li>
                    <li><a href="<?php print ITEMLIST_WESTERN_URL; ?>">洋菓子</a></li>
                    <li><a href="<?php print ITEMLIST_JAPANESE_URL; ?>">和菓子</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>
