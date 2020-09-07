<?php
session_start();
include "../required/AdHound.php";

$adhound = new AdHound();

switch ($_POST['FunctionType'])
{
    case 'getLocations':
        $adhound->locations->setLocations($_SESSION['user']['user_id']);
        $locationInfo['locations'] = $adhound->views->listLocations($adhound->locations->getLocations());
        $locationInfo['count'] = $adhound->locations->locationCount($_SESSION['user']['user_id']);
        echo json_encode($locationInfo);

        // echo json_encode($adhound->locations->getLocations());

        break;
    case 'getAddLocationForm':
        require_once('../forms/addLocation.php');
        break;

    case 'getEditLocationForm':
        $_SESSION['locationId'] = $_POST['locationID'];
        require_once('../forms/editLocation.php');
        break;

    case 'deleteLocation':
        $adhound->locations->deleteLocation($_POST['locationID']);
        $adhound->locations->setLocations($_SESSION['user']['user_id']);
        $locationInfo['locations'] = $adhound->views->listLocations($adhound->locations->getLocations());
        $locationInfo['count'] = $adhound->locations->locationCount($_SESSION['user']['user_id']);
        echo json_encode($locationInfo);
        break;

    case 'viewLocation':
        $adhound->locations->setLocation($_POST['locationID']);
        $locationInfo['location'] = $adhound->views->viewLocation($adhound->locations->getLocation());
        echo json_encode($locationInfo);
        break;

    case 'viewWallPanels':
        $adhound->panels->setWallPanels($_POST['locationID'], $_POST['levelID'], $_POST['roomID'], $_POST['wallID']);
        $panelInfo['panels'] = $adhound->views->viewWallPanels($adhound->panels->getWallPanels());
        echo json_encode($panelInfo);
        break;

    case 'searchString':
        $adhound->locations->setSearchResults($_SESSION['user']['user_id'], $_POST['search']);
        $locationInfo['locations'] = $adhound->views->listLocations($adhound->locations->getLocations());

        //$locationInfo['locations'] = $adhound->locations->getLocations();

        echo json_encode($locationInfo);
        break;
    default:
        break;
}

?>