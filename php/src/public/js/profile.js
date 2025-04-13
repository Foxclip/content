import { createSpinner } from "./ui.js";

async function handleResponse(response, onSuccess, logPrefix) {
    if (response.ok) {
        let responseJson;
        try {
            responseJson = await response.json();
        } catch (error) {
            console.log(logPrefix + ": ошибка: " + error);
        }
        if (responseJson.success) {
            onSuccess(responseJson);
            console.log(logPrefix + ": успешно");
        } else {
            console.log(logPrefix + ": ошибка: " + responseJson.error);
        }
    } else {
        console.log(logPrefix + ": ошибка: " + response.statusText);
    }
}

function addTextEditListeners(tableRowId, fetchUrl, errorPrefix)  {

    let tableRow = document.getElementById(tableRowId);
    let displayText = tableRow.querySelector(".profileDisplayText");
    let textInput = tableRow.querySelector(".profileTextInput");
    let editButtonsContainer = tableRow.querySelector(".profileEditButtonsContainer");
    let changeButton = tableRow.querySelector(".profileEditButton");
    let saveButton = tableRow.querySelector(".profileSaveButton");
    let cancelButton = tableRow.querySelector(".profileCancelButton");

    changeButton.addEventListener("click", () => {
        textInput.value = displayText.textContent;
        displayText.classList.add("hidden");
        textInput.classList.remove("hidden");
        changeButton.classList.add("hidden");
        saveButton.classList.remove("hidden");
        cancelButton.classList.remove("hidden");
        textInput.focus();
    });

    cancelButton.addEventListener("click", () => {
        displayText.classList.remove("hidden");
        textInput.classList.add("hidden");
        changeButton.classList.remove("hidden");
        saveButton.classList.add("hidden");
        cancelButton.classList.add("hidden");
    });

    saveButton.addEventListener("click", async () => {

        let spinner = createSpinner();
        editButtonsContainer.appendChild(spinner);
        saveButton.classList.add("hidden");
        cancelButton.classList.add("hidden");

        const response = await fetch(fetchUrl, {
            method: "POST",
            body: JSON.stringify({
                value: textInput.value
            })
        });

        spinner.remove();
        saveButton.classList.remove("hidden");
        cancelButton.classList.remove("hidden");

        handleResponse(response, (responseJson) => {
            displayText.textContent = textInput.value;
        }, errorPrefix);

    });

}

function addImageUploadListeners(tableRowId, fetchUrl, errorPrefix) {

    let tableRow = document.getElementById(tableRowId);
    let displayImage = tableRow.querySelector(".profileDisplayImage");
    let editButtonsContainer = tableRow.querySelector(".profileEditButtonsContainer");
    let changeButton = tableRow.querySelector(".profileEditButton");
    let imageHiddenInput = tableRow.querySelector(".profileHiddenFileInput");

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

        const response = await fetch(fetchUrl, {
            method: "POST",
            body: formData
        });

        spinner.remove();
        changeButton.classList.remove("hidden");

        handleResponse(response, (responseJson) => {
            displayImage.src = responseJson.image_url;
        }, errorPrefix);

    });

}

addTextEditListeners("emailRow", "/change_email", "Изменение email");
addImageUploadListeners("avatarRow", "/change_avatar", "Изменение аватара");
