const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("sidebarOverlay");

function toggleSidebar() {
  sidebar.classList.toggle("active");
  overlay.classList.toggle("active");
}

overlay.addEventListener("click", () => {
  sidebar.classList.remove("active");
  overlay.classList.remove("active");
});
