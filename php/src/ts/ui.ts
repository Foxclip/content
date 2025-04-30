export function createSpinner() {
    let tempDiv = document.createElement("div");
    const radius = 30;
    const factor = 2/3;
    tempDiv.innerHTML = `
        <svg class="spinner" viewBox="0 0 100 100">
            <circle 
                cx="50" 
                cy="50" 
                r="${ radius }"
                fill="none" 
                stroke="red"
                stroke-width="5" 
                stroke-dasharray="${ 2 * Math.PI * radius * factor } ${ 2 * Math.PI * radius * (1 - factor) }"
                stroke-linecap="round"
            ></circle>
        </svg>
    `;
    let spinner = tempDiv.firstElementChild;
    return spinner;
}
