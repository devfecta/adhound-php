var validForm;

/*
    Appends the Javascript file validationStyles.js.
*/
/*
function importValidationStyles() {
    "use strict";

    var headTag;
    var scriptTag;

    headTag = document.getElementsByTagName("head")[0];

    scriptTag = document.createElement("script");
    scriptTag.id = "FieldStyles";
    scriptTag.type= "text/javascript";
    scriptTag.src= "../validationStyles.js";

    headTag.appendChild(scriptTag);

    return false;
}
*/
/*
    importValidationStyles must load with the HTML page
*/
//importValidationStyles();

/*
    Validates the required fields specific to the current form
*/
function formValidation(currentForm) {
    "use strict";

    if (!currentForm.checkValidity()) {
        validForm = false;
        currentForm.classList.add('was-validated');
    } else {
        validForm = true;
    }

    switch (currentForm.name) {
        case 'signupForm':
            // Password fields validation
            if (document.getElementById('passwordId').value !== document.getElementById('passwordReEnterId').value) {
                document.getElementById('passwordErrorId').innerHTML = "Passwords must match.";
                document.getElementById('passwordErrorId').style.display = 'unset';
                validForm = false;
                return validForm;
            }
            break;

        case 'addLocationForm':
            break;

        case 'editLocationForm':
            break;

        case 'loginForm':
            break;

        case 'logForm':
            break;

        default:
            break;
    }

    return validForm;
}