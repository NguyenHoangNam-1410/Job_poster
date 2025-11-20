let searchTimeout;
let form = document.getElementById('jobForm');
let initialValues = {};
let warningBox = document.getElementById('changeWarning');
let changedList = document.getElementById('changedFieldsList');
let statusBtn = document.getElementById('statusChangeBtn');
let statusInput = document.getElementById('newStatusInput');
let cancelBtn = document.getElementById('cancelBtn');

// Record initial values
if(form){
    form.querySelectorAll('input, textarea').forEach(el => {
        if (el.type === 'checkbox') {
            initialValues[el.name + '_' + el.value] = el.checked;
        } else {
            initialValues[el.name] = el.value;
        }
    });

    form.addEventListener('input', detectChanges);

    // Save Changes confirmation
    form.addEventListener('submit', (event) => {
        const action = event.submitter.value;
        
        // Only show this for Save Changes button
        if (action === "save_changes") {
            const checkboxes = document.querySelectorAll('input[name="categories[]"]');
            const anyChecked = Array.from(checkboxes).some(cb => cb.checked);

            if (!anyChecked) {
                alert("Please select at least one job category.");
                event.preventDefault();
                return;
            }

            if (!validateDeadline()) {
                alert(form.querySelector('[name="deadline"]').validationMessage);
                event.preventDefault();
                return;
            }

            // Removed browser confirm - using custom notification instead
        }
    });

}
// Detect changes for Save Changes only
function detectChanges() {
    let changed = [];
    form.querySelectorAll('input, textarea').forEach(el => {
        let label = el.dataset.fieldName || null;
        if (!label) return;

        if (el.type === 'checkbox') {
            let key = el.name + '_' + el.value;
            if (initialValues[key] !== el.checked) changed.push(label);
        } else {
            if (initialValues[el.name] !== el.value) changed.push(label);
        }
    });

    if (statusInput.value) changed.push("Job Status");

    let filtered = [...new Set(changed.filter(v => v && v.trim() !== ""))];

    if (filtered.length) {
        changedList.innerHTML = "";
        filtered.forEach(f => {
            let li = document.createElement('li');
            li.textContent = f;
            changedList.appendChild(li);
        });
        warningBox.classList.remove("hidden");
    } else {
        warningBox.classList.add("hidden");
    }
}



// Deadline validation function
function validateDeadline() {
    const deadlineInput = form.querySelector('[name="deadline"]');
    const status = statusInput.value;
    if (!deadlineInput) return true;

    if (status === "draft") return true;

    const value = deadlineInput.value;

    // Empty or placeholder value
    if (!value || value.startsWith("0000-00-00")) {
        deadlineInput.setCustomValidity("Please enter a valid application deadline.");
        return false;
    }

    const selectedDate = new Date(value);
    const now = new Date();

    // Check for invalid date
    if (isNaN(selectedDate.getTime())) {
        deadlineInput.setCustomValidity("Please enter a valid application deadline.");
        return false;
    }

    // Check if deadline is in the past
    if (selectedDate <= now) {
        deadlineInput.setCustomValidity("Application deadline cannot be in the past.");
        return false;
    }

    // Check for extremely far future dates
    if (selectedDate > new Date(9999, 11, 31)) {
        deadlineInput.setCustomValidity("Application deadline is too far in the future.");
        return false;
    }

    deadlineInput.setCustomValidity(""); // clear error
    return true;
}


// Status button: handle differently for create and edit pages
if (statusBtn) {
    statusBtn.addEventListener('click', function(e) {
        e.preventDefault();
        let action = this.dataset.action;
        let newStatus = "";

        switch(action) {
            case "post_job": 
                newStatus = "pending"; 
                break;
            case "mark_overdue": 
                newStatus = "overdue"; 
                break;
            case "reapprove": 
                newStatus = "approved"; 
                break;
            case "resubmit": 
                newStatus = "pending"; 
                break;
            default: 
                console.warn("Unknown status action:", action);
        }

        statusInput.value = newStatus;

        // Only validate deadline if not draft
        if (newStatus !== "draft" && !validateDeadline()) {
            alert(form.querySelector('[name="deadline"]').validationMessage);
            return;
        }

        // Show status preview
        let willChangeSpan = document.getElementById('statusWillChange');
        if (!willChangeSpan) {
            willChangeSpan = document.createElement('span');
            willChangeSpan.id = 'statusWillChange';
            willChangeSpan.className = 'ml-2 text-sm text-gray-600';
            statusBtn.insertAdjacentElement('afterend', willChangeSpan);
        }
        willChangeSpan.innerHTML = `Will change to: <strong>${newStatus}</strong> (saved when you press Save Changes)`;

        detectChanges();
    });
}

if(form){
    // Cancel warning - removed browser confirm
    cancelBtn.addEventListener('click', (e) => {
        if (!warningBox.classList.contains('hidden')) {
            // Just show notification, don't block
            if (window.notyf) {
                window.notyf.error('You have unsaved changes!');
            }
        }
    });

    // Immediate deadline validation on input
    const deadlineInput = form.querySelector('[name="deadline"]');
    if (deadlineInput) {
        deadlineInput.addEventListener('input', validateDeadline);
    }
}


// Delete confirmation using custom modal
async function handleDeleteJob(type, jobId) {
    let message = "Are you sure you want to delete this job?\n\n";
    if (type === 'soft') {
        message += "This will be a SOFT DELETE. Admin and Staff can still see it.";
    } else {
        message += "This will be a HARD DELETE. The job will be permanently removed.";
    }
    
    const confirmed = await window.confirmModal.show(
        'Delete Job',
        message,
        'Delete',
        'Cancel'
    );
    
    if (confirmed) {
        window.location.href = `/Job_poster/public/my-jobs/${type}-delete/${jobId}?type=${type}`;
    }
}

// Debounce search functionality
function debounceMyJobsSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        document.getElementById('myJobsFilterForm').submit();
    }, 500);
}