// Form Modal Handler
class FormModal {
  constructor() {
    this.modal = null;
    this.createModal();
  }

  createModal() {
    // Don't create if already exists
    if (document.getElementById('formModal')) {
      this.modal = document.getElementById('formModal');
      return;
    }

    const modalHTML = `
      <div id="formModal" class="fixed inset-0 z-[100] hidden bg-black bg-opacity-50 overflow-y-auto p-4 pt-12">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-auto my-8 max-h-[calc(100vh-8rem)] flex flex-col transform transition-all">
          <!-- Modal Header -->
          <div class="flex items-center justify-between p-6 border-b border-gray-200 flex-shrink-0">
            <h2 id="formModalTitle" class="text-2xl font-bold text-gray-900"></h2>
            <button id="formModalClose" class="text-gray-400 hover:text-gray-600 focus:outline-none transition">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Modal Body -->
          <div id="formModalBody" class="p-6 overflow-y-auto flex-1">
            <div class="flex items-center justify-center py-8">
              <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
            </div>
          </div>
          
          <!-- Modal Footer (for buttons) -->
          <div id="formModalFooter" class="border-t border-gray-200 bg-gray-50 px-6 py-4 flex-shrink-0 hidden">
          </div>
        </div>
      </div>
    `;

    const div = document.createElement("div");
    div.innerHTML = modalHTML;
    document.body.appendChild(div.firstElementChild);

    this.modal = document.getElementById("formModal");
    this.setupEventListeners();
  }

  setupEventListeners() {
    const closeBtn = document.getElementById("formModalClose");
    let isMouseDownOnBackdrop = false;
    
    closeBtn.addEventListener("click", () => this.close());

    // Close on ESC key
    document.addEventListener("keydown", (e) => {
      if (e.key === "Escape" && this.modal && !this.modal.classList.contains("hidden")) {
        this.close();
      }
    });

    // Close on backdrop click only (not on drag)
    this.modal.addEventListener("mousedown", (e) => {
      // Only set flag if click is on the backdrop itself, not on modal content
      if (e.target === this.modal) {
        isMouseDownOnBackdrop = true;
      }
    });

    this.modal.addEventListener("mouseup", (e) => {
      // Only close if mousedown and mouseup both happened on backdrop
      if (e.target === this.modal && isMouseDownOnBackdrop) {
        this.close();
      }
      isMouseDownOnBackdrop = false;
    });

    // Reset flag if mouse leaves modal area
    this.modal.addEventListener("mouseleave", () => {
      isMouseDownOnBackdrop = false;
    });
  }

  adjustModalHeight() {
    const modalContainer = this.modal.querySelector('.bg-white');
    const modalBody = document.getElementById("formModalBody");
    
    // Wait for content to render
    setTimeout(() => {
      const contentHeight = modalBody.scrollHeight;
      const headerHeight = 73; // Approximate header height with padding
      const totalNeededHeight = contentHeight + headerHeight;
      const maxHeight = window.innerHeight - 128; // 8rem = 128px
      
      // If content is short enough to fit without scrolling
      if (totalNeededHeight < maxHeight) {
        modalContainer.style.maxHeight = 'none';
        modalContainer.style.height = 'auto';
        modalBody.style.maxHeight = 'none';
        modalBody.style.overflow = 'visible';
        modalBody.classList.remove('flex-1');
      } else {
        // For long content, keep scrollable behavior
        modalContainer.style.maxHeight = 'calc(100vh - 8rem)';
        modalContainer.style.height = '';
        modalBody.style.maxHeight = '';
        modalBody.style.overflow = '';
        if (!modalBody.classList.contains('flex-1')) {
          modalBody.classList.add('flex-1');
        }
      }
    }, 100);
  }

  async loadForm(url, title) {
    // Ensure modal is created
    if (!this.modal) {
      this.createModal();
    }

    // Update title
    document.getElementById("formModalTitle").textContent = title;

    // Show modal with loading state
    this.modal.classList.remove("hidden");
    this.modal.classList.add("flex");
    
    const modalBody = document.getElementById("formModalBody");
    modalBody.innerHTML = `
      <div class="flex items-center justify-center py-8">
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
      </div>
    `;

    try {
      const response = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest'
        }
      });

      if (!response.ok) {
        throw new Error('Failed to load form');
      }

      const html = await response.text();
      
      // Extract form content (between form tags)
      const parser = new DOMParser();
      const doc = parser.parseFromString(html, 'text/html');
      const formElement = doc.querySelector('form');
      
      if (formElement) {
        // Update the form action to handle modal submission
        const originalAction = formElement.action;
        formElement.dataset.originalAction = originalAction;
        
        // Move form buttons to footer
        const formButtons = formElement.querySelector('.flex.gap-3, .flex.gap-4, .mt-6');
        const modalFooter = document.getElementById('formModalFooter');
        
        if (formButtons && formButtons.querySelector('button[type="submit"]')) {
          // Clone buttons to footer
          const buttonContainer = formButtons.cloneNode(true);
          buttonContainer.classList.add('justify-end');
          modalFooter.innerHTML = '';
          modalFooter.appendChild(buttonContainer);
          modalFooter.classList.remove('hidden');
          
          // Remove original buttons from form
          formButtons.remove();
          
          // Re-attach event listeners to new buttons
          const newSubmitBtn = modalFooter.querySelector('button[type="submit"]');
          const newCancelBtn = modalFooter.querySelector('button[type="button"]');
          
          if (newSubmitBtn) {
            newSubmitBtn.onclick = (e) => {
              e.preventDefault();
              formElement.requestSubmit();
            };
          }
          
          if (newCancelBtn) {
            newCancelBtn.onclick = () => this.close();
          }
        }
        
        modalBody.innerHTML = '';
        modalBody.appendChild(formElement);
        
        // Execute any script tags in the loaded content
        const scripts = doc.querySelectorAll('script');
        scripts.forEach(oldScript => {
          const newScript = document.createElement('script');
          if (oldScript.src) {
            newScript.src = oldScript.src;
          } else {
            newScript.textContent = oldScript.textContent;
          }
          document.head.appendChild(newScript);
        });
        
        // Setup form submission handler
        this.setupFormSubmission(formElement);
      } else {
        modalBody.innerHTML = '<p class="text-red-600">Error loading form</p>';
      }
    } catch (error) {
      console.error('Error loading form:', error);
      modalBody.innerHTML = '<p class="text-red-600">Error loading form. Please try again.</p>';
    }
  }

  setupFormSubmission(form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      
      // Find submit button in footer
      const modalFooter = document.getElementById('formModalFooter');
      const submitBtn = modalFooter.querySelector('button[type="submit"]') || form.querySelector('button[type="submit"]');
      const originalText = submitBtn.innerHTML;
      
      // Show loading state
      submitBtn.disabled = true;
      submitBtn.innerHTML = `
        <svg class="animate-spin h-5 w-5 inline-block mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Saving...
      `;

      try {
        const formData = new FormData(form);
        // Use getAttribute to avoid conflict with form elements named 'action'
        const formAction = form.getAttribute('action');
        const response = await fetch(formAction, {
          method: 'POST',
          body: formData,
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          }
        });

        // Get response text first to check if it's JSON
        const responseText = await response.text();
        let data;
        
        try {
          data = JSON.parse(responseText);
        } catch (e) {
          // Response is not JSON, likely HTML error page
          console.error('Server returned non-JSON response:', responseText.substring(0, 200));
          throw new Error('Server error: Invalid response format');
        }

        if (data.success) {
          // Close modal first
          this.close();
          
          // Show success notification
          if (window.notyf) {
            window.notyf.success(data.message || 'Saved successfully!');
          }
          
          // Check if there's a redirect URL (e.g., from company profile to job posting)
          if (data.redirect) {
            setTimeout(() => {
              window.formModal.loadForm(data.redirect, 'Post New Job');
            }, 800);
          } else {
            // Reload page after a short delay
            setTimeout(() => {
              window.location.reload();
            }, 500);
          }
        } else {
          // Show error message in modal
          const errorDiv = document.createElement('div');
          errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
          errorDiv.textContent = data.message || 'An error occurred';
          
          form.insertBefore(errorDiv, form.firstChild);
          
          // Restore button
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      } catch (error) {
        console.error('Error submitting form:', error);
        
        // Show error message with more detail
        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        errorDiv.innerHTML = `<strong>Error:</strong> ${error.message || 'An error occurred. Please try again.'}`;
        
        form.insertBefore(errorDiv, form.firstChild);
        
        // Restore button
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalText;
      }
    });
  }

  close() {
    if (!this.modal) return;
    
    this.modal.classList.add("hidden");
    this.modal.classList.remove("flex");
    
    // Clear content
    document.getElementById("formModalBody").innerHTML = '';
    document.getElementById("formModalFooter").innerHTML = '';
    document.getElementById("formModalFooter").classList.add('hidden');
  }
}

// Initialize when DOM is ready
if (typeof window !== 'undefined') {
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      window.formModal = new FormModal();
    });
  } else {
    window.formModal = new FormModal();
  }
}
