document.addEventListener('DOMContentLoaded', function() {
    // Account type selection
    const accountTypeCards = document.querySelectorAll('.account-type-card');
    const accountTypeInput = document.getElementById('accountTypeInput');

    // Auto-select if there's old input
    const oldType = accountTypeInput.value;
    if (oldType) {
        selectAccountType(oldType);
    }

    // Form validation
    const accountForm = document.getElementById('accountForm');
    if (accountForm) {
        accountForm.addEventListener('submit', function(e) {
            // Basic validation
            const accountType = document.getElementById('accountTypeInput').value;
            const accountName = document.getElementById('name').value;
            const accountBalance = document.getElementById('balance').value;

            if (!accountType || !accountName || accountBalance === '') {
                e.preventDefault();
                // Show error notification
                showNotification('Mohon lengkapi semua field yang diperlukan', 'error');
                return false;
            }

            return true;
        });
    }
});

function selectAccountType(type) {
    // Remove selected class from all cards
    document.querySelectorAll('.account-type-card').forEach(card => {
        card.classList.remove('selected');
    });

    // Add selected class to clicked card
    document.querySelector(`[data-type="${type}"]`).classList.add('selected');

    // Set hidden input value
    document.getElementById('accountTypeInput').value = type;
}

function showNotification(message, type) {
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
            <span>${message}</span>
        </div>
        <button class="notification-close" onclick="this.parentElement.remove()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    `;

    // Add to page
    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.remove();
    }, 5000);
}