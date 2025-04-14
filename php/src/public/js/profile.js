import { createSpinner } from "./ui.js";
import { getUserAvatarElement } from "./header.js";
import { Utils } from "./utils.js";
import { validatePassword } from "./validation.js";

function addTextEditListeners(tableRowId, fetchUrl, errorPrefix)  {

    let tableRow = document.getElementById(tableRowId);
    let displayText = tableRow.querySelector(".profileDisplayText");
    let textInput = tableRow.querySelector(".profileTextInput");
    let editButtonsContainer = tableRow.querySelector(".profileEditButtonsContainer");
    let changeButton = tableRow.querySelector(".profileEditButton");
    let saveButton = tableRow.querySelector(".profileSaveButton");
    let cancelButton = tableRow.querySelector(".profileCancelButton");
    let errorText = tableRow.querySelector(".profileErrorText");

    function enableEditing() {
        textInput.value = displayText.textContent;
        displayText.classList.add("hidden");
        textInput.classList.remove("hidden");
        changeButton.classList.add("hidden");
        saveButton.classList.remove("hidden");
        cancelButton.classList.remove("hidden");
        textInput.focus();
    }

    function disableEditing(confirm) {
        if (confirm) {
            displayText.textContent = textInput.value;
        }
        displayText.classList.remove("hidden");
        textInput.classList.add("hidden");
        changeButton.classList.remove("hidden");
        saveButton.classList.add("hidden");
        cancelButton.classList.add("hidden");
        errorText.classList.add("hidden");
    }

    changeButton.addEventListener("click", () => {
        enableEditing();
    });

    cancelButton.addEventListener("click", () => {
        disableEditing(false);
    });

    saveButton.addEventListener("click", async () => {

        let spinner = createSpinner();
        editButtonsContainer.appendChild(spinner);
        saveButton.classList.add("hidden");
        cancelButton.classList.add("hidden");
        errorText.classList.add("hidden");

        const response = await fetch(fetchUrl, {
            method: "POST",
            body: textInput.value
        });

        spinner.remove();
        saveButton.classList.remove("hidden");
        cancelButton.classList.remove("hidden");

        Utils.handleResponse(
            response,
            (responseJson) => {
                disableEditing(true);
            },
            (errorMessage) => {
                errorText.textContent = errorMessage;
                errorText.classList.remove("hidden");
            },
            errorPrefix
        );

    });

}

function addPasswordEditListeners(tableRowId, fetchUrl, errorPrefix)  {

    let tableRow = document.getElementById(tableRowId);
    let displayText = tableRow.querySelector(".profileDisplayText");
    let oldPasswordInput = tableRow.querySelector("input[name=old_password]");
    let newPasswordInput = tableRow.querySelector("input[name=new_password]");
    let editButtonsContainer = tableRow.querySelector(".profileEditButtonsContainer");
    let changeButton = tableRow.querySelector(".profileEditButton");
    let saveButton = tableRow.querySelector(".profileSaveButton");
    let cancelButton = tableRow.querySelector(".profileCancelButton");
    let errorText = tableRow.querySelector(".profileErrorText");

    function enableEditing() {
        displayText.classList.add("hidden");
        oldPasswordInput.classList.remove("hidden");
        newPasswordInput.classList.remove("hidden");
        changeButton.classList.add("hidden");
        saveButton.classList.remove("hidden");
        cancelButton.classList.remove("hidden");
        oldPasswordInput.focus();
    }

    function disableEditing(confirm) {
        oldPasswordInput.value = "";
        newPasswordInput.value = "";
        displayText.classList.remove("hidden");
        oldPasswordInput.classList.add("hidden");
        newPasswordInput.classList.add("hidden");
        changeButton.classList.remove("hidden");
        saveButton.classList.add("hidden");
        cancelButton.classList.add("hidden");
        errorText.classList.add("hidden");
    }

    changeButton.addEventListener("click", () => {
        enableEditing();
    });

    cancelButton.addEventListener("click", () => {
        disableEditing(false);
    });

    saveButton.addEventListener("click", async () => {

        let passwordError = validatePassword(newPasswordInput.value);
        if (passwordError) {
            errorText.textContent = passwordError;
            errorText.classList.remove("hidden");
            return;
        }

        if (newPasswordInput.value === oldPasswordInput.value) {
            errorText.textContent = "Пароли совпадают";
            errorText.classList.remove("hidden");
            return;
        }

        let spinner = createSpinner();
        editButtonsContainer.appendChild(spinner);
        saveButton.classList.add("hidden");
        cancelButton.classList.add("hidden");
        errorText.classList.add("hidden");

        const response = await fetch(fetchUrl, {
            method: "POST",
            body: JSON.stringify({
                old_password: oldPasswordInput.value,
                new_password: newPasswordInput.value
            })
        });

        spinner.remove();
        saveButton.classList.remove("hidden");
        cancelButton.classList.remove("hidden");

        Utils.handleResponse(
            response,
            (responseJson) => {
                disableEditing(true);
            },
            (errorMessage) => {
                errorText.textContent = errorMessage;
                errorText.classList.remove("hidden");
            },
            errorPrefix
        );

    });

}

function addImageUploadListeners(tableRowId, fetchUrl, errorPrefix) {

    let tableRow = document.getElementById(tableRowId);
    let displayImage = tableRow.querySelector(".profileDisplayImage");
    let editButtonsContainer = tableRow.querySelector(".profileEditButtonsContainer");
    let changeButton = tableRow.querySelector(".profileEditButton");
    let imageHiddenInput = tableRow.querySelector(".profileHiddenFileInput");
    let errorText = tableRow.querySelector(".profileErrorText");

    changeButton.addEventListener("click", () => imageHiddenInput.click());

    imageHiddenInput.addEventListener("change", async () => {

        const imageFile = imageHiddenInput.files[0];
        if (!imageFile) {
            return;
        }
        const formData = new FormData();
        formData.append("image", imageFile);

        let spinner = createSpinner();
        editButtonsContainer.appendChild(spinner);
        changeButton.classList.add("hidden");
        errorText.classList.add("hidden");

        const response = await fetch(fetchUrl, {
            method: "POST",
            body: formData
        });

        spinner.remove();
        changeButton.classList.remove("hidden");

        Utils.handleResponse(
            response,
            (responseJson) => {
                displayImage.src = responseJson.image_url;
                let headerAvatar = getUserAvatarElement();
                headerAvatar.src = responseJson.image_url;
                errorText.classList.add("hidden");
            },
            (errorMessage) => {
                errorText.textContent = errorMessage;
                errorText.classList.remove("hidden");
            },
            errorPrefix
        );

    });

}

addTextEditListeners("emailRow", "/change_email", "Изменение email");
addPasswordEditListeners("passwordRow", "/change_password", "Изменение пароля");
addImageUploadListeners("avatarRow", "/change_avatar", "Изменение аватара");
