function $(id) {
    return document.getElementById(id);
}

function validateName(field) {
    if (field === "") return "No name was entered.\n";
    return "";
}

function validateUsername(field) {
    if (field === "") return "No username was entered.\n";
    return "";
}

function validatePassword(field) {
    if (field === "") return "No password was entered.\n";
    else if (field.length < 6)
        return "Passwords must be at least 6 characters.\n";
    else if (!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field))
        return "Passwords require one each of a-z, A-Z, and 0-9.\n";
    return "";
}

function validateFile(field) {
    var fileName = field.value;
    var allowedExtensions = /(\.txt)$/i;
    if (!allowedExtensions.exec(fileName)) {
        alert('Only .txt files are allowed!');
        field.value = '';
        return false;
    }
    return true;
}

document.addEventListener('DOMContentLoaded', function() {
    var fileInput = $('file');
    if (fileInput) {
        fileInput.addEventListener('change', function() {
            validateFile(this);
        });
    }
});

function validateSignupForm(form) {
    var fail = "";
    fail += validateName(form.name.value);
    fail += validateUsername(form.username.value);
    fail += validatePassword(form.password.value);

    if (fail === "") return true;
    else {
        alert(fail);
        return false;
    }
}

function validateLoginForm(form) {
    var fail = "";
    fail += validateUsername(form.username.value);
    fail += validatePassword(form.password.value);

    if (fail === "") return true;
    else {
        alert(fail);
        return false;
    }
}

function validateFileUpload(form) {
    var fileInput = form.file;
    return validateFile(fileInput);
}
