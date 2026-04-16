<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PROFIT — Sign Up</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/auth/login.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .login-error{background:rgba(220,53,69,.15);border:1px solid rgba(220,53,69,.5);border-radius:5px;color:#ff6b6b;font-size:13px;padding:10px 16px;margin:16px 20px 0;text-align:left}
        input[type=password]{background-color:#f6f6f6;border:2px solid #f6f6f6;border-radius:5px;color:#0d0d0d;padding:15px 32px;text-align:center;display:inline-block;font-size:16px;margin:5px;width:85%;margin-top:2%;transition:all .5s ease-in-out}
        input[type=password]:focus{background-color:#fff;border-bottom:2px solid #5fbae9;outline:none}
    </style>
</head>
<body class="min-vh-100" style="background:url('<?= BASE_URL ?>/app/uploads/gymrat.jpg') center/cover no-repeat;">
    <img class="hamster" src="<?= BASE_URL ?>/app/uploads/gymhamster.jpg" alt="User Icon">
    <div class="wrapper fadeInDown">
        <div id="formContent">
            <div class="fadeIn first"></div>
            <?php if (!empty($error)): ?>
                <div class="login-error"><i class="bi bi-exclamation-circle me-1"></i><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form action="<?= BASE_URL ?>/auth/register" method="post">
                <input type="text"     id="username" class="fadeIn second" name="username" placeholder="username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" autocomplete="username">
                <input type="password" id="password" class="fadeIn third"  name="password" placeholder="password (min 6 chars)" autocomplete="new-password">
                <input type="submit"   id="loginBtn" class="fadeIn fourth" value="Sign Up">
            </form>
            <div id="formFooter"><a class="underlineHover" href="<?= BASE_URL ?>/auth/login">Log In</a></div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>