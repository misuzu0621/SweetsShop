<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>SweetsShop 商品管理ページ</title>
        <link rel="stylesheet" href="<?php print STYLESHEET_PATH . 'admin.css'; ?>">
    </head>
    <body>
        <!-- エラーメッセージ挿入 -->
        <?php if (count($err_msgs) > 0) { foreach ($err_msgs as $err_msg) { ?>
        <p><?php print $err_msg; ?></p>
        <?php } } ?>
        <!-- 成功メッセージ挿入 -->
        <?php if ($success_msg !== '') { ?>
        <p><?php print $success_msg; ?></p>
        <?php } ?>
        <h1>商品管理ページ</h1>
        <section>
            <h2>新規商品追加</h2>
            <form method="post" enctype="multipart/form-data">
                <p><label>商品名　　：<input type="text" name="name"></label></p>
                <p><label>価格(税抜)：<input type="text" name="price"></label></p>
                <p>
                    税率　　　：
                    <select name="tax">
                        <option value="1">8%軽</option>
                        <option value="2">10%</option>
                    </select>
                </p>
                <p><label>個数　　　：<input type="text" name="stock"></label></p>
                <p>
                    カテゴリ　：
                    <select name="type">
                        <option value="1">焼き菓子</option>
                        <option value="2">ショコラ</option>
                        <option value="3">洋菓子</option>
                        <option value="4">和菓子</option>
                    </select>
                </p>
                <p><input type="checkbox" name="recommend" value="1">おすすめ商品に追加</p>
                <p><input type="file" name="img"></p>
                <p>
                    <select name="status">
                        <option value="0">非公開</option>
                        <option value="1">公開</option>
                    </select>
                </p>
                <p><input type="submit" value="商品追加"></p>
                <input type="hidden" name="action" value="insert_item">
            </form>
        </section>
        <section>
            <h2>商品情報変更</h2>
            <table>
                <caption>商品一覧</caption>
                <tr>
                    <th>商品画像</th>
                    <th>商品名</th>
                    <th>価格(税抜)</th>
                    <th>税率</th>
                    <th>在庫数</th>
                    <th>カテゴリ</th>
                    <th>おすすめ</th>
                    <th>ステータス</th>
                    <th>削除</th>
                </tr>
                <!-- 商品一覧 -->
                <?php foreach ($rows as $row) { ?>
                <tr class="<?php if ((int)$row['status'] === 0) { print 'private'; } ?>"><!-- ステータスが非公開のとき クラス名:private -->
                    <td><!-- 商品画像 -->
                        <img src="<?php print ITEM_IMAGE_PATH . $row['img']; ?>"><br>
                        <form method="post" enctype="multipart/form-data">
                            <input type="file" name="img"><br>
                            <input type="submit" value="変更">
                            <input type="hidden" name="action" value="update_img">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                    <td><!-- 商品名 -->
                        <form method="post">
                            <input type="text" name="name" value="<?php print $row['name']; ?>"><br>
                            <input type="submit" value="変更">
                            <input type="hidden" name="action" value="update_name">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                    <td><!-- 価格 -->
                        <form method="post">
                            <input type="text" name="price" value="<?php print $row['price']; ?>">円<br>
                            <input type="submit" value="変更">
                            <input type="hidden" name="action" value="update_price">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                    <td><!-- 税率 -->
                        <form method="post">
                            <select name="tax">
                                <option value="1" <?php if ((int)$row['tax'] === 1) { print 'selected'; } ?>>8%軽</option>
                                <option value="2" <?php if ((int)$row['tax'] === 2) { print 'selected'; } ?>>10%</option>
                            </select><br>
                            <input type="submit" value="変更">
                            <input type="hidden" name="action" value="update_tax">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                    <td><!-- 在庫数 -->
                        <form method="post">
                            <input type="text" name="stock" value="<?php print $row['stock']; ?>">個<br>
                            <input type="submit" value="変更">
                            <input type="hidden" name="action" value="update_stock">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                    <td><!-- カテゴリ -->
                        <form method="post">
                            <select name="type">
                                <option value="1" <?php if ((int)$row['type'] === 1) { print 'selected'; } ?>>焼き菓子</option>
                                <option value="2" <?php if ((int)$row['type'] === 2) { print 'selected'; } ?>>ショコラ</option>
                                <option value="3" <?php if ((int)$row['type'] === 3) { print 'selected'; } ?>>洋菓子</option>
                                <option value="4" <?php if ((int)$row['type'] === 4) { print 'selected'; } ?>>和菓子</option>
                            </select><br>
                            <input type="submit" value="変更">
                            <input type="hidden" name="action" value="update_type">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                    <td><!-- おすすめ -->
                        <form method="post">
                            <input type="checkbox" name="recommend" value="1" <?php if ((int)$row['recommend'] === 1) { print 'checked'; } ?>><br>
                            <input type="submit" value="変更">
                            <input type="hidden" name="action" value="update_recommend">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                    <td><!-- ステータス -->
                        <form method="post">
                            <input type="submit" name="status" value="<?php if ((int)$row['status'] === 0) { print '非公開→公開'; } else { print '公開→非公開'; } ?>">
                            <input type="hidden" name="action" value="update_status">
                            <input type="hidden" name="status" value="<?php print $row['status']; ?>">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                    <td><!-- 削除 -->
                        <form method="post">
                            <input type="submit" value="削除">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="item_id" value="<?php print $row['item_id']; ?>">
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </table>
        </section>
    </body>
</html>