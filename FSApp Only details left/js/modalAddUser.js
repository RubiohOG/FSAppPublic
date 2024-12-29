document.addEventListener("DOMContentLoaded", function() {
    const message = document.getElementById("userMessage");
    if (message) {
        setTimeout(() => {
            message.style.transition = "opacity 1s";
            message.style.opacity = 0;
            setTimeout(() => message.remove(), 1000);
        }, 3000);
    }
});