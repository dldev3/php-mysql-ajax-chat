<?php

#check if username, password, name submitted

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['name'])) {

    #get data from post request and store them in var
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];


    # database connection file
    include '../db.conn.php';

    # making url data format
    $data = 'name=' . $name . '&username=' . $username;

    #simple form validation
    if (empty($name)) {
        #error message
        $em = "Name is required";

        #redirect to signup.php and passing error message
        header("Location:  ../../signup.php?error=$em");
        exit;
    } else if (empty($username)) {
        #error message
        $em = "Username is required";

        #redirect to signup.php and passing error message
        header("Location:  ../../signup.php?error=$em");
        exit;
    } else if (empty($password)) {
        #error message
        $em = "Password is required";

        #redirect to signup.php and passing error message
        header("Location:  ../../signup.php?error=$em");
        exit;
    } else {
        #checking the database if the username is taken
        $sql = "SELECT username
                    FROM users
                    WHERE username=?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $em =  "The username " . $username . " is taken";
            header("Location: ../../signup.php?error=$em");
            exit;
        } else {
            # profile picture uploading
            if (isset($_FILES['pp'])) {
                #get data and store in var
                $img_name = $_FILES['pp']['name'];
                $tmp_name = $_FILES['pp']['tmp_name'];
                $error = $_FILES['pp']['error'];

                #if there is not error occured while uploading
                if ($error === 0) {
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);

                    #convert the image extension into lowercase and store it in var
                    $img_ex_lc = strtolower($img_ex);

                    #crafting array that stores allowed to upload image extension.
                    $allowed_exs = array("jpg", "jpeg", "png");

                    #check if the image extension is present in $allowed_exs array

                    if (in_array($img_ex_lc, $allowed_exs)) {
                        #renaming the image with user's username like: username.$img_ex_lc
                        $new_image_name = $username . '.' . $img_ex_lc;

                        #crafting upload path on root directory
                        $img_upload_path = '../../uploads/' . $new_image_name;

                        #move upload image to uploads directory
                        move_uploaded_file($tmp_name, $img_upload_path);
                    } else {
                        $em  = "You cannot upload this file type, please check it again";
                        header("Location: ../../signup.php?error=$em");
                        exit;
                    }
                } else {
                    $em  = "Unknown error occured";
                    header("Location: ../../signup.php?error=$em");
                    exit;
                }
            }

            #password hashing
            $password = password_hash($password, PASSWORD_DEFAULT);

            #if the user upload profile picture
            if (isset($new_image_name)) {
                $sql = "INSERT INTO users (name, username, password, p_p)
                    VALUES (?,?,?,?)";

                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password, $new_image_name]);
            } else {
                $sql = "INSERT INTO users (name, username, password)
                    VALUES (?,?,?)";

                $stmt = $conn->prepare($sql);
                $stmt->execute([$name, $username, $password]);
            }
            #success message
            $sm = "Account created successfully!";

            #redirect to login page
            header("Location:  ../../index.php?success=$sm");
            exit;
        }
    }
} else {
    header("Location:  ../../signup.php");
    exit;
}
