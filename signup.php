<?php
    require("required/AdHound.php");
    // Creates a reference to the AdHound class
    $adhound = new AdHound();

    $pageTitle = 'Sign-Up';

    if (isset($_POST['signupButton'])) {

        $adhound->users->userSignUp($_POST);

    }
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>AdHound&trade; - <?php echo $pageTitle; ?></title>

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>


        <link rel="stylesheet" type="text/css" href="css/externalForms.css?v=<?php echo rand (1, 100); ?>" />
        <link rel="stylesheet" type="text/css" href="css/forms.css?v=<?php echo rand (1, 100); ?>" />
        <link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo rand (1, 100); ?>" />


        <script src="javascript/validationForms.js"></script>

    </head>
    <body>

        <div class="container">
            <div class="row">
            <form method="post" class="form form-signup needs-validation" onsubmit="return formValidation(this)" novalidate name="signupForm" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <img class="mb-3" src="images/AdHound_Logo.gif" alt="AdHound&trade; - It's Advertising, LLC" width="258" height="60">
                <h1 class="h5 mb-3 font-weight-normal">Sign Up TODAY!</h1>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="usernameId">Username</label>
                        <input type="text" class="form-control" id="usernameId" name="username" maxlength="24" placeholder="" pattern="[\d+\s+\w]{5,24}$" value="<?php echo $_POST['username']; ?>" required>
                        <div class="invalid-feedback">
                          Username must be 5-24 characters using only letters and numbers.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="passwordId">Password</label>
                        <input type="password" class="form-control" id="passwordId" name="password" maxlength="128" placeholder="" pattern="^[a-zA-Z0-9!@#$^&]{6,128}$" value="" required>
                        <div id="passwordErrorId" class="invalid-feedback">
                          Password must be 6-128 characters. Use only ! @ # $ ^ & for special characters.
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="passwordReEnterId">Re-Enter Password</label>
                        <input type="password" class="form-control" id="passwordReEnterId" name="passwordReEnter" maxlength="128" placeholder="" pattern="^[a-zA-Z0-9!@#$^&]{6,128}$" value="" required>
                        <div class="invalid-feedback">
                          Password must be 6-128 characters. Use only ! @ # $ ^ & for special characters.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="firstNameId">First name</label>
                        <input type="text" class="form-control" id="firstNameId" name="firstName" maxlength="24" placeholder="" pattern="[\d+\s+\w]{3,24}$" value="<?php echo $_POST['firstName']; ?>" required>
                        <div class="invalid-feedback">
                          First name must be 3-24 letters.
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="lastNameId">Last name</label>
                        <input type="text" class="form-control" id="lastNameId" name="lastName" maxlength="24" placeholder="" pattern="[\d+\s+\w]{3,24}$" value="<?php echo $_POST['lastName']; ?>" required>
                        <div class="invalid-feedback">
                          Last name must be 3-24 letters.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phoneId">Phone</label>
                        <input type="text" class="form-control" id="phoneId" name="phone" maxlength="15" placeholder="" pattern="^\(?[2-9]\d{2}\)?[-\.\s]\d{3}[-\.\s]\d{4}([-\.\s]\d{4})?$" value="<?php echo $_POST['phone']; ?>" required>
                        <div class="invalid-feedback">
                          Enter a valid phone number. Example: (xxx) xxx-xxxx
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="faxId">Fax</label>
                        <input type="text" class="form-control" id="faxId" name="fax" maxlength="15" placeholder="" pattern="^\(?[2-9]\d{2}\)?[-\.\s]\d{3}[-\.\s]\d{4}([-\.\s]\d{4})?$" value="<?php echo $_POST['fax']; ?>">
                        <div class="invalid-feedback">
                          Enter a valid fax number. Example: (xxx) xxx-xxxx
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="emailId">e-Mail Address</label>
                        <input type="text" class="form-control" id="emailId" name="email" maxlength="128" placeholder="" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="<?php echo $_POST['email']; ?>" required>
                        <div class="invalid-feedback">
                          Enter a valid e-mail address.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="addressId">Street Address</label>
                        <input type="text" class="form-control" id="addressId" name="address" maxlength="64" placeholder="" pattern="[\d+\s+\w]{3,64}" value="<?php echo $_POST['address']; ?>" required>
                        <div class="invalid-feedback">
                          Enter a valid street address.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="cityId">City</label>
                        <input type="text" class="form-control" id="cityId" name="city" maxlength="24" placeholder="" pattern="^[\d+\s+\w]{3,24}$" value="<?php echo $_POST['city']; ?>" required>
                        <div class="invalid-feedback">
                          City name must be 3-24 letters.
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="stateId">State</label>
                        <select class="form-control" id="stateId" name="state" pattern="^\d{1,2}$" required>
                            <?php echo $adhound->getStateDropdownOptions($_POST['state']); ?>
                        </select>
                        <div class="invalid-feedback">
                          Select a state.
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="zipcodeId">Zipcode</label>
                        <input type="text" class="form-control" id="zipcodeId" name="zipcode" maxlength="11" placeholder="" pattern="\d{5}(-\d{4})?" value="<?php echo $_POST['zipcode']; ?>" required>
                        <div class="invalid-feedback">
                          Zipcode must be 5-11 letters.
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block" id="signupButtonId" name="signupButton">Sign-Up</button>
                <p class="mt-5 mb-3 text-center text-muted">AdHound&trade; Copyright &copy; <?php echo date('Y'); ?></p>
            </form>

            <?php
                // Unsets all backend error messages after they were displayed
                if (isset($_SESSION['messages'])) {
                foreach ($_SESSION['messages'] as $e => $error) {
                    unset($_SESSION['messages'][$e]);
                }
                unset($_SESSION['messages']);
                }

                /*
                Remove any values in these object references.
                */
                $adhound = null;
            ?>
            </div>
        </div>
    </body>
</html>