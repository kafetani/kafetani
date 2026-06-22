// =========================
// TOGGLE PASSWORD
// =========================
function togglePassword() {
    const password_input = document.getElementById("password");
    if (password_input.type == "password") {
        password_input.type = "text";
    } else {
        password_input.type = "password";
    }
}

// =========================
// TOGGLE KONFIRMASI PASSWORD
// =========================
function toggleKonfirmasiPassword() {
    const konfirmasi_password_input = document.getElementById("konfirmasi_password");
    if (konfirmasi_password_input.type == "password") {
        konfirmasi_password_input.type = "text";
    } else {
        konfirmasi_password_input.type = "password";
    }
}

// Catatan: logik keranjang (cart) dipindahkan ke assets/js/app.js
