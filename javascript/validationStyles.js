/*
    Changes the field(s) border and background
*/
function assignFieldStyles(validField, fieldID) {
    "use strict";

    var fieldSelected;
    fieldSelected = false;

    if (!validField) {
        // If validation fails assign a CSS class that changes the field(s) border and background to red
        document.getElementById(fieldID).style = "border: 1px solid red; background-color: #ffeeee;";

        if (!fieldSelected) {
            if (document.getElementById(fieldID).type === "select-one" || document.getElementById(fieldID).type === "textarea") {
                document.getElementById(fieldID).focus();
            } else {
                document.getElementById(fieldID).select();
            }

            fieldSelected = true;
        }

        validForm = false;
    } else {
        // If validation passes remove CSS class that changes the field(s) border and background to red
        document.getElementById(fieldID).style = "";
    }

    return validForm;
}

/*
    Resets form stylings
*/
function resetForm(form) {
    "use strict";

    // Local Constants and Variables Declaration
    var fieldCount;
    var fieldID;

    // Variable Assignment
    fieldCount = 0;

    while(fieldCount < form.elements.length) {
        fieldID = form.elements[fieldCount].id;
        if (fieldID !== "") {
            document.getElementById(fieldID).classList.remove("requiredField");
        }

        fieldCount += 1;
    }

    if (form.name === 'customerCommentForm') {
        document.getElementById("phoneLabel").style.display = 'none';
        document.getElementById("emailLabel").style.display = 'none';
    }

}