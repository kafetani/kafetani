// File: produk.js

document.addEventListener("DOMContentLoaded", function () {
  const addBtn = document.querySelector(".add-product");
  const actionLinks = document.querySelectorAll(".product-table a");

  // Fungsi popup form tambah produk
  function showAddProductForm() {
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

    const formBox = document.createElement("div");
    formBox.style.background = "#fff";
    formBox.style.padding = "20px";
    formBox.style.borderRadius = "8px";
    formBox.style.width = "350px";
    formBox.style.boxShadow = "0 2px 10px rgba(0,0,0,0.3)";

    const title = document.createElement("h2");
    title.textContent = "Tambah Produk Baru";
    formBox.appendChild(title);

    const fields = ["Nama Produk", "Kategori", "Tipe", "Harga", "Stok"];
    fields.forEach(f => {
      const label = document.createElement("label");
      label.textContent = f;
      label.style.display = "block";
      label.style.marginTop = "10px";

      const input = document.createElement("input");
      input.type = "text";
      input.style.width = "100%";
      input.style.padding = "8px";
      input.style.marginTop = "5px";

      formBox.appendChild(label);
      formBox.appendChild(input);
    });

    const saveBtn = document.createElement("button");
    saveBtn.textContent = "Simpan";
    saveBtn.style.marginTop = "15px";
    saveBtn.style.padding = "10px 15px";
    saveBtn.style.backgroundColor = "#2e7d32";
    saveBtn.style.color = "#fff";
    saveBtn.style.border = "none";
    saveBtn.style.borderRadius = "5px";
    saveBtn.style.cursor = "pointer";

    saveBtn.addEventListener("click", function () {
      alert("Produk baru berhasil ditambahkan!");
      document.body.removeChild(overlay);
    });

    formBox.appendChild(saveBtn);

    // Tombol Cancel
    const cancelBtn = document.createElement("button");
    cancelBtn.textContent = "Batal";
    cancelBtn.style.marginTop = "15px";
    cancelBtn.style.marginLeft = "10px";
    cancelBtn.style.padding = "10px 15px";
    cancelBtn.style.backgroundColor = "#fff";
    cancelBtn.style.color = "#c0392b";
    cancelBtn.style.border = "1px solid #c0392b";
    cancelBtn.style.borderRadius = "5px";
    cancelBtn.style.cursor = "pointer";

    cancelBtn.addEventListener("click", function () {
      document.body.removeChild(overlay);
    });

    formBox.appendChild(cancelBtn);

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

    formBox.appendChild(closeBtn);

    overlay.appendChild(formBox);
    document.body.appendChild(overlay);
  }

  // Event klik tombol Produk Baru
  addBtn.addEventListener("click", showAddProductForm);

  // Event klik Edit/Hapus
  actionLinks.forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      if (this.textContent === "Edit") {
        alert("Fitur Edit akan ditambahkan di sini.");
      } else if (this.textContent === "Hapus") {
        const confirmDelete = confirm("Apakah Anda yakin ingin menghapus produk ini?");
        if (confirmDelete) {
          alert("Produk berhasil dihapus!");
          // Di sini bisa ditambahkan logika hapus baris tabel
        }
      }
    });
  });
});
