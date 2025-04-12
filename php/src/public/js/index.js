import { postDatetimesToLocalTime } from './post.js';
import { Utils } from './utils.js';

let tabButtonList = document.querySelector(".tabButtonList");
let tabButtons = tabButtonList.querySelectorAll(".tabButton");
let tabBodyList = document.querySelector(".tabBodyList");
let tabBodies = tabBodyList.querySelectorAll(".tabBody");
for (let i = 0; i < tabButtons.length; i++) {
    let tabButton = tabButtons[i];
    tabButton.addEventListener("click", () => {
        tabButtonList.querySelector(".active").classList.remove("active");
        tabButton.classList.add("active");
        let tabBody = tabBodyList.children[i];
        tabBodies.forEach((tabBody) => tabBody.classList.remove("active"));
        tabBody.classList.add("active");
    });
}

let likeButtons = document.querySelectorAll(".postLikes");
likeButtons.forEach((likeButton) => likeButton.addEventListener("click", async (event) => {
    likeButton.classList.toggle("active");
    const response = await fetch("/like", {
        method: "POST",
        body: JSON.stringify({
            postId: likeButton.dataset.postId,
            action: likeButton.classList.contains("active") ? "like" : "unlike"
        })
    });
    if (response.ok) {
        const responseObj = await response.json();
        likeButton.querySelector(".postLikesCount").textContent = responseObj.like_count;
    } else {
        console.log("Кнопка лайка: ошибка: " + response.statusText);
    }
}));

postDatetimesToLocalTime();
