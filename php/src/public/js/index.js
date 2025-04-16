import { postDatetimesToLocalTime } from './post.js';
import { Utils } from './utils.js';

let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let likeButtons = document.querySelectorAll(".postLikes");

likeButtons.forEach((likeButton) => likeButton.addEventListener("click", async (event) => {

    likeButton.classList.toggle("active");

    let response;
    try {
        response = await fetch("/like", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken
            },
            body: JSON.stringify({
                postId: likeButton.dataset.postId,
                action: likeButton.classList.contains("active") ? "like" : "unlike",
            })
        });
    } catch (error) {
        console.log(`Кнопка лайка: ${error}`);
    }

    await Utils.handleResponse(
        response,
        (json) => likeButton.querySelector(".postLikesCount").textContent = json.like_count,
        null,
        "Кнопка лайка"
    );

}));

postDatetimesToLocalTime();
