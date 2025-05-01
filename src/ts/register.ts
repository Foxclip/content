import { Utils } from './utils';
import { validatePassword } from './validation';

let form = document.getElementById("form") as HTMLFormElement;
let loginInput = document.getElementById("loginInput") as HTMLInputElement;
let emailInput = document.getElementById("emailInput") as HTMLInputElement;
let passwordInput1 = document.getElementById("passwordInput1") as HTMLInputElement;
let passwordInput2 = document.getElementById("passwordInput2") as HTMLInputElement;
let inputs = document.querySelectorAll<HTMLInputElement>(".formInput");
let submitButton = document.getElementById("submitButton") as HTMLElement;
let formInputBorderColor = Utils.getCssVariable("--form-input-border-color");
let formInputErrorColor = Utils.getCssVariable("--form-input-error-color");

function setInputErrorState(inputElement: HTMLInputElement, isOk: boolean, errorText = "") {
    let errorElement = inputElement.nextElementSibling as HTMLElement;
    if (isOk) {
        inputElement.style.borderColor = formInputBorderColor;
        errorElement.style.display = "none";
    } else {
        inputElement.style.borderColor = formInputErrorColor;
        errorElement.style.display = "block";
        errorElement.textContent = errorText || "Текст ошибки";
    }
}

function validateForm() {
    let result = true;
    for (let input of inputs) {
        setInputErrorState(input, true);
        if (input.type !== "password") {
            input.value = input.value.trim();
        }
    }
    if (loginInput.value.length < 3 || loginInput.value.length > 20) {
        setInputErrorState(loginInput, false, "Логин должен содержать от 3 до 20 символов");
        result = false;
    }
    if (!loginInput.value.match(/^\w+$/)) {
        setInputErrorState(loginInput, false, "Логин должен содержать только латинские буквы, цифры и нижнее подчеркивание");
        result = false;
    }
    if (!emailInput.value.match(/^\S+@\S+$/)) {
        setInputErrorState(emailInput, false, "Некорректный email");
        result = false;
    }
    let passwordError = validatePassword(passwordInput1.value);
    if (passwordError) {
        setInputErrorState(passwordInput1, false, passwordError);
        result = false;
    }
    if (passwordInput1.value !== passwordInput2.value) {
        setInputErrorState(passwordInput2, false, "Пароли не совпадают");
        result = false;
    }
    return result;
}

form.addEventListener("submit", (event) => {
    event.preventDefault();
    let valid = validateForm();
    if (valid) {
        form.submit();
    }
});
