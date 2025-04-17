let userMenuButton = document.getElementById("userMenuButton");
let userMenuPopup = document.getElementById("userMenuPopup");

export function getUserAvatarElement() {
    let avatar = userMenuButton.querySelector(".avatarImage");
    return avatar;
}

if (userMenuButton && userMenuPopup) {   
    userMenuButton.addEventListener("click", () => userMenuPopup.style.display = userMenuPopup.style.display === "flex" ? "none" : "flex");
    document.addEventListener("click", (event) => {
        if (!userMenuButton.contains(event.target) && !userMenuPopup.contains(event.target)) {
            userMenuPopup.style.display = "none";
        }
    });
}

let logoutButton = document.getElementById("logoutButton");
if (logoutButton) {
    let logoutForm = logoutButton.querySelector("form");
    logoutButton.addEventListener("click", () => logoutForm.submit());
}
