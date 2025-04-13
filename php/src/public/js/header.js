let userMenuButton = document.getElementById("userMenuButton");
let userMenuPopup = document.getElementById("userMenuPopup");

export function getUserAvatarElement() {
    let avatar = userMenuButton.querySelector(".avatarImage");
    return avatar;
}

if (userMenuButton !== null && userMenuPopup !== null) {   
    userMenuButton.addEventListener("click", () => userMenuPopup.style.display = userMenuPopup.style.display === "flex" ? "none" : "flex");
    document.addEventListener("click", (event) => {
        if (!userMenuButton.contains(event.target) && !userMenuPopup.contains(event.target)) {
            userMenuPopup.style.display = "none";
        }
    });
}
