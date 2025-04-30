export function validateEmail(email: string) {
    if (!email.match(/^\S+@\S+$/)) {
        return "Некорректный email";
    }
    return '';
}

export function validatePassword(password: string) {
    if (password.length < 6 || password.length > 20) {
        return "Пароль должен содержать от 6 до 20 символов";
    }
    return '';
}
