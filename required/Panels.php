<?php
class Panels extends AdHound {

    private $aPanel = array();
    private $Panels = array();


    function __construct () {

        parent::setMySQLi_CONN();

    }

    function __destruct () {

    }

    public function getWallPanels() {
        return $this->Panels;
    }

    public function setWallPanels($locationId, $levelId, $roomId, $wallId) {

        $queryPanels = "SELECT * FROM location_panels AS lps INNER JOIN location_panel AS lp ON lps.id=lp.panel_id WHERE lp.location_id=$locationId AND lp.level_id=$levelId AND lp.room_id=$roomId AND lp.wall_id=$wallId ORDER BY lps.name ASC";

        $panels = mysqli_query($this->getMySQLi_CONN(), $queryPanels) or die("Could not retrieve panels.");

        while ($panel = mysqli_fetch_assoc($panels)) {

            $this->Panels[] = $panel;

        }

    }

}
?>