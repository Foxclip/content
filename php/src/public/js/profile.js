import { createSpinner } from "./ui.js";

let emailRow = document.getElementById("emailRow");
let userEmail = emailRow.querySelector(".profileDisplayText");
let emailInput = emailRow.querySelector(".profileTextInput");
let emailSaveCancelContainer = emailRow.querySelector(".saveCancelContainer");
let changeEmailButton = emailRow.querySelector(".profileEditButton");
let saveEmailButton = emailRow.querySelector(".profileSaveButton");
let cancelEmailButton = emailRow.querySelector(".profileCancelButton");

changeEmailButton.addEventListener("click", () => {
    emailInput.value = userEmail.textContent;
    userEmail.classList.add("hidden");
    emailInput.classList.remove("hidden");
    changeEmailButton.classList.add("hidden");
    saveEmailButton.classList.remove("hidden");
    cancelEmailButton.classList.remove("hidden");
    emailInput.focus();
});
cancelEmailButton.addEventListener("click", () => {
    userEmail.classList.remove("hidden");
    emailInput.classList.add("hidden");
    changeEmailButton.classList.remove("hidden");
    saveEmailButton.classList.add("hidden");
    cancelEmailButton.classList.add("hidden");
});

saveEmailButton.addEventListener("click", async () => {

    let spinner = createSpinner(5, "red");
    emailSaveCancelContainer.appendChild(spinner);
    saveEmailButton.classList.add("hidden");
    cancelEmailButton.classList.add("hidden");

    const response = await fetch("/change_email", {
        method: "POST",
        body: JSON.stringify({
            email: emailInput.value
        })
    });

    spinner.remove();
    saveEmailButton.classList.remove("hidden");
    cancelEmailButton.classList.remove("hidden");

    if (response.ok) {
        let responseObj;
        try {
            responseObj = await response.json();
        } catch (error) {
            console.log("Изменение email: ошибка: " + error);
        }
        if (responseObj.success) {
            userEmail.textContent = emailInput.value;
            console.log("Изменение email: успешно");
        } else {
            console.log("Изменение email: ошибка: " + responseObj.error);
        }
    } else {
        console.log("Изменение email: ошибка: " + response.statusText);
    }

});

let avatarRow = document.getElementById("avatarRow");
let avatarImage = avatarRow.querySelector(".profileDisplayImage");
let changeAvatarButton = avatarRow.querySelector(".profileEditButton");
let avatarHiddenInput = avatarRow.querySelector(".profileHiddenFileInput");

changeAvatarButton.addEventListener("click", () => avatarHiddenInput.click());
avatarHiddenInput.addEventListener("change", async () => {
    const avatarFile = avatarHiddenInput.files[0];
    if (!avatarFile) {
        return;
    }
    const formData = new FormData();
    formData.append("avatar", avatarFile);
    const response = await fetch("/change_avatar", {
        method: "POST",
        body: formData
    });
    if (response.ok) {
        let responseObj;
        try {
            responseObj = await response.json();
        } catch (error) {
            console.log("Изменение аватара: ошибка: " + error);
        }
        if (responseObj.success) {
            avatarImage.src = responseObj.avatar_url;
            console.log("Изменение аватара: успешно");
        } else {
            console.log("Изменение аватара: ошибка: " + responseObj.message);
        }
    } else {
        console.log("Изменение аватара: ошибка: " + response.statusText);
    }
});
