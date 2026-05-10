// File: script.js

// Tunggu sampai DOM siap
document.addEventListener("DOMContentLoaded", function () {
    // Ambil tombol
    const btnProduk = document.querySelector(".actions .btn:nth-child(1)");
    const btnPetani = document.querySelector(".actions .btn:nth-child(2)");

    // Fungsi untuk membuat popup sederhana
    function showPopup(title, fields) {
        // Buat elemen overlay
        const overlay = document.createElement("div");
        overlay.style.position = "fixed";
        overlay.style.top = "0";
        overlay.style.left = "0";
        overlay.style.width = "100%";
        overlay.style.height = "100%";
        overlay.style.backgroundColor = "rgba(0,0,0,0.5)";
        overlay.style.display = "flex";
        overlay.style.alignItems = "center";
        overlay.style.justifyContent = "center";
        overlay.style.zIndex = "999";

        // Buat kotak popup
        const popup = document.createElement("div");
        popup.style.backgroundColor = "#fff";
        popup.style.padding = "20px";
        popup.style.borderRadius = "8px";
        popup.style.width = "300px";
        popup.style.boxShadow = "0 2px 10px rgba(0,0,0,0.3)";

        // Judul
        const h2 = document.createElement("h2");
        h2.textContent = title;
        h2.style.marginBottom = "15px";
        popup.appendChild(h2);

        // Form input
        fields.forEach(field => {
            const label = document.createElement("label");
            label.textContent = field + ":";
            label.style.display = "block";
            label.style.marginTop = "10px";

            const input = document.createElement("input");
            input.type = "text";
            input.style.width = "100%";
            input.style.padding = "8px";
            input.style.marginTop = "5px";

            popup.appendChild(label);
            popup.appendChild(input);
        });

        // Tombol simpan
        const saveBtn = document.createElement("button");
        saveBtn.textContent = "Simpan";
        saveBtn.style.marginTop = "15px";
        saveBtn.style.padding = "10px 15px";
        saveBtn.style.backgroundColor = "#27ae60";
        saveBtn.style.color = "#fff";
        saveBtn.style.border = "none";
        saveBtn.style.borderRadius = "5px";
        saveBtn.style.cursor = "pointer";

        saveBtn.addEventListener("click", function () {
            alert(title + " berhasil ditambahkan!");
            document.body.removeChild(overlay);
        });

        popup.appendChild(saveBtn);

        // Tombol tutup
        const closeBtn = document.createElement("span");
        closeBtn.textContent = "×";
        closeBtn.style.position = "absolute";
        closeBtn.style.top = "10px";
        closeBtn.style.right = "20px";
        closeBtn.style.cursor = "pointer";
        closeBtn.style.fontSize = "20px";
        closeBtn.style.color = "#333";

        closeBtn.addEventListener("click", function () {
            document.body.removeChild(overlay);
        });

        popup.appendChild(closeBtn);

        overlay.appendChild(popup);
        document.body.appendChild(overlay);
    }

    // Event klik tombol
    btnProduk.addEventListener("click", function () {
        showPopup("Tambah Produk Baru", ["Nama Produk", "Kategori", "Harga", "Stok"]);
    });

    btnPetani.addEventListener("click", function () {
        showPopup("Daftarkan Petani", ["Nama Petani", "Alamat", "Nomor HP"]);
    });
});
