<?php
session_start();

if (isset($_SESSION['username'])) {

    #database connection
    include 'app/db.conn.php';

    include 'app/helpers/user.php';

    include 'app/helpers/chat.php';

    include 'app/helpers/timeAgo.php';



    if (!isset($_GET['user'])) {
        header("Location: home.php");
        exit;
    }

    #getting user data
    $chatWith = getUser($_GET['user'], $conn);

    if (empty($chatWith)) {
        header("Location: home.php");
        exit;
    }

    $chats = getChats($_SESSION['user_id'], $chatWith['user_id'], $conn);


?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="css/style.css">
        <link rel="stylesheet" href="css/bootstrap.css">
        <link rel="stylesheet" href="css/fontawesome.css">
        <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />
        <title>Chat App - Home</title>
    </head>

    <body class="d-flex justify-content-center align-items-center vh-100">
        <div class="w-400 shadow p-4 rounded ">
            <a href="home.php" class="fs-4 link-dark">&#8592;</a>
            <div class="d-flex align-items-center">
                <h3 class="display-4 fs-sm m-2"><?= $chatWith['name']; ?> </h3>
                <br>
                <div class="d-flex align-items-center" title="online">
                    <?php
                    if (last_seen($chatWith['last_seen']) == "Active") { ?>
                        <div class="online"></div>
                        <small class="d-block p-1">Online</small>
                    <?php } else { ?>
                        <small class="d-block p-1">
                            <?= $chatWith['last_seen'];  ?>
                        </small>
                    <?php } ?>
                </div>

            </div>
            <div class="shadow p-4 rounded d-flex flex-column mt-2 chat-box" id="chatBox">
                <?php
                if (!empty($chats)) {
                    foreach ($chats as $chat) {
                        if ($chat['from_id'] == $_SESSION['user_id']) { ?>
                            <p class="rtext align-self-end border rounded p-2 mb-1">
                                <?= $chat['message']; ?>
                                <small class="d-block">
                                    <?= $chat['created_at']; ?>
                                </small>
                            </p>
                        <?php } else { ?>
                            <p class="ltext border rounded p-2 mb-1">
                                <?= $chat['message']; ?>
                                <small class="d-block">
                                    <?= $chat['created_at']; ?>
                                </small>
                            </p>
                    <?php }
                    }

                    ?>





                <?php  } else { ?>

                    <div class="alert alert-info text-center">
                        <i class="fa fa-comments d-block fs-big "></i>
                        No messages yet, Start the conversation
                    </div>

                <?php } ?>


            </div>

            <div class="input-group mb-3">
                <textarea cols="3" class="form-control" id="message"></textarea>
                <button class="btn  btn-primary" id="sendBtn">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </div>


        </div>



        <script type="text/javascript" src="js/jq.min.js"></script>

        <script>
            var scrollDown = function() {
                let chatbox = document.getElementById('chatBox');
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            scrollDown();

            $(document).ready(function() {
                $('#sendBtn').on('click', function() {
                    message = $("#message").val();
                    if (message == "") return;

                    $.post("app/ajax/insert.php", {
                            message: message,
                            to_id: <?= $chatWith['user_id'] ?>
                        },
                        function(data, status) {
                            $("#message").val("");
                            $("#chatBox").append(data);
                            scrollDown();
                        });
                });
            });
        </script>
    </body>

    </html>
<?php

} else {
    header("Location: index.php");
    exit;
}

?>