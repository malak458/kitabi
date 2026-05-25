function togglePassword() {
    const input = document.querySelector('input[type="password"]');

    if (input.type === "password") {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
