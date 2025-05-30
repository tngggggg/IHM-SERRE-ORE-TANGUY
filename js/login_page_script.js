document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("login-form");
    const messageDiv = document.getElementById("message");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("../php/login.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                messageDiv.style.color = "green";
                messageDiv.textContent = data.message;
                setTimeout(() => {
                    window.location.href = "http://192.168.137.15/index.php";
                }, 1000);
            } else {
                messageDiv.style.color = "red";
                messageDiv.textContent = data.message;
            }
        })
        .catch(error => {
            messageDiv.textContent = "Erreur de connexion au serveur.";
            console.error(error);
        });
    });
});
