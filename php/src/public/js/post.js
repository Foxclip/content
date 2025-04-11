import { Utils } from './utils.js';

export function postDatetimesToLocalTime() {
    let postDatetimes = document.querySelectorAll(".postDatetime");
    postDatetimes.forEach((postDatetime) => {
        postDatetime.textContent = Utils.formatUTCDateToLocal(postDatetime.textContent);
    });
}
