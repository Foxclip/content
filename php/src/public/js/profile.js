import { createSpinner } from "./ui.js";
import { getUserAvatarElement } from "./header.js";
import { Utils } from "./utils.js";
import { validatePassword } from "./validation.js";

async function performRequest({
    buttonsToHide = [],
    errorText,
    fetchUrl,
    method,
    body,
    headers = {},
    errorPrefix,
    spinnerParent,
    onSuccess,
    onCleanup
}) {
    const spinner = createSpinner();
    spinnerParent.appendChild(spinner);
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

class AbstractField {
    constructor(tableRowId, fetchUrl, errorPrefix) {
        this.fetchUrl = fetchUrl;
        this.errorPrefix = errorPrefix;
        const tableRow = document.getElementById(tableRowId);
        this.editButtonsContainer = tableRow.querySelector(".profileEditButtonsContainer");
        this.changeButton = tableRow.querySelector(".profileEditButton");
        this.errorText = tableRow.querySelector(".profileErrorText");
    }
}

class TextField extends AbstractField {
    constructor({
        tableRowId,
        fetchUrl,
        errorPrefix,
        inputSelectors,
        displaySelectors,
        validate,
        prepareRequest,
        onEnable,
        onDisable
    }) {
        super(tableRowId, fetchUrl, errorPrefix);
        
        const tableRow = document.getElementById(tableRowId);
        this.changeButton = tableRow.querySelector(".profileEditButton");
        this.saveButton = tableRow.querySelector(".profileSaveButton");
        this.cancelButton = tableRow.querySelector(".profileCancelButton");

        this.onEnable = onEnable;
        this.onDisable = onDisable;

        this.inputElements = inputSelectors.map(selector => tableRow.querySelector(selector));
        this.displayElements = displaySelectors.map(selector => tableRow.querySelector(selector));

        this.changeButton.addEventListener("click", this.enableEditing.bind(this));
        this.cancelButton.addEventListener("click", () => this.disableEditing.bind(this)(false));

        this.saveButton.addEventListener("click", async () => {
            const inputValues = this.inputElements.map(input => input.value);
    
            if (validate) {
                const errorMessage = validate(...inputValues);
                if (errorMessage) {
                    this.errorText.textContent = errorMessage;
                    this.errorText.classList.remove("hidden");
                    return;
                }
            }
    
            const request = prepareRequest(...inputValues);
    
            await performRequest({
                buttonsToHide: [this.saveButton, this.cancelButton],
                errorText: this.errorText,
                fetchUrl,
                method: "POST",
                body: request.body,
                headers: request.headers,
                errorPrefix,
                spinnerParent: this.editButtonsContainer,
                onSuccess: () => this.disableEditing(true),
            });
        });
    }

    enableEditing() {
        this.displayElements.forEach(el => el.classList.add("hidden"));
        this.inputElements.forEach(el => el.classList.remove("hidden"));
        this.changeButton.classList.add("hidden");
        this.saveButton.classList.remove("hidden");
        this.cancelButton.classList.remove("hidden");
        this.inputElements[0].focus();

        if (this.onEnable) this.onEnable(this.inputElements, this.displayElements);
    }

    disableEditing(confirm) {
        this.displayElements.forEach(el => el.classList.remove("hidden"));
        this.inputElements.forEach(el => el.classList.add("hidden"));
        this.changeButton.classList.remove("hidden");
        this.saveButton.classList.add("hidden");
        this.cancelButton.classList.add("hidden");
        this.errorText.classList.add("hidden");

        if (this.onDisable) this.onDisable(confirm, this.inputElements, this.displayElements);
    }
}

class ImageUploadField extends AbstractField {
    constructor(tableRowId, fetchUrl, errorPrefix) {
        super(tableRowId, fetchUrl, errorPrefix);

        const tableRow = document.getElementById(tableRowId);
        this.displayImage = tableRow.querySelector(".profileDisplayImage");
        this.imageHiddenInput = tableRow.querySelector(".profileHiddenFileInput");

        this.changeButton.addEventListener("click", () => this.imageHiddenInput.click());

        this.imageHiddenInput.addEventListener("change", async () => {
            const imageFile = this.imageHiddenInput.files[0];
            if (!imageFile) return;

            const formData = new FormData();
            formData.append("image", imageFile);

            await performRequest({
                buttonsToHide: [this.changeButton],
                errorText: this.errorText,
                fetchUrl,
                method: "POST",
                body: formData,
                errorPrefix,
                spinnerParent: this.editButtonsContainer,
                onSuccess: (responseJson) => {
                    this.displayImage.src = responseJson.image_url;
                    getUserAvatarElement().src = responseJson.image_url;
                },
                onCleanup: () => this.imageHiddenInput.value = ""
            });
        });
    }
}

new TextField({
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

new TextField({
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

new ImageUploadField("avatarRow", "/change_avatar", "Изменение аватара");
