// Show toast notification
function showToast(message, type = "success") {
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.textContent = message;
  document.body.appendChild(toast);

  setTimeout(() => {
    toast.classList.add("hide");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// Delete user with AJAX
function deleteUser(userId, username) {
  if (!confirm(`Are you sure you want to delete user "${username}"?`)) {
    return;
  }

  const row = document.getElementById(`user-row-${userId}`);

  // Show loading state
  const originalContent = row.innerHTML;
  row.style.opacity = "0.5";
  row.style.pointerEvents = "none";

  // Make AJAX request
  fetch(`/Job_poster/public/users/delete/${userId}`, {
    method: "GET",
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => {
      if (response.ok) {
        // Animate row removal
        row.classList.add("fade-out");
        setTimeout(() => {
          row.remove();

          // Check if table is now empty
          const tbody = document.getElementById("usersTableBody");
          if (tbody.children.length === 0) {
            tbody.innerHTML =
              '<tr id="no-users-row"><td colspan="5" class="p-4 text-center text-gray-500">No users found</td></tr>';
          }
        }, 300);

        showToast(`User "${username}" deleted successfully!`, "success");
      } else {
        throw new Error("Delete failed");
      }
    })
    .catch((error) => {
      // Restore row on error
      row.style.opacity = "1";
      row.style.pointerEvents = "auto";
      showToast("Failed to delete user. Please try again.", "error");
      console.error("Delete error:", error);
    });
}

// Optional: Reload table data without page refresh
function refreshUserList() {
  fetch("/Job_poster/public/users/api/list", {
    headers: {
      "X-Requested-With": "XMLHttpRequest",
    },
  })
    .then((response) => response.json())
    .then((data) => {
      const tbody = document.getElementById("usersTableBody");
      if (data.users.length === 0) {
        tbody.innerHTML =
          '<tr id="no-users-row"><td colspan="5" class="p-4 text-center text-gray-500">No users found</td></tr>';
      } else {
        tbody.innerHTML = data.users
          .map(
            (user) => `
                        <tr class="border-t hover:bg-gray-50" id="user-row-${
                          user.id
                        }">
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${
                              user.id
                            }</td>
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${escapeHtml(
                              user.username
                            )}</td>
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">${escapeHtml(
                              user.email
                            )}</td>
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><span class="px-2 py-1 rounded ${
                              user.role === "Admin"
                                ? "bg-red-100 text-red-800"
                                : "bg-green-100 text-green-800"
                            }">${escapeHtml(user.role)}</span></td>
                            <td class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <a href="/Job_poster/public/users/edit/${
                                  user.id
                                }" class="text-blue-500 hover:underline">Edit</a> |
                                <button onclick="deleteUser(${
                                  user.id
                                }, '${escapeHtml(
              user.username
            )}')" class="text-red-500 hover:underline focus:outline-none">Delete</button>
                            </td>
                        </tr>
                    `
          )
          .join("");
      }
    })
    .catch((error) => {
      showToast("Failed to refresh user list", "error");
    });
}

function escapeHtml(text) {
  const map = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#039;",
  };
  return text.replace(/[&<>"']/g, (m) => map[m]);
}