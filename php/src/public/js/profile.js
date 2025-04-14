import { createSpinner } from "./ui.js";
import { getUserAvatarElement } from "./header.js";
import { Utils } from "./utils.js";
import { validatePassword } from "./validation.js";

async function performRequest({
    editButtonsContainer,
    buttonsToHide = [],
    errorText,
    fetchUrl,
    method,
    body,
    headers = {},
    errorPrefix,
    onSuccess,
    onCleanup
}) {
    const spinner = createSpinner();
    editButtonsContainer.appendChild(spinner);
    buttonsToHide.forEach(button => button.classList.add("hidden"));
    errorText.classList.add("hidden");

    let response;
    try {
        response = await fetch(fetchUrl, { method, body, headers });
    } catch (error) {
        errorText.textContent = `${errorPrefix}: Ошибка сети`;
        errorText.classList.remove("hidden");
    }

    spinner.remove();
    buttonsToHide.forEach(button => button.classList.remove("hidden"));
    if (onCleanup) onCleanup();

    let onError = (errorMessage) => {
        errorText.textContent = errorMessage;
        errorText.classList.remove("hidden");
    };

    if (response.ok) {
        let responseJson;
        try {
            responseJson = await response.json();
        } catch (error) {
            onError(error);
            console.log(`${errorPrefix}: ошибка json: ` + error);
        }
        if (responseJson.success) {
            if (onSuccess) onSuccess(responseJson);
            console.log(`${errorPrefix}: успешно`);
        } else {
            onError(responseJson.message);
            console.log(`${errorPrefix}: ошибка: ` + responseJson.message);
        }
    } else {
        onError(response.statusText);
        console.log(e`${errorPrefix}: ошибка http: ` + response.statusText);
    }
}

function createEditableField(config) {
    const {
        tableRowId,
        fetchUrl,
        errorPrefix,
        inputSelectors,
        displaySelectors,
        validate,
        prepareRequest,
        onEnable,
        onDisable
    } = config;

    const tableRow = document.getElementById(tableRowId);
    const errorText = tableRow.querySelector(".profileErrorText");
    const changeButton = tableRow.querySelector(".profileEditButton");
    const saveButton = tableRow.querySelector(".profileSaveButton");
    const cancelButton = tableRow.querySelector(".profileCancelButton");
    const editButtonsContainer = tableRow.querySelector(".profileEditButtonsContainer");

    const inputElements = inputSelectors.map(selector => tableRow.querySelector(selector));
    const displayElements = displaySelectors.map(selector => tableRow.querySelector(selector));

    function enableEditing() {
        displayElements.forEach(el => el.classList.add("hidden"));
        inputElements.forEach(el => el.classList.remove("hidden"));
        changeButton.classList.add("hidden");
        saveButton.classList.remove("hidden");
        cancelButton.classList.remove("hidden");
        inputElements[0].focus();

        if (onEnable) onEnable(inputElements, displayElements);
    }

    function disableEditing(confirm) {
        displayElements.forEach(el => el.classList.remove("hidden"));
        inputElements.forEach(el => el.classList.add("hidden"));
        changeButton.classList.remove("hidden");
        saveButton.classList.add("hidden");
        cancelButton.classList.add("hidden");
        errorText.classList.add("hidden");

        if (onDisable) onDisable(confirm, inputElements, displayElements);
    }

    changeButton.addEventListener("click", enableEditing);
    cancelButton.addEventListener("click", () => disableEditing(false));

    saveButton.addEventListener("click", async () => {
        const inputValues = inputElements.map(input => input.value);

        if (validate) {
            const errorMessage = validate(...inputValues);
            if (errorMessage) {
                errorText.textContent = errorMessage;
                errorText.classList.remove("hidden");
                return;
            }
        }

        const request = prepareRequest(...inputValues);

        await performRequest({
            editButtonsContainer,
            buttonsToHide: [saveButton, cancelButton],
            errorText,
            fetchUrl,
            method: "POST",
            body: request.body,
            headers: request.headers,
            errorPrefix,
            onSuccess: () => disableEditing(true),
        });
    });
}

function addImageUploadListeners(tableRowId, fetchUrl, errorPrefix) {
    const tableRow = document.getElementById(tableRowId);
    const displayImage = tableRow.querySelector(".profileDisplayImage");
    const editButtonsContainer = tableRow.querySelector(".profileEditButtonsContainer");
    const changeButton = tableRow.querySelector(".profileEditButton");
    const imageHiddenInput = tableRow.querySelector(".profileHiddenFileInput");
    const errorText = tableRow.querySelector(".profileErrorText");

    changeButton.addEventListener("click", () => imageHiddenInput.click());

    imageHiddenInput.addEventListener("change", async () => {
        const imageFile = imageHiddenInput.files[0];
        if (!imageFile) return;

        const formData = new FormData();
        formData.append("image", imageFile);

        await performRequest({
            editButtonsContainer,
            buttonsToHide: [changeButton],
            errorText,
            fetchUrl,
            method: "POST",
            body: formData,
            errorPrefix,
            onSuccess: (responseJson) => {
                displayImage.src = responseJson.image_url;
                getUserAvatarElement().src = responseJson.image_url;
            },
            onCleanup: () => imageHiddenInput.value = ""
        });
    });
}

createEditableField({
    tableRowId: "emailRow",
    fetchUrl: "/change_email",
    errorPrefix: "Изменение email",
    inputSelectors: [".profileTextInput"],
    displaySelectors: [".profileDisplayText"],
    prepareRequest: (email) => ({
        body: email,
        headers: { "Content-Type": "text/plain" }
    }),
    onEnable: (inputs, displays) => inputs[0].value = displays[0].textContent,
    onDisable: (confirm, inputs, displays) => {
        if (confirm) displays[0].textContent = inputs[0].value;
    }
});

createEditableField({
    tableRowId: "passwordRow",
    fetchUrl: "/change_password",
    errorPrefix: "Изменение пароля",
    inputSelectors: ["input[name=old_password]", "input[name=new_password]"],
    displaySelectors: [".profileDisplayText"],
    validate: (oldPass, newPass) => {
        const error = validatePassword(newPass);
        return error || (oldPass === newPass ? "Пароли совпадают" : null);
    },
    prepareRequest: (oldPass, newPass) => ({
        body: JSON.stringify({ old_password: oldPass, new_password: newPass }),
        headers: { "Content-Type": "application/json" }
    }),
    onDisable: (_, inputs) => inputs.forEach(input => input.value = "")
});

addImageUploadListeners("avatarRow", "/change_avatar", "Изменение аватара");
