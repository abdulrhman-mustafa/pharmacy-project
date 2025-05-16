// Function to toggle password visibility
function togglePasswordVisibility(passwordId, eyeId) {
    const eyeIcon = document.getElementById(eyeId);
    const passwordField = document.getElementById(passwordId);

    if (eyeIcon && passwordField) {
        eyeIcon.addEventListener("click", () => {
            if (passwordField.type === "password" && passwordField.value) {
                passwordField.type = "text";
                eyeIcon.classList.remove("fa-eye");
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("fa-eye-slash");
                eyeIcon.classList.add("fa-eye");
            }
        });
    }
}

// Initialize for both password fields
togglePasswordVisibility("password", "eye");
togglePasswordVisibility("confirm_password", "eye-confirm");


document.querySelector("form").addEventListener("submit", function(e) {
        e.preventDefault();
        fetch("../api/useraccount.php", {
            method: "POST",
            body: new URLSearchParams(new FormData(this)),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                sessionStorage.setItem("user", JSON.stringify(data.user));
                window.location.href = "../pages/index.php";
            } else {
                alert("Login failed: " + data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    });