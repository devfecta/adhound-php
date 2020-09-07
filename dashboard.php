<?php
    $pageTitle = 'Dashboard';
    require_once('required/header.php');
?>

<?php
    /*
        Displays any errors from the any backend validation. Also,
        the HTML article will displan any errors found on the frontend
        using JavaScript.
    */
    if (isset($_SESSION['messages'])) {
        foreach ($_SESSION['messages'] as $message) {
            echo $message;
        }
    }

    if (isset($_GET['viewLocations']) && $_GET['viewLocations'] == 'true') {
        $adhound->locations->setLocations($_SESSION['user']['user_id']);

        echo $adhound->views->listlocations($adhound->locations->getLocations());

    }
?>


<?php
    require_once('required/footer.php');
?>