import { postDatetimesToLocalTime } from './post.js';
import { Utils } from './utils.js';

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

    await Utils.handleResponse(
        response,
        (json) => { likeButton.querySelector(".postLikesCount").textContent = json.like_count; },
        null,
        "Кнопка лайка"
    );

}));

postDatetimesToLocalTime();
