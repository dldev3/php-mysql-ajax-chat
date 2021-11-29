<?php

session_start();

#check if  username and password submitted

if (isset($_POST['username']) && isset($_POST['password'])) {

    # database connection file
    include '../db.conn.php';

    #get data from post request and store them in var
    $username = $_POST['username'];
    $password = $_POST['password'];

    #simple form validation
    if (empty($username)) {
        $em = "Username is required";
        header("Location: ../../index.php?error=$em");
        exit;
    } else if (empty($password)) {
        $em = "Password is required";
        header("Location: ../../index.php?error=$em");
        exit;
    } else {

        $sql = "SELECT * FROM users WHERE username=?";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);

        #if the username is exist
        if ($stmt->rowCount() === 1) {
            #fetching user data
            $user = $stmt->fetch();

            #if both usernames are strictly equal
            if ($user['username'] === $username) {
                #verifying the encrypted password
                if (password_verify($password, $user['password'])) {
                    #successfully logged in
                    #creating the session

                    $_SESSION['username'] = $user['username'];
                    $_SESSION['name'] = $user['name'];
                    $_SESSION['user_id'] = $user['user_id'];

                    #redirect to home.php
                    header("Location: ../../home.php");
                } else {
                    $em = "Incorrect username or password";
                    header("Location: ../../index.php?error=$em");
                    exit;
                }
            } else {
                $em = "Incorrect username or password";
                header("Location: ../../index.php?error=$em");
                exit;
            }
        } else {
        }
    }
} else {
    header("Location: ../../index.php");
    exit;
}
