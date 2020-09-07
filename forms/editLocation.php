<?php

    // Creates a reference to the AdHound class


    if (isset($_POST['submitButton'])) {
        require("../required/AdHound.php");
        $adhound = new AdHound();

        $adhound->locations->updateLocation($_SESSION['locationId'], $_POST);

    }

    $adhound->locations->setLocation($_SESSION['locationId']);

    //$locations = json_encode($adhound->locations->getLocation());

    //echo var_dump($adhound->locations->getLocation()[0]);


?>

            <form id="editLocationForm" name="editLocationForm" method="post" class="form form-signup needs-validation" onsubmit="return formValidation(this)" novalidate action="forms/editLocation.php">

                <h1 class="h5 mb-3 font-weight-normal">Edit Location</h1>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="nameId">Location Name</label>
                        <input type="text" class="form-control" id="nameId" name="nameLocation" maxlength="48" placeholder="" pattern="[\d+\s+\w]{3,48}$" value="<?php echo $adhound->locations->getLocation()['name'] ?>" required>
                        <div class="invalid-feedback">
                          Location name must be 3-48 characters using only letters and numbers.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="phoneId">Phone</label>
                        <input type="text" class="form-control" id="phoneId" name="phone" maxlength="15" placeholder="" pattern="^\(?[2-9]\d{2}\)?[-\.\s]\d{3}[-\.\s]\d{4}([-\.\s]\d{4})?$" value="<?php echo $adhound->locations->getLocation()['phone']; ?>" required>
                        <div class="invalid-feedback">
                          Enter a valid phone number. Example: (xxx) xxx-xxxx
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="faxId">Fax</label>
                        <input type="text" class="form-control" id="faxId" name="fax" maxlength="15" placeholder="" pattern="^\(?[2-9]\d{2}\)?[-\.\s]\d{3}[-\.\s]\d{4}([-\.\s]\d{4})?$" value="<?php echo $adhound->locations->getLocation()['fax']; ?>">
                        <div class="invalid-feedback">
                          Enter a valid fax number. Example: (xxx) xxx-xxxx
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="addressId">Street Address</label>
                        <input type="text" class="form-control" id="addressId" name="address" maxlength="64" placeholder="" pattern="[\d+\s+\w]{3,128}" value="<?php echo $adhound->locations->getLocation()['address']; ?>" required>
                        <div class="invalid-feedback">
                          Enter a valid street address.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="cityId">City</label>
                        <input type="text" class="form-control" id="cityId" name="city" maxlength="24" placeholder="" pattern="[\d+\s+\w]{3,24}$" value="<?php echo $adhound->locations->getLocation()['city']; ?>" required>
                        <div class="invalid-feedback">
                          City name must be 3-24 letters.
                        </div>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="stateId">State</label>
                        <select class="form-control" id="stateId" name="state" pattern="^\d{1,2}$" required>
                            <?php echo $adhound->getStateDropdownOptions($adhound->locations->getLocation()['state_id']); ?>
                        </select>
                        <div class="invalid-feedback">
                          Select a state.
                        </div>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="zipcodeId">Zipcode</label>
                        <input type="text" class="form-control" id="zipcodeId" name="zipcode" maxlength="11" placeholder="" pattern="\d{5}(-\d{4})?" value="<?php echo $adhound->locations->getLocation()['zipcode']; ?>" required>
                        <div class="invalid-feedback">
                          Zipcode must be 5-11 letters.
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col">
                        <label for="regionId">Region</label>
                        <select class="form-control" id="regionId" name="region" pattern="^\d{1,4}$" required>
                            <?php echo $adhound->getRegionDropdownOptions($adhound->locations->getLocation()['region_id']); ?>
                        </select>
                        <div class="invalid-feedback">
                          Select a region.
                        </div>
                    </div>
                </div>

                <p>Categories</p>
                <div class="form-row">
                    <div class="form-group col">
                        <?php echo $adhound->locations->getLocationCategoryCheckboxes($adhound->locations->getLocation()['categories']); ?>
                        <div class="invalid-feedback">
                          Select a region.
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-lg btn-primary btn-block" id="submitButtonId" name="submitButton">Update Location</button>

                <button type="button" onclick="getLocations(<?php echo $_SESSION['user']['user_id']; ?>)" class="btn btn-lg btn-primary btn-block" id="cancelButtonId" name="cancelButton">Cancel</button>

            </form>