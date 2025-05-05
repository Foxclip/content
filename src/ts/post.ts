import { Utils } from './utils';

export function postDatetimesToLocalTime() {
    let postDatetimes = document.querySelectorAll(".postDatetime");
    postDatetimes.forEach((postDatetime) => {
        if (postDatetime.textContent === null || postDatetime.textContent === undefined) return;
        postDatetime.textContent = Utils.formatUTCDateToLocal(postDatetime.textContent);
    });
}

export function setupLikeButtons() {
    let csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    let likeButtons = document.querySelectorAll<HTMLElement>(".postLikes");

    likeButtons.forEach((likeButton) => likeButton.addEventListener("click", async (event) => {

        likeButton.classList.toggle("active");

        try {
            const response = await fetch("/like", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    ...(csrfToken && { "X-CSRF-Token": csrfToken })
                },
                body: JSON.stringify({
                    postId: likeButton.dataset.postId,
                    action: likeButton.classList.contains("active") ? "like" : "unlike",
                })
            });

            await Utils.handleResponse(
                response,
                (json: any) => likeButton.querySelector(".postLikesCount")!.textContent = json.like_count,
                null,
                "Кнопка лайка"
            );
        } catch (error) {
            console.log(`Кнопка лайка: ${error}`);
            likeButton.classList.toggle("active");
        }

    }));
}
