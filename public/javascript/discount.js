function showToast(message, isError = false) {
  const toast = document.createElement("div");
  toast.className = "toast" + (isError ? " error" : "");
  toast.textContent = message;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.classList.add("hide");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

function deleteDiscount(id) {
  if (!confirm("Are you sure you want to delete this discount?")) return;

  fetch(`/Job_poster/public/discounts/delete/${id}`, {
    method: "GET",
    headers: { "X-Requested-With": "XMLHttpRequest" },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        showToast("Discount deleted successfully!");
        const row = document.getElementById(`discount-${id}`);
        row.classList.add("fade-out");
        setTimeout(() => {
          row.remove();
          const tbody = document.getElementById("discounts-table-body");
          if (tbody.children.length === 0) {
            const table = tbody.closest(".overflow-x-auto");
            table.innerHTML = `
                        <div id="empty-state" class="text-center py-12">
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No discounts found</h3>
                            <p class="mt-1 text-sm text-gray-500">Start by creating a new discount.</p>
                        </div>
                    `;
          }
        }, 300);
      } else {
        showToast(data.message || "Failed to delete discount", true);
      }
    })
    .catch((error) => {
      console.error("Delete error:", error);
      showToast("Error: " + error.message, true);
    });
}