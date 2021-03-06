<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, user-scalable=yes">
        <title>SweetsShop 新規ユーザ登録</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/10up-sanitize.css/4.1.0/sanitize.min.css">
        <link rel="stylesheet" href="./css/common.css">
        <link rel="stylesheet" href="./css/register.css">
    </head>
    <body>
        <header>
            <div class="container">
                <h1>Sweets Shop</h1>
            </div>
        </header>
        <main>
            <div class="container">
                <!-- エラーメッセージ挿入 -->
                <?php if (count($err_msgs) > 0) { ?>
                <div class="err_msg">
                    <?php foreach ($err_msgs as $err_msg) { ?>
                    <p><?php print $err_msg; ?></p>
                    <?php } ?>
                </div>
                <?php } ?>
                <!-- 成功メッセージ挿入 -->
                <?php if ($db_insert === true) { ?>
                <div class="success_msg">
                    <p>ありがとうございます</p>
                    <p>新規ユーザ登録が完了しました</p>
                    <a href="login.php" class="login_register">&rsaquo;&rsaquo;&nbsp;ログインページへ</a>
                </div>
                <?php } else { ?>
                <form method="post">
                    <p><label>ユーザ名&emsp;&emsp;<input type="text" name="username" class="textbox"></label></p>
                    <p><label>パスワード&emsp;<input type="password" name="password" class="textbox"></label></p>
                    <p class="comment">ユーザ名とパスワードは半角英数字6文字以上で入力してください</p>
                    <p><input type="submit" value="&rsaquo;&rsaquo;&nbsp;新規登録" class="login_register"></p>
                </form>
                <?php } ?>
            </div>
        </main>
        <footer>
            
        </footer>
    </body>
</html>