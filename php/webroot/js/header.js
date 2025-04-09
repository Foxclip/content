let userMenuButton = document.getElementById("userMenuButton");
let userMenuPopup = document.getElementById("userMenuPopup");
userMenuButton.addEventListener("click", () => userMenuPopup.style.display = userMenuPopup.style.display === "block" ? "none" : "block");
