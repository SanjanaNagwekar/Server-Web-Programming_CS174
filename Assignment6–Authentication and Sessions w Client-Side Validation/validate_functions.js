
function validateName(field) {
    return (field == "") ? "No name was entered.\n" : "";
}

function validatePassword(field) {
    if (field == "") return "No Password was entered.\n";
    else if (field.length < 6)
        return "Passwords must be at least 6 characters.\n";
    else if (!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field))
        return "Passwords require one each of a-z, A-Z and 0-9.\n";
    return "";
}

function validateEmail(field) {
    if (field == "") return "Invalid Email.\n";
    else if (/^0/.test(field)) return "Invalid Email.\n";
    else if (!/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(field)) return "Invalid email format.\n";
    else if (!/^[\w.-]+@[\w.-]+\.\w+$/.test(field)) return "Invalid Email.\n";
    else if ((field.match(/@/g) || []).length > 1) return "Invalid Email.\n";
    return "";
}

function validateStudentID(field) {
    if (field == "") return "Student ID was not entered.\n";
    else if (field.length != 9 || isNaN(field))
        return "Student ID must be 9 digits.\n";
    return "";
}

function validate(form) {
    fail  = validateName(form.name.value);
    fail += validateStudentID(form.student_id.value);
    fail += validatePassword(form.password.value);
    fail += validateEmail(form.email.value);

    if (fail == "") return true;
    else { alert(fail); return false; }
}
