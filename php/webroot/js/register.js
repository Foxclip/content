let passwordInput1 = document.getElementById("passwordInput1");
let passwordInput2 = document.getElementById("passwordInput2");
let passwordMismatchError = document.getElementById("passwordInput2").nextElementSibling;
let submitButton = document.getElementById("submitButton");
let password2Touched = false;

function getCssVariable(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(name);
}

let formInputBorderColor = getCssVariable("--form-input-border-color");
let formInputErrorColor = getCssVariable("--form-input-error-color");

function updatePasswordMismatchError() {
    if (password2Touched) {
        if (passwordInput1.value === passwordInput2.value) {
            passwordMismatchError.style.visibility =  "hidden";
            passwordInput2.style.borderColor = formInputBorderColor;
            submitButton.disabled = false;
        } else {
            passwordMismatchError.style.visibility = "visible";
            passwordMismatchError.textContent = "Пароли не совпадают";
            passwordInput2.style.borderColor = formInputErrorColor;
            submitButton.disabled = true;
        }
    } else {
        passwordMismatchError.style.visibility = "hidden";
        passwordInput2.style.borderColor = formInputBorderColor;
        submitButton.disabled = false;
    }
}

passwordInput1.addEventListener("input", function() {
    updatePasswordMismatchError();
});

passwordInput2.addEventListener("input", function() {
    password2Touched = true;
    updatePasswordMismatchError();
});
