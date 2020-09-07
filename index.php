<?php
    require("required/AdHound.php");
    // Creates a reference to the AdHound class
    $adhound = new AdHound();

    $pageTitle = 'Login';

    if (isset($_POST['loginButton'])) {
        /*
            userLogin passes the login form data to login the user.
        */
        $adhound->users->userLogin($_POST['username'], $_POST['password']);

    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>AdHound&trade;2 - <?php echo $pageTitle; ?></title>

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>

        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>

        <link rel="stylesheet" type="text/css" href="css/externalForms.css?v=<?php echo rand (1, 10); ?>" />
        <link rel="stylesheet" type="text/css" href="css/forms.css?v=<?php echo rand (1, 10); ?>" />
        <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo rand (1, 10); ?>" />


        <script src="javascript/validationForms.js"></script>


    </head>
    <body>
        <?php

            $image = imagecreatefromgif('images/AdHound_LogoV.gif');
            $color = imagecolorallocate($image, 0, 0, 0);
            imagestring($image, 1, 13, 1, VERSION, $color);
            imagegif($image, 'images/AdHound_Logo.gif');
            imagedestroy($image);

        ?>

        <form method="post" class="form form-signin" name="loginForm" onSubmit="return formValidation(this);" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <img class="mb-3" src="images/AdHound_Logo.gif" alt="AdHound&trade; - It's Advertising, LLC" width="258" height="60">
            <h1 class="h5 mb-3 font-weight-normal">Please sign in</h1>
            <label for="username" class="sr-only">Username</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo $_POST['username']; ?>" placeholder="Username" required autofocus>
            <label for="password" class="sr-only">Password</label>
                <input type="password" id="password" name="password" class="form-control"  placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" id="loginButton" name="loginButton" type="submit">Login</button>

            <button type="button" onclick="window.location.href='signup.php'" class="btn btn-lg btn-primary btn-block" id="signupButton" name="signupButton">Sign-Up</button>
            <p class="mt-5 mb-3 text-muted">AdHound&trade; Copyright &copy; <?php echo date('Y'); ?></p>
        </form>

        <?php
            // Unsets all backend error messages after they were displayed
            if (isset($_SESSION['messages'])) {
            foreach ($_SESSION['messages'] as $e => $error) {
                unset($_SESSION['messages'][$e]);
            }
            unset($_SESSION['messages']);
            }

            /*
            Remove any values in these object references.
            */
            $adhound = null;
        ?>

    </body>
</html>