<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>SweetsShop ユーザ管理ページ</title>
        <link rel="stylesheet" href="./css/userlist.css">
    </head>
    <body>
        <h1>ユーザ管理ページ</h1>
        <table>
            <caption>ユーザ一覧</caption>
            <tr>
                <th>ユーザID</th>
                <th>ユーザ名</th>
                <th>パスワード</th>
            </tr>
            <!-- ユーザ情報 繰り返し -->
            <?php foreach ($rows as $row) { ?>
            <tr>
                <td><?php print $row['user_id']; ?></td>
                <td><?php print $row['username']; ?></td>
                <td><?php print $row['password']; ?></td>
            </tr>
            <?php } ?>
        </table>
    </body>
</html>