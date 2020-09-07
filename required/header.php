<?php
    require("required/AdHound.php");
    // Creates a reference to the Blog class
    $adhound = new AdHound();

    if (!isset($_SESSION['user']['user_id']) && !isset($_SESSION['user']['username'])) {
        // Take the user to login page if not logged in
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/index.php';
        header('Location: ' . $home_url);
    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="favicon.ico">
        <title>AdHound&trade; - <?php echo $pageTitle; ?></title>

        <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
        <!-- Bootstrap core CSS -->

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css">

        <link rel="stylesheet" type="text/css" href="css/forms.css?v=<?php echo rand (1, 10); ?>" />
        <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo rand (1, 10); ?>" />
        <link rel="stylesheet" type="text/css" href="css/dashboard.css?v=<?php echo rand (1, 10); ?>" />

        <script src="javascript/validationForms.js"></script>
        <script src="jquery/jquery.js"></script>
    </head>

    <body>

        <nav class="navbar navbar-expand-md navbar-light sticky-top bg-light flex-md-nowrap p-0 shadow-sm">
            <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="dashboard.php"><img src="images/AdHound_Logo.gif" style="width:100%; padding:10px;" border="0" alt="AdHound&trade; - It's Advertising, LLC" /></a>

            <input class="form-control w-100" type="text" data-toggle="popover" data-placement="bottom" data-content="Example => location: location name" placeholder="Search Locations" aria-label="Search" onkeyup="searchString(this.value)">
            <script>
                $(document).ready(function(){
                    $('[data-toggle="popover"]').popover();
                });
            </script>
            <ul class="navbar-nav px-3">
                <li class="nav-item text-nowrap">
                    <a class="nav-link" href="logout.php"><span class="fas fa-sign-out-alt"></span>Logout</a>
                </li>
            </ul>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
            </button>
        </nav>

        <div class="container-fluid">
            <div class="row">

                <nav class="col-lg-2 col-md-3 d-md-block bg-light sidebar collapse navbar-collapse" id="collapsibleNavbar">
                  <div class="sidebar-sticky">
                    <ul id="dashboardMenu" class="nav flex-column list-group">
                      <li class="nav-item list-group-item border-top-0 align-items-center">
                        <a class="nav-link active" href="dashboard.php">
                          <span class="fas fa-tachometer-alt"></span>
                          Dashboard <span class="sr-only">(current)</span>
                        </a>
                      </li>
                      <li class="nav-item list-group-item align-items-center">
                        <a class="nav-link" href="#locations" data-toggle="collapse">
                          <span class="fas fa-map-marked-alt"></span>
                          Locations
                          <span id="locationCount" class="badge badge-secondary">
                              <?php
                                echo $adhound->locations->locationCount($_SESSION['user']['user_id']);
                              ?>
                          </span>
                        </a>

                        <ul class="nav collapse list-group" style="padding-left:1rem" id="locations" data-parent="#dashboardMenu">
                            <li class="nav-item list-group-item align-items-center border-0" data-toggle="collapse" data-target="#collapsibleNavbar">
                                <a class="nav-link" onclick="getLocations(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-eye"></span>
                                    View
                                </a>
                            </li>
                            <li class="nav-item list-group-item align-items-center border-0 d-none d-md-block">
                                <a class="nav-link" onclick="getAddLocationForm(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-plus-square"></span>
                                    Add
                                </a>
                            </li>
                        </ul>

                      </li>
                      <li class="nav-item list-group-item align-items-center">
                        <a class="nav-link" href="#panels" data-toggle="collapse">
                          <span class="fas fa-th-large"></span>
                          Panels
                        </a>

                        <ul class="nav flex-column collapse list-group" style="padding-left:1rem" id="panels" data-parent="#dashboardMenu">
                            <li class="nav-item list-group-item align-items-center border-0" data-toggle="collapse" data-target="#collapsibleNavbar">
                                <a class="nav-link" onclick="getPanels(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-eye"></span>
                                    View
                                </a>
                            </li>
                            <li class="nav-item list-group-item align-items-center border-0 d-none d-md-block">
                                <a class="nav-link" onclick="getAddPanelForm(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-plus-square"></span>
                                    Add
                                </a>
                            </li>
                        </ul>

                      </li>
                      <li class="nav-item list-group-item align-items-center d-none d-md-block">
                        <a class="nav-link" href="#advertisers" data-toggle="collapse">
                          <span class="fas fa-address-card"></span>
                          Advertisers
                        </a>

                        <ul class="nav flex-column collapse list-group" style="padding-left:1rem" id="advertisers" data-parent="#dashboardMenu">
                            <li class="nav-item list-group-item align-items-center border-0">
                                <a class="nav-link" onclick="getAdvertisers(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-eye"></span>
                                    View
                                </a>
                            </li>
                            <li class="nav-item list-group-item align-items-center border-0">
                                <a class="nav-link" onclick="getAddAdvertiserForm(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-plus-square"></span>
                                    Add
                                </a>
                            </li>
                        </ul>

                      </li>
                      <li class="nav-item list-group-item align-items-center">
                        <a class="nav-link" href="#advertisements" data-toggle="collapse">
                          <span class="far fa-file-image"></span>
                          Advertisements
                        </a>

                        <ul class="nav flex-column collapse list-group" style="padding-left:1rem" id="advertisements" data-parent="#dashboardMenu">
                            <li class="nav-item list-group-item align-items-center border-0" data-toggle="collapse" data-target="#collapsibleNavbar">
                                <a class="nav-link" onclick="getAdvertisements(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-eye"></span>
                                    View
                                </a>
                            </li>
                            <li class="nav-item list-group-item align-items-center border-0 d-none d-md-block">
                                <a class="nav-link" onclick="getAddAdvertisementForm(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-plus-square"></span>
                                    Add
                                </a>
                            </li>
                        </ul>

                      </li>
                      <li class="nav-item list-group-item align-items-center d-none d-md-block">
                        <a class="nav-link" href="#users" data-toggle="collapse">
                          <span class="fas fa-users"></span>
                          Users
                        </a>

                        <ul class="nav flex-column collapse list-group" id="users" data-parent="#dashboardMenu">
                            <li class="nav-item list-group-item align-items-center border-0">
                                <a class="nav-link" onclick="getUsers(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-eye"></span>
                                    View
                                </a>
                            </li>
                            <li class="nav-item list-group-item align-items-center border-0">
                                <a class="nav-link" onclick="getAddUserForm(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-plus-square"></span>
                                    Add
                                </a>
                            </li>
                        </ul>

                      </li>
                      <li class="nav-item list-group-item align-items-center">
                        <a class="nav-link" href="#reports" data-toggle="collapse">
                          <span class="fas fa-chart-bar"></span>
                          Reports
                        </a>

                        <ul class="nav flex-column collapse list-group data-toggle="collapse" data-target="#collapsibleNavbar"" id="reports" data-parent="#dashboardMenu">
                            <li class="nav-item list-group-item align-items-center border-0">
                                <a class="nav-link" onclick="getRunReports(<?php echo $_SESSION['user']['user_id']; ?>)">
                                    <span class="fas fa-eye"></span>
                                    Run Reports
                                </a>
                            </li>
                        </ul>

                      </li>
                    </ul>
                  </div>
                </nav>

                <main role="main" class="col-lg-10 col-md-9">



