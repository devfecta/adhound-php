<?php
    session_start();

    if (isset($_SESSION)) {
        foreach ($_SESSION as $s => $session) {
            session_unset($_SESSION[$s]);
        }
        session_unset($_SESSION);

        session_destroy();
    }

    // Go to the login page on logout
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';

     header('Location: ' . $home_url);

?>