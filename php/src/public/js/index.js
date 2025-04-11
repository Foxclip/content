import { Utils } from './utils.js';

let likeButtons = document.querySelectorAll(".postLikes");
likeButtons.forEach((likeButton) => likeButton.addEventListener("click", async () => {
    likeButton.classList.toggle("active");
    const response = await fetch("/like", {
        method: "POST",
        body: JSON.stringify({
            postId: likeButton.dataset.postId,
            action: likeButton.classList.contains("active") ? "like" : "unlike"
        })
    });
    const responseText = await response.text();
}));
