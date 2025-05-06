import { createSpinner } from "./ui";
import { getUserAvatarElement } from "./header";
import { Utils } from "./utils";
import { validateEmail, validatePassword } from "./validation";

let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

class AbstractField {
    private readonly fetchUrl: string;
    private readonly errorPrefix: string;
    private readonly editButtonsContainer: HTMLDivElement;
    protected changeButton: HTMLElement;
    protected errorText: HTMLElement;

    constructor(tableRowId: string, fetchUrl: string, errorPrefix: string) {
        this.fetchUrl = fetchUrl;
        this.errorPrefix = errorPrefix;
        const tableRow = this.getElementById(tableRowId);
        this.editButtonsContainer = this.querySelector(tableRow, ".profileEditButtonsContainer");
        this.changeButton = this.querySelector(tableRow, ".profileEditButton");
        this.errorText = this.querySelector(tableRow, ".profileErrorText");
    }

    getElementById(id: string) {
        const element = document.getElementById(id);
        if (!element) throw new Error(`Элемент ${id} не найден`);
        return element;
    }

    querySelector<T extends HTMLElement>(parent: HTMLElement, selector: string) {
        const element = parent.querySelector<T>(selector);
        if (!element) throw new Error(`Элемент ${selector} не найден`);
        return element;
    }

    async performRequest({
        buttonsToHide = [],
        method,
        body,
        headers = {},
        onSuccess,
        onCleanup
    }: {
        buttonsToHide?: HTMLElement[],
        method: "GET" | "POST",
        body?: BodyInit,
        headers?: Record<string, string>,
        onSuccess: (response: any) => void,
        onCleanup?: () => void
    }) {
        const spinner = createSpinner();
        this.editButtonsContainer.appendChild(spinner);
        buttonsToHide.forEach(button => button.classList.add("hidden"));
        this.errorText.classList.add("hidden");
    
        if (csrfToken) headers["X-CSRF-Token"] = csrfToken;
        try {
            const response = await fetch(this.fetchUrl, { method, body, headers });
            let onError = (errorMessage: string) => {
                this.errorText.textContent = errorMessage;
                this.errorText.classList.remove("hidden");
            };
            Utils.handleResponse(response, onSuccess, onError, this.errorPrefix);
        } catch (error) {
            this.errorText.textContent = `Ошибка сети: ${error}`;
            this.errorText.classList.remove("hidden");
            return;
        } finally {
            spinner.remove();
            buttonsToHide.forEach(button => button.classList.remove("hidden"));
            if (onCleanup) onCleanup();
        }
    }
}

type onEnableType = (inputs: HTMLInputElement[], display: HTMLElement) => void;
type onDisableType = (confirm: boolean, inputs: HTMLInputElement[], display: HTMLElement) => void;

class TextField extends AbstractField {
    private readonly saveButton: HTMLElement;
    private readonly cancelButton: HTMLElement;
    private readonly inputElements: HTMLInputElement[];
    private readonly displayElement: HTMLElement;
    private readonly onEnable?: onEnableType;
    private readonly onDisable?: onDisableType;

    constructor({
        tableRowId,
        fetchUrl,
        errorPrefix,
        inputSelectors,
        pass,
        validate,
        prepareRequest,
        onEnable,
        onDisable
    }: {
        tableRowId: string,
        fetchUrl: string,
        errorPrefix: string,
        inputSelectors: string[],
        pass?: (displayValue: string, ...inputValues: string[]) => any,
        validate?: (...inputValues: string[]) => string | null,
        prepareRequest: (...inputValues: string[]) => { body: string, headers: Record<string, string> },
        onEnable?: onEnableType,
        onDisable?: onDisableType
    }) {
        super(tableRowId, fetchUrl, errorPrefix);

        const tableRow = this.getElementById(tableRowId);
        this.changeButton = this.querySelector(tableRow, ".profileEditButton");
        this.saveButton = this.querySelector(tableRow, ".profileSaveButton");
        this.cancelButton = this.querySelector(tableRow, ".profileCancelButton");

        this.onEnable = onEnable;
        this.onDisable = onDisable;

        this.inputElements = inputSelectors.map(selector => this.querySelector(tableRow, selector));
        this.displayElement = this.querySelector(tableRow, ".profileDisplayText");

        this.changeButton.addEventListener("click", () => this.enableEditing());
        this.cancelButton.addEventListener("click", () => this.disableEditing(false));

        this.saveButton.addEventListener("click", async () => {
            const inputValues = this.inputElements.map(input => input.value);

            if (pass) {
                if (pass(this.displayElement.textContent!, ...inputValues)) {
                    this.disableEditing(true);
                    return;
                }
            }
    
            if (validate) {
                const errorMessage = validate(...inputValues);
                if (errorMessage) {
                    this.errorText.textContent = errorMessage;
                    this.errorText.classList.remove("hidden");
                    return;
                }
            }
    
            const request = prepareRequest(...inputValues);
    
            await this.performRequest({
                buttonsToHide: [this.saveButton, this.cancelButton],
                method: "POST",
                body: request.body,
                headers: request.headers,
                onSuccess: () => this.disableEditing(true),
            });
        });
    }

    enableEditing() {
        this.displayElement.classList.add("hidden");
        this.inputElements.forEach(el => el.classList.remove("hidden"));
        this.changeButton.classList.add("hidden");
        this.saveButton.classList.remove("hidden");
        this.cancelButton.classList.remove("hidden");
        this.inputElements[0].focus();

        if (this.onEnable) this.onEnable(this.inputElements, this.displayElement);
    }

    disableEditing(confirm: boolean) {
        this.displayElement.classList.remove("hidden");
        this.inputElements.forEach(el => el.classList.add("hidden"));
        this.changeButton.classList.remove("hidden");
        this.saveButton.classList.add("hidden");
        this.cancelButton.classList.add("hidden");
        this.errorText.classList.add("hidden");

        if (this.onDisable) this.onDisable(confirm, this.inputElements, this.displayElement);
    }
}

class ImageUploadField extends AbstractField {
    private readonly displayImage: HTMLImageElement;
    private readonly imageHiddenInput: HTMLInputElement;

    constructor(tableRowId: string, fetchUrl: string, errorPrefix: string) {
        super(tableRowId, fetchUrl, errorPrefix);

        const tableRow = this.getElementById(tableRowId);
        this.displayImage = this.querySelector(tableRow, ".profileDisplayImage");
        this.imageHiddenInput = this.querySelector(tableRow, ".profileHiddenFileInput");

        this.changeButton.addEventListener("click", () => this.imageHiddenInput.click());

        this.imageHiddenInput.addEventListener("change", async () => {
            if (!this.imageHiddenInput.files) return;
            const imageFile = this.imageHiddenInput.files[0];
            if (!imageFile) return;

            const formData = new FormData();
            formData.append("image", imageFile);

            await this.performRequest({
                buttonsToHide: [this.changeButton],
                method: "POST",
                body: formData,
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
    pass: (emailOld, emailNew) => emailNew === emailOld,
    validate: (email) => {
        if (!email) return "Введите email";
        const error = validateEmail(email);
        return error;
    },
    prepareRequest: (email) => ({
        body: email,
        headers: { "Content-Type": "text/plain" }
    }),
    onEnable: (inputs, display) => inputs[0].value = display.textContent!,
    onDisable: (confirm, inputs, display) => {
        if (confirm) display.textContent = inputs[0].value;
    }
});

new TextField({
    tableRowId: "passwordRow",
    fetchUrl: "/change_password",
    errorPrefix: "Изменение пароля",
    inputSelectors: ["input[name=old_password]", "input[name=new_password]"],
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
