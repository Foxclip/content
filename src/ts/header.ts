import { postDatetimesToLocalTime, setupLikeButtons } from "./post";

declare global {
    interface Window {
        __headerInitialized?: boolean;
    }
}

let userMenuButton: HTMLElement | null = null;
let userMenuPopup: HTMLElement | null = null;

export function getUserAvatarElement() {
    if (!userMenuButton) userMenuButton = document.getElementById("userMenuButton") as HTMLElement;
    if (!userMenuPopup) userMenuPopup = document.getElementById("userMenuPopup") as HTMLElement;
    let avatar = userMenuButton.querySelector(".avatarImage") as HTMLImageElement;
    return avatar;
}

function setupUserMenu() {
    userMenuButton = document.getElementById("userMenuButton");
    userMenuPopup = document.getElementById("userMenuPopup");

    if (userMenuButton && userMenuPopup) {   
        userMenuButton.addEventListener("click", () => {
            userMenuPopup!.classList.toggle("hidden");
        });
        document.addEventListener("click", (event) => {
            if (!userMenuButton!.contains(event.target as Node) && !userMenuPopup!.contains(event.target as Node)) {
                userMenuPopup!.classList.add("hidden");
            }
        });
    }

    let logoutButton = document.getElementById("logoutButton");
    if (logoutButton) {
        let logoutForm = logoutButton.querySelector("form");
        if (!logoutForm) throw new Error("Logout form not found");
        logoutButton.addEventListener("click", () => logoutForm.submit());
    }
}

if (typeof window !== "undefined" && !window.__headerInitialized) {
    window.__headerInitialized = true;

    setupUserMenu();
    postDatetimesToLocalTime();
    setupLikeButtons();
}
