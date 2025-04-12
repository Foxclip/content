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
