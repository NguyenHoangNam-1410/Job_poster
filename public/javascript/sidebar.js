function toggleSidebar() {
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("sidebarOverlay");

  sidebar.classList.toggle("active");
  overlay.classList.toggle("active");
}

// Sidebar minimize/expand toggle
function toggleSidebarMinimize() {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.getElementById("mainContent");
  const html = document.documentElement;

  const willBeMinimized = !sidebar.classList.contains("minimized");

  sidebar.classList.toggle("minimized");
  mainContent.classList.toggle("expanded");

  // Sync with HTML class
  if (willBeMinimized) {
    html.classList.add("sidebar-minimized-state");
  } else {
    html.classList.remove("sidebar-minimized-state");
  }

  // Save state to localStorage
  localStorage.setItem("sidebarMinimized", willBeMinimized);
}

// Restore sidebar state on page load - sync with initial state
document.addEventListener("DOMContentLoaded", function () {
  const isMinimized = localStorage.getItem("sidebarMinimized") === "true";
  if (isMinimized) {
    document.getElementById("sidebar").classList.add("minimized");
    document.getElementById("mainContent").classList.add("expanded");
  }
});

// User dropdown toggle
function toggleUserDropdown() {
  const dropdown = document.getElementById("userDropdown");
  dropdown.classList.toggle("active");
}

// Close dropdowns when clicking outside
document.addEventListener("click", function (event) {
  const sidebar = document.getElementById("sidebar");
  const toggleBtn = document.querySelector(".toggle-sidebar");
  const userDropdown = document.getElementById("userDropdown");
  const userButton = document.querySelector(".user-profile-button");

  // Close sidebar on mobile
  if (window.innerWidth <= 768) {
    if (
      sidebar &&
      toggleBtn &&
      !sidebar.contains(event.target) &&
      !toggleBtn.contains(event.target)
    ) {
      sidebar.classList.remove("active");
      document.getElementById("sidebarOverlay").classList.remove("active");
    }
  }

  // Close user dropdown when clicking outside
  if (userDropdown && userButton) {
    if (
      !userDropdown.contains(event.target) &&
      !userButton.contains(event.target)
    ) {
      userDropdown.classList.remove("active");
    }
  }
});

// Toast notification function (reusable)
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
