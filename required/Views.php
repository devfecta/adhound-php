<?php
class Views extends AdHound {

    function __construct () {

        parent::setMySQLi_CONN();

        // $this->contacts = new Contacts();
        $this->locations = new Locations();
        $this->panels = new Panels();

    }

    function __destruct () {

    }

    public function listLocations($locations) {

        $view = '<section class="row d-flex justify-content-around">';

        if (count($locations) > 0) {

            foreach ($locations as $location) {
                $view .= '<div class="card border-secondary m-3" onclick="viewLocation(' . $location['id'] . ')">';

                    $view .= '<div class="card-header text-center"><span class="fas fa-map-marked-alt" style="font-size: 10vw;"></span></div>';

                    $view .= '<div class="card-body">';
                        $view .= '<h3 class="card-title">' . $location['name'] . '</h3>';
                        $view .= '<p class="card-text">' . $location['address'] . '<br>' . $location['city'] . ', ' . $location['states_Abbreviation'] . ' ' . $location['zipcode'] . '</p>';
                        $view .= '<p class="card-text">Phone: ' . $location['phone'] . '<br>Fax: ' . $location['fax'] . '</p>';
                    $view .= '</div>';

                $view .= '</div>';
            }

        } else {

            $view .= '<p class="pt-3 text-center">No Locations Found</p>';

        }

        $view .= '</section>';

        return $view;

    }

    public function viewLocation($location) {

        $view = '<section class="row">';
            $view .= '<div class="col-lg-5 col-md">';

                $view .= '<div class="card border-secondary mb-3">';

                    $view .= '<div class="card-header text-center"><span class="fas fa-map-marked-alt" style="font-size: 10vw;"></span></div>';

                    $view .= '<div class="card-body">';
                        $view .= '<h3 class="card-title">' . $location['name'] . '</h3>';
                        $view .= '<p class="card-text">' . $location['address'] . '<br>' . $location['city'] . ', ' . $location['states_Abbreviation'] . ' ' . $location['zipcode'] . '</p>';
                        $view .= '<p class="card-text">Phone: ' . $location['phone'] . '<br>Fax: ' . $location['fax'] . '</p>';


                        $view .= '<h5 class="card-title">Contacts</h5>';
                        if (count($location['contacts'] ) > 0) {
                            foreach($location['contacts'] as $contact) {
                                $view .= '<p class="card-text"><b>'. $contact['contactType'] . '</b>: ' . $contact['first_name'] . ' ' . $contact['last_name'] . '<br>';
                                $view .= '<b>Phone</b>: '. $contact['phone'] . '<br><b>Fax</b>: ' . $contact['fax'] . '<br><b>e-Mail</b>: <a href="mailto:'.$contact['email'].'">' . $contact['email'] . '</a></p>';
                            }
                        }


                        $view .= '<h5 class="card-title">Categories</h5>';
                        if (count($location['categories'] ) > 0) {
                            $view .= '<p class="card-text">';
                            foreach($location['categories'] as $category) {
                                $view .= $category['name'] . ' &nbsp ';
                            }
                            $view .= '</p>';
                        }

                    $view .= '</div>';

                    $view .= '<div class="card-footer d-flex justify-content-between">';
                        $view .= '<button type="button" onclick="getEditLocationForm(' . $location['id'] . ')" class="btn btn-primary"><span class="fas fa-edit"></span> Edit Location</button>';
                        $view .= '<button type="button" onclick="deleteLocation(' . $location['id'] . ')" class="btn btn-primary"><span class="fas fa-trash-alt"></span> Delete Location</button>';

                    $view .= '</div>';

                $view .= '</div>';

            $view .= '</div>';
            $view .= '<div class="col-lg-7 col-md">';


                    $view .= '<div class="card border-secondary mb-3" id="Levels">';
                        $view .= '<div class="card-header"><h3>' . $location['name'] . ' Levels <button type="button" class="btn btn-primary float-right">Add Level</button></h3></div>';
                        $view .= '<ul class="list-group list-group-flush">';

                            $this->locations->setLevels($location['id']);

                            if (count($this->locations->getLevels()) > 0) {

                                foreach($this->locations->getLevels() as $level) {
                                    $view .= '<li class="list-group-item" href="#room' . $level['id'] . '" data-toggle="collapse">' . $level['name'] . '</li>';

                                    $this->locations->setRooms($location['id'], $level['id']);

                                    $view .= '<div class="card border-secondary border-0 mb-3 collapse" id="room' . $level['id'] . '" data-parent="#Levels">';
                                        $view .= '<div class="card-header"><h4>' . $level['name'] . ' Rooms <button type="button" class="btn btn-primary float-right">Add Room</button></h4></div>';
                                        $view .= '<ul class="list-group list-group-flush pl-3">';
                                            if (count($this->locations->getRooms()) > 0) {

                                                foreach($this->locations->getRooms() as $room) {

                                                    $view .= '<li class="list-group-item" href="#wall' . $room['id'] . '" data-toggle="collapse">' . $room['name'] . '</li>';

                                                    $this->locations->setWalls($location['id'], $level['id'], $room['id']);

                                                    $view .= '<div class="card border-secondary border-0 mb-3 collapse" id="wall' . $room['id'] . '" data-parent="#room' . $level['id'] . '">';
                                                        $view .= '<div class="card-header"><h5>' . $room['name'] . ' Walls <button type="button" class="btn btn-primary float-right">Add Wall</button></h5></h4></div>';
                                                        $view .= '<ul class="list-group list-group-flush pl-3">';
                                                            if (count($this->locations->getWalls()) > 0) {

                                                                foreach($this->locations->getWalls() as $wall) {

                                                                    $view .= '<li class="list-group-item" onclick="viewWallPanels(' . $location['id'] . ', ' . $level['id'] . ', ' . $room['id'] . ', ' . $wall['id'] . ')">' . $wall['name'] . '</li>';

                                                                }

                                                            } else {
                                                                $view .= '<li class="list-group-item">No Walls Found</li>';
                                                            }
                                                        $view .= '</ul>';


                                                    $view .= '</div>';

                                                }

                                            } else {
                                                $view .= '<li class="list-group-item">No Rooms Found</li>';
                                            }
                                        $view .= '</ul>';


                                    $view .= '</div>';
                                }

                            } else {
                                $view .= '<li class="list-group-item">No Levels Found</li>';
                            }

                        $view .= '</ul>';

                    $view .= '</div>';

            $view .= '</div>';



        $view .= '</section>';

        return $view;
    }

    public function viewWallPanels($panels) {

        $view = '<div class="row d-flex justify-content-around">';

        if (count($panels) > 0) {

            foreach ($panels as $panel) {
                $view .= '<div class="card border-secondary m-3" style="width:' . ($panel['width'] * 72) / 10 . 'px; max-height:' . ($panel['height'] * 72) / 10  . 'px;">';
                    $view .= '<div class="card-header text-center">' . $panel['name'] . '</div>';

                    $view .= '<div class="card-body">';
                        $view .= '<img src="images/AdHound_Icon.png" class="card-img-top" />';
                    $view .= '</div>';

                    $view .= '<div class="card-footer text-center">' . !empty($panel['description']) ? '<p>' . $panel['description'] . '</p>' : ''  . '<button type="button" class="btn btn-primary">Edit Panel</button></div>';
                $view .= '</div>';
            }

        } else {
            $view .= '<p class="pt-3 text-center">No Panels Found</p>';
        }

        $view .= '</div>';

        return $view;

    }


}
?>