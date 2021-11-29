<?php
session_start();

if (!isset($_SESSION['username'])) {

?>

    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Chat APP</title>
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" href="css/bootstrap.css">
    </head>

    <body class="d-flex justify-content-center align-items-center vh-100">
        <div class="w-400 p-5 rounded shadow">


            <form method="POST" action="app/http/signup.php" enctype="multipart/form-data">

                <div class="d-flex justify-content-center align-items-center  flex-column">
                    <h3 class="display-4 fs-1 text-center">Sign Up</h3>
                </div>

                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-warning" role="alert">
                        <?php echo htmlspecialchars($_GET['error']); ?>
                    </div>
                <?php } ?>

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" class="form-control" name="name" />
                </div>
                <div class="mb-3">
                    <label class="form-label">User Name</label>
                    <input type="text" class="form-control" name="username" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" />
                </div>
                <div class="mb-3">
                    <label class="form-label">Profile Picture</label>
                    <input type="file" class="form-control" name="pp" />
                </div>

                <button type="submit" class="btn btn-primary">Sign Up</button>

                <a href="index.php">Login</a>
            </form>
        </div>
    </body>

    </html>

<?php

} else {
    header("Location: home.php");
    exit;
}

?>