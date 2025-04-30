import { Utils } from './utils';

export function postDatetimesToLocalTime() {
    let postDatetimes = document.querySelectorAll(".postDatetime");
    postDatetimes.forEach((postDatetime) => {
        postDatetime.textContent = Utils.formatUTCDateToLocal(postDatetime.textContent);
    });
}
