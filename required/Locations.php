<?php
class Locations extends AdHound {

    private $aLocation = array();
    private $Locations = array();
    private $aLevel = array();
    private $Level = array();
    private $aRoom = array();
    private $Room = array();
    private $aWall = array();
    private $Wall = array();


    function __construct () {

        parent::setMySQLi_CONN();

        $this->contacts = new Contacts();

    }

    function __destruct () {

    }

    public function locationCount($userId) {
        $queryLocations = "SELECT COUNT(*) FROM user_locations AS u INNER JOIN locations AS l ON u.location_id=l.id WHERE u.admin_id=$userId";

        $locations = mysqli_query($this->getMySQLi_CONN(), $queryLocations) or die("Could not retrieve location count.");

        return mysqli_fetch_row($locations)[0];
    }

    public function getLocation() {
        return $this->aLocation;
    }

    public function setLocation($locationId) {

        $queryLocation = "SELECT l.*, s.id AS 'stateId', s.states_Abbreviation, r.name AS 'region' FROM states AS s, location_regions AS r, locations AS l WHERE l.id=$locationId AND s.id=l.state_id AND r.id=l.region_id LIMIT 1";

        $locations = mysqli_query($this->getMySQLi_CONN(), $queryLocation) or die("Could not retrieve location.");

        if (mysqli_num_rows($locations) > 0) {

            while ($location = mysqli_fetch_assoc($locations)) {

                $this->aLocation = $location;

                $this->contacts->setLocationContacts($location['id']);

                if (count($this->contacts->getContacts()) > 0) {
                    foreach ($this->contacts->getContacts() as $contact) {
                        $this->aLocation['contacts'][] = $contact;
                    }
                }

                $queryCategories = "SELECT lcs.* FROM location_categories AS lcs INNER JOIN location_category AS lc ON lcs.id=lc.category_id WHERE location_id=$locationId";
                $categories = mysqli_query($this->getMySQLi_CONN(), $queryCategories) or die("Could not retrieve categories.");

                if (mysqli_num_rows($categories) > 0) {

                    while ($category = mysqli_fetch_assoc($categories)) {
                        $this->aLocation['categories'][] = $category;
                    }

                }

            }

        } else { }

    }

    public function getLocations() {
        return $this->Locations;
    }

    public function setLocations($userId) {

        $queryLocations = "SELECT l.*, s.id AS 'stateId', s.states_Abbreviation, r.name AS 'region' FROM states AS s, location_regions AS r, user_locations AS u INNER JOIN locations AS l ON u.location_id=l.id WHERE u.admin_id=$userId AND s.id=l.state_id AND l.region_id=r.id ORDER BY l.name ASC";

        $locations = mysqli_query($this->getMySQLi_CONN(), $queryLocations) or die("Could not retrieve locations.");

        if (mysqli_num_rows($locations) > 0) {

            $i=0;

            while ($location = mysqli_fetch_assoc($locations)) {

                $this->Locations[$i] = $location;

                $this->contacts->setLocationContacts($location['id']);

                $this->Locations[$i]['contacts'] = $this->contacts->getContacts();

                $i++;
            }

        } else { }

    }
    /**
     * addLocation adds a new location and automatically logs them in.
     */
    public function addLocation ($userId, $locationInfo) {
        // Trim and escape form data.
        $locationName = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['nameLocation']));
        $phone = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['phone']));
        $fax = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['fax']));
        $address = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['address']));
        $city = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['city']));
        $state = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['state']));
        $zipcode = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['zipcode']));
        $region = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['region']));

        $query = "INSERT INTO locations (name, phone, fax, address, city, state_id, zipcode, region_id) VALUES
                ('" . $locationName . "', '"
                    . $phone . "', '"
                    . $fax . "', '"
                    . $address . "', '"
                    . $city . "', '"
                    . $state . "', '"
                    . $zipcode . "', '"
                    . $region . "')";

        mysqli_query($this->getMySQLi_CONN(), $query) or die("Could not add location.");


        $locationId = mysqli_insert_id($this->getMySQLi_CONN());


        foreach ($locationInfo['categories'] as $categoryId) {
            $query = "INSERT INTO location_category (location_id, category_id) VALUES
                    ('" . $locationId . "', '". $categoryId . "')";

            mysqli_query($this->getMySQLi_CONN(), $query) or die("Could not join category to location.");
        }

        $query = "INSERT INTO user_locations (admin_id, location_id) VALUES
                    ('" . $userId . "', '". $locationId . "')";

        mysqli_query($this->getMySQLi_CONN(), $query) or die("Could not join location to user.");

        $_SESSION['messages'][] = '<div class="alert alert-success" role="alert">' . $locationName . ' has been added.</div>';

        //$this->userLogin($userName, $password);

        // On a successful insert the user is routed to the posts page
        //$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/dashboard.php?viewLocations=true';
        $home_url = '../dashboard.php?viewLocations=true';

        header('Location: ' . $home_url);

    }

    /**
     * addLocation adds a new location and automatically logs them in.
     */
    public function updateLocation ($locationId, $locationInfo) {
        // Trim and escape form data.
        $locationName = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['nameLocation']));
        $phone = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['phone']));
        $fax = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['fax']));
        $address = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['address']));
        $city = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['city']));
        $state = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['state']));
        $zipcode = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['zipcode']));
        $region = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($locationInfo['region']));

        $query = "UPDATE locations SET
                name='$locationName',
                phone='$phone',
                fax='$fax',
                address='$address',
                city='$city',
                state_id=$state,
                zipcode='$zipcode',
                region_id=$region
                WHERE id=$locationId";

        mysqli_query($this->getMySQLi_CONN(), $query) or die("Could not update location.");

        $deleteQuery = "DELETE FROM location_category WHERE location_id=$locationId";

        mysqli_query($this->getMySQLi_CONN(), $deleteQuery) or die("Could not remove location categories.");

        foreach ($locationInfo['categories'] as $categoryId) {
            $query = "INSERT INTO location_category (location_id, category_id) VALUES
                    ('" . $locationId . "', '". $categoryId . "')";

            mysqli_query($this->getMySQLi_CONN(), $query) or die("Could not update join category to location.");
        }

        $_SESSION['messages'][] = '<div class="alert alert-success" role="alert">' . $locationName . ' has been updated.</div>';

        $home_url = '../dashboard.php?viewLocations=true';

        //$home_url = '../dashboard.php';

        header('Location: ' . $home_url);

    }

    public function deleteLocation($locationId) {

        $queryDeleteLocation = "DELETE FROM locations WHERE id=$locationId";
        $delete = mysqli_query($this->getMySQLi_CONN(), $queryDeleteLocation) or die("Could not delete location.");

    }

    public function getLocationCategoryCheckboxes($categoriesId) {

        //echo var_dump($categoriesId);

        $index = 0;

        $categoryOptions = '';

        $queryCategories = "SELECT * FROM location_categories ORDER BY name ASC";
        $categories = mysqli_query($this->getMySQLi_CONN(), $queryCategories) or die("Could not retrieve categories.");

        while ($category = mysqli_fetch_assoc($categories)) {

            $categoryOptions .= '<div class="form-check form-check-inline">';
            $categoryOptions .= '<label for="category' . $category['id'] . '" class="form-label-check font-weight-normal">';

            if (isset($categoriesId[$index]['id']) && ($categoriesId[$index]['id'] == $category['id'])) {
                $categoryOptions .= '<input type="checkbox" class="form-check-input" id="category' . $category['id'] . '" name="categories[]" value="' . $category['id'] . '" checked>'. $category['name'];
                $index++;
            } else {
                $categoryOptions .= '<input type="checkbox" class="form-check-input" id="category' . $category['id'] . '" name="categories[]" value="' . $category['id'] . '">'. $category['name'];
            }

            $categoryOptions .= '</label>';
            $categoryOptions .= '</div>';
        }

        return $categoryOptions;

    }

    public function getLevels() {
        return $this->Levels;
    }

    public function setLevels($locationId) {

        $this->Levels = null;

        $queryLevels = "SELECT ls.* FROM location_levels AS ls INNER JOIN location_level AS l ON ls.id=l.level_id WHERE l.location_id=$locationId ORDER BY ls.name ASC";

        $levels = mysqli_query($this->getMySQLi_CONN(), $queryLevels) or die("Could not retrieve levels.");

        //if (mysqli_num_rows($levels) > 0) {

            //$i=0;

            while ($level = mysqli_fetch_assoc($levels)) {

                $this->Levels[] = $level;

            }

        //} else { }

    }

    public function getRooms() {
        return $this->Rooms;
    }

    public function setRooms($locationId, $levelId) {

        $this->Rooms = null;

        $queryRooms = "SELECT lrs.* FROM location_rooms AS lrs INNER JOIN location_room AS lr ON lrs.id=lr.room_id WHERE lr.location_id=$locationId AND lr.level_id=$levelId ORDER BY lrs.name ASC";

        $rooms = mysqli_query($this->getMySQLi_CONN(), $queryRooms) or die("Could not retrieve rooms.");

        while ($room = mysqli_fetch_assoc($rooms)) {

            $this->Rooms[] = $room;

        }

    }

    public function getWalls() {
        return $this->Walls;
    }

    public function setWalls($locationId, $levelId, $roomId) {

        $this->Walls = null;

        $queryWalls = "SELECT lws.* FROM location_walls AS lws INNER JOIN location_wall AS lw ON lws.id=lw.wall_id WHERE lw.location_id=$locationId AND lw.level_id=$levelId AND lw.room_id=$roomId ORDER BY lws.name ASC";

        $walls = mysqli_query($this->getMySQLi_CONN(), $queryWalls) or die("Could not retrieve walls.");

        while ($wall = mysqli_fetch_assoc($walls)) {

            $this->Walls[] = $wall;

        }

    }

    public function setSearchResults($userId, $searchText) {

        $queryLocations = "SELECT l.*, s.id AS 'stateId', s.states_Abbreviation, r.name AS 'region' FROM states AS s, location_regions AS r, user_locations AS u INNER JOIN locations AS l ON u.location_id=l.id WHERE u.admin_id=$userId AND s.id=l.state_id AND l.region_id=r.id AND l.name LIKE '%$searchText%' ORDER BY l.name ASC";

        $locations = mysqli_query($this->getMySQLi_CONN(), $queryLocations) or die("Could not retrieve locations.");


        if (mysqli_num_rows($locations) > 0) {

            $i=0;

            while ($location = mysqli_fetch_assoc($locations)) {

                $this->Locations[$i] = $location;

                $this->contacts->setLocationContacts($location['id']);

                $this->Locations[$i]['contacts'] = $this->contacts->getContacts();

                $i++;
            }

        } else { }

    }

}
?>