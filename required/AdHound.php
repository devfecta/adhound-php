<?php

class AdHound {

    private $mySQLi_CONN;

    public $users;
    public $locations;
    public $contacts;
    public $panels;

    function __construct () {

        // Start the session
        session_start();
        require_once('config.php');
        require_once('Users.php');
        require_once('Locations.php');
        require_once('Contacts.php');
        require_once('Panels.php');
        require_once('Views.php');

        $this->setMySQLi_CONN();


        if (mysqli_connect_errno())
        {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        }

        $this->users = new Users();
        $this->locations = new Locations();
        $this->contacts = new Contacts();
        $this->panels = new Panels();
        $this->views = new Views();

    }

    function __destruct () {
        /*
            Close the connection to the database.
        */
        mysqli_close($this->getMySQLi_CONN());
    }

    public function test($v) {
        echo $v;
    }


    public function getMySQLi_CONN() {
        return $this->mySQLi_CONN;
    }

    public function setMySQLi_CONN() {
        /*
            Create a connection to the database.
        */
        $this->mySQLi_CONN = mysqli_connect(HOSTNAME, USERNAME, PASSWORD, DATABASE)
                                or die('Error connecting to MySQL server.');
    }

    public function getStateDropdownOptions($stateId) {

        $stateOptions = '<option value="">Select A State</option>';

        $queryStates = "SELECT * FROM states ORDER BY states_Abbreviation ASC";
        $states = mysqli_query($this->getMySQLi_CONN(), $queryStates) or die("Could not retrieve states.");

        while ($state = mysqli_fetch_assoc($states)) {

            if (isset($stateId) && ($state['id'] == $stateId)) {
                $stateOptions .= '<option value="' . $state['id'] . '" selected>' . $state['states_Abbreviation'] . '</option>';
            } else {
                $stateOptions .= '<option value="' . $state['id'] . '">' . $state['states_Abbreviation'] . '</option>';
            }

        }

        return $stateOptions;

    }

    public function getRegionDropdownOptions($regionId) {

        $regionOptions = '<option value="">Select A Region</option>';

        $queryRegions = "SELECT * FROM location_regions ORDER BY name ASC";
        $regions = mysqli_query($this->getMySQLi_CONN(), $queryRegions) or die("Could not retrieve regions.");

        while ($region = mysqli_fetch_assoc($regions)) {

            if (isset($regionId) && ($region['id'] == $regionId)) {
                $regionOptions .= '<option value="' . $region['id'] . '" title="' . $region['description'] . '" selected>' . $region['name'] . '</option>';
            } else {
                $regionOptions .= '<option value="' . $region['id'] . '" title="' . $region['description'] . '">' . $region['name'] . '</option>';
            }

        }

        return $regionOptions;

    }

}

?>