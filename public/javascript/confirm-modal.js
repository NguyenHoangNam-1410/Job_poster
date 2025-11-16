// Custom confirmation modal
class ConfirmModal {
  constructor() {
    this.modal = null;
    this.resolveCallback = null;
    
    // Wait for DOM to be ready before creating modal
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', () => this.createModal());
    } else {
      this.createModal();
    }
  }

  createModal() {
    // Don't create if already exists
    if (document.getElementById('confirmModal')) {
      this.modal = document.getElementById('confirmModal');
      this.setupEventListeners();
      return;
    }

    // Create modal HTML
    const modalHTML = `
      <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50 transition-opacity">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 transform transition-all">
          <div class="p-6">
            <div class="flex items-start">
              <div class="flex-shrink-0">
                <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="ml-4 flex-1">
                <h3 class="text-lg font-semibold text-gray-900 mb-2" id="confirmModalTitle">Confirm Action</h3>
                <p class="text-sm text-gray-600 whitespace-pre-line" id="confirmModalMessage"></p>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-6 py-4 flex justify-end gap-3 rounded-b-lg">
            <button id="confirmModalCancel" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
              Cancel
            </button>
            <button id="confirmModalConfirm" class="px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition">
              Confirm
            </button>
          </div>
        </div>
      </div>
    `;

    // Add modal to body
    const div = document.createElement("div");
    div.innerHTML = modalHTML;
    document.body.appendChild(div.firstElementChild);

    this.modal = document.getElementById("confirmModal");
    this.setupEventListeners();
  }

  setupEventListeners() {
    const cancelBtn = document.getElementById("confirmModalCancel");
    const confirmBtn = document.getElementById("confirmModalConfirm");

    if (!cancelBtn || !confirmBtn) return;

    cancelBtn.addEventListener("click", () => this.close(false));
    confirmBtn.addEventListener("click", () => this.close(true));

    // Close on backdrop click
    this.modal.addEventListener("click", (e) => {
      if (e.target === this.modal) {
        this.close(false);
      }
    });

    // Close on ESC key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && this.modal && !this.modal.classList.contains("hidden")) {
        this.close(false);
      }
    });
  }

  show(message, title = "Confirm Action", confirmText = "Confirm") {
    return new Promise((resolve) => {
      // Ensure modal is created
      if (!this.modal) {
        this.createModal();
      }

      this.resolveCallback = resolve;

      // Update content
      document.getElementById("confirmModalTitle").textContent = title;
      document.getElementById("confirmModalMessage").textContent = message;
      document.getElementById("confirmModalConfirm").textContent = confirmText;

      // Show modal with animation
      this.modal.classList.remove("hidden");
      this.modal.classList.add("flex");
      setTimeout(() => {
        this.modal.style.opacity = "1";
      }, 10);
    });
  }

  close(result) {
    if (!this.modal) return;
    
    // Hide modal with animation
    this.modal.style.opacity = "0";
    setTimeout(() => {
      this.modal.classList.add("hidden");
      this.modal.classList.remove("flex");
      if (this.resolveCallback) {
        this.resolveCallback(result);
        this.resolveCallback = null;
      }
    }, 200);
  }
}

// Create global instance when DOM is ready
if (typeof window !== 'undefined') {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      window.confirmModal = new ConfirmModal();
    });
  } else {
    window.confirmModal = new ConfirmModal();
  }
}
