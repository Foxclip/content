import { createSpinner } from "./ui.js";

let userEmail = document.getElementById("userEmail");
let changeEmailForm = document.getElementById("changeEmailForm");
let emailInput = document.getElementById("emailInput");
let emailSaveCancelContainer = document.getElementById("emailSaveCancelContainer");
let changeEmailButton = document.getElementById("changeEmailButton");
let saveEmailButton = document.getElementById("saveEmailButton");
let saveEmailButtonCancel = document.getElementById("saveEmailButtonCancel");

changeEmailButton.addEventListener("click", () => {
    emailInput.value = userEmail.textContent;
    userEmail.classList.add("hidden");
    changeEmailForm.classList.remove("hidden");
    changeEmailButton.classList.add("hidden");
    saveEmailButton.classList.remove("hidden");
    saveEmailButtonCancel.classList.remove("hidden");
    emailInput.focus();
});
saveEmailButtonCancel.addEventListener("click", () => {
    userEmail.classList.remove("hidden");
    changeEmailForm.classList.add("hidden");
    changeEmailButton.classList.remove("hidden");
    saveEmailButton.classList.add("hidden");
    saveEmailButtonCancel.classList.add("hidden");
});

saveEmailButton.addEventListener("click", async () => {

    let spinner = createSpinner(5, "red");
    emailSaveCancelContainer.appendChild(spinner);
    saveEmailButton.classList.add("hidden");
    saveEmailButtonCancel.classList.add("hidden");

    const response = await fetch("/change_email", {
        method: "POST",
        body: JSON.stringify({
            email: emailInput.value
        })
    });
    
    spinner.remove();
    saveEmailButton.classList.remove("hidden");
    saveEmailButtonCancel.classList.remove("hidden");

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

let avatarImage = document.getElementById("avatarImage");
let changeAvatarButton = document.getElementById("changeAvatarButton");
let changeAvatarForm = document.getElementById("changeAvatarForm");
let avatarHiddenInput = document.getElementById("avatarHiddenInput");

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
