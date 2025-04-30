export function validateEmail(email) {
    if (!email.match(/^\S+@\S+$/)) {
        return "Некорректный email";
    }
    return '';
}

export function validatePassword(password) {
    if (password.length < 6 || password.length > 20) {
        return "Пароль должен содержать от 6 до 20 символов";
    }
    return '';
}
