let userMenuButton = document.getElementById("userMenuButton") as HTMLElement;
let userMenuPopup = document.getElementById("userMenuPopup") as HTMLElement;

export function getUserAvatarElement() {
    let avatar = userMenuButton.querySelector(".avatarImage") as HTMLImageElement;
    return avatar;
}

if (userMenuButton && userMenuPopup) {   
    userMenuButton.addEventListener("click", () => {
        userMenuPopup.classList.toggle("hidden");
    });
    document.addEventListener("click", (event) => {
        if (!userMenuButton.contains(event.target as Node) && !userMenuPopup.contains(event.target as Node)) {
            userMenuPopup.classList.add("hidden");
        }
    });
}

let logoutButton = document.getElementById("logoutButton");
if (logoutButton) {
    let logoutForm = logoutButton.querySelector("form");
    if (!logoutForm) throw new Error("Logout form not found");
    logoutButton.addEventListener("click", () => logoutForm.submit());
}
