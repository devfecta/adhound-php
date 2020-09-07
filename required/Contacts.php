<?php
class Contacts extends AdHound {

    private $aContact;
    private $Contacts = array();
    private $ContactsArray;


    function __construct () {

        parent::setMySQLi_CONN();

    }

    function __destruct () {

    }

    public function getContacts() {
        return $this->Contacts;
    }

    public function getContactsArray() {
        return $this->ContactsArray;
    }

    public function setLocationContacts($locationId) {

        $queryContacts = "SELECT c.*, ct.name AS 'contactType', s.id AS 'stateId', s.states_Abbreviation FROM states AS s, contact_types AS ct, location_contacts AS c INNER JOIN location_contact AS lc ON c.id=lc.contact_id WHERE lc.location_id=$locationId AND s.id=c.state_id AND ct.id=c.type_id ORDER BY ct.id, c.last_name, c.first_name ASC";

        $contacts = mysqli_query($this->getMySQLi_CONN(), $queryContacts) or die("Could not retrieve contacts.");

        $this->Contacts = null;

        while ($contact = mysqli_fetch_assoc($contacts)) {

            $this->Contacts[] = $contact;

        }

    }

}
?>