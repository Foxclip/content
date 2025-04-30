import { Utils } from './utils';

export function postDatetimesToLocalTime() {
    let postDatetimes = document.querySelectorAll(".postDatetime");
    postDatetimes.forEach((postDatetime) => {
        if (postDatetime.textContent === null || postDatetime.textContent === undefined) return;
        postDatetime.textContent = Utils.formatUTCDateToLocal(postDatetime.textContent);
    });
}
