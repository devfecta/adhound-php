<?php
class Users extends AdHound {

    private $aUser;
    private $Users;


    function __construct () {

        parent::setMySQLi_CONN();

    }

    function __destruct () {

    }

    public function getUserInfo() {
        return $this->aUser;
    }
    public function setUserInfo($id) {

        $query = "SELECT * FROM users WHERE id = '$id' LIMIT 1";

        $results = mysqli_query($this->getMySQLi_CONN(), $query)
                or die("Could not retrieve user information.");

        while ($array = mysqli_fetch_assoc($results)) {
            $this->aUser[] = $array;
        }

    }
    /**
     * userLogin logs in the user and redirects them to their dashboard page.
     */
    public function userLogin ($userName, $password) {
        // Trim and escape form data.
        $userName = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userName));
        $password = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($password));

        // Look up the username in the database
        $query = "SELECT id, username, password FROM users WHERE username = '$userName' LIMIT 1";
        $data = mysqli_query($this->getMySQLi_CONN(), $query) or die("Could not find username.");

        // If the username is found check the password and then redirect to the user's profile page.
        if (mysqli_num_rows($data) > 0) {

            $row = mysqli_fetch_array($data);

            if (password_verify($password, $row['password'])) {

                $_SESSION['user']['user_id'] = $row['id'];
                $_SESSION['user']['username'] = $row['username'];

                // On a successful login the user is routed to the Profile page
                $home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/dashboard.php';

                header('Location: ' . $home_url);

            }
            else {
                $_SESSION['errorMessages'][] = "- Invalid password";
            }

        }
        else {

            $_SESSION['errorMessages'][] = "- Invalid username";

        }

    }
    /**
     * userSignUp signs up a new user and automatically logs them in.
     */
    public function userSignUp ($userInfo) {
        // Trim and escape form data.
        $userName = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['username']));
        $password = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['password']));
        $firstName = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['firstName']));
        $lastName = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['lastName']));
        $phone = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['phone']));
        $fax = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['fax']));
        $email = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['email']));
        $address = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['address']));
        $city = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['city']));
        $state = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['state']));
        $zipcode = mysqli_real_escape_string($this->getMySQLi_CONN(), trim($userInfo['zipcode']));

        $passwordEncrypted = password_hash($password, PASSWORD_DEFAULT);

        $query = "INSERT INTO users (username, password, first_name, last_name, phone, fax, email, address, city, state_id, zipcode) VALUES
                ('" . $userName . "', '"
                    . $passwordEncrypted . "', '"
                    . $firstName . "', '"
                    . $lastName . "', '"
                    . $phone . "', '"
                    . $fax . "', '"
                    . $email . "', '"
                    . $address . "', '"
                    . $city . "', '"
                    . $state . "', '"
                    . $zipcode . "')";

        mysqli_query($this->getMySQLi_CONN(), $query) or die("Could not add user.");

        $this->userLogin($userName, $password);
    }

}
?>