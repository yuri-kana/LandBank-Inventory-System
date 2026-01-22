/**
 * Auto Notification System
 * Handles automatic notification updates when requests are processed
 */

class AutoNotificationSystem {
    constructor() {
        this.currentRequestId = null;
        this.pollingInterval = null;
        this.isPolling = false;
    }

    /**
     * Initialize for a specific request
     */
    initForRequest(requestId) {
        this.currentRequestId = requestId;
        
        // Auto-mark notifications as done when viewing request details
        this.autoMarkAsDone(requestId);
        
        // Start polling for status changes
        this.startPolling(requestId);
    }

    /**
     * Auto-mark notifications as done when viewing request
     */
    autoMarkAsDone(requestId) {
        console.log('Auto-marking notifications as done for request:', requestId);
        
        fetch(`/notifications/auto-mark-done/${requestId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Auto-marked notifications:', data);
                
                // Update notification badge if on requests page
                if (window.updateNotificationBadge) {
                    window.updateNotificationBadge();
                }
                
                // If request is already processed, update UI
                if (data.request_status && this.shouldUpdateUI(data.request_status)) {
                    this.updateRequestUI(requestId, data.request_status);
                }
            }
        })
        .catch(error => {
            console.error('Error auto-marking notifications:', error);
        });
    }

    /**
     * Start polling for request status changes
     */
    startPolling(requestId) {
        if (this.isPolling) {
            this.stopPolling();
        }

        this.isPolling = true;
        let lastStatus = null;

        this.pollingInterval = setInterval(() => {
            this.checkRequestStatus(requestId).then(currentStatus => {
                if (lastStatus === null) {
                    lastStatus = currentStatus;
                } else if (lastStatus !== currentStatus) {
                    // Status changed - update notifications
                    console.log('Request status changed:', lastStatus, '->', currentStatus);
                    lastStatus = currentStatus;
                    
                    // Update notifications for the changed status
                    this.updateNotificationsForStatus(requestId, currentStatus);
                    
                    // Update UI
                    this.updateRequestUI(requestId, currentStatus);
                }
            });
        }, 5000); // Check every 5 seconds
    }

    /**
     * Stop polling
     */
    stopPolling() {
        if (this.pollingInterval) {
            clearInterval(this.pollingInterval);
            this.pollingInterval = null;
            this.isPolling = false;
        }
    }

    /**
     * Check current request status
     */
    async checkRequestStatus(requestId) {
        try {
            const response = await fetch(`/api/requests/${requestId}/status`);
            if (!response.ok) throw new Error('Failed to fetch status');
            
            const data = await response.json();
            return data.status || 'pending';
        } catch (error) {
            console.error('Error checking request status:', error);
            return 'pending';
        }
    }

    /**
     * Update notifications when request status changes
     */
    updateNotificationsForStatus(requestId, status) {
        if (!['approved', 'rejected', 'claimed'].includes(status)) {
            return;
        }

        console.log('Updating notifications for status change:', status);
        
        // Mark notifications as resolved
        fetch(`/notifications/auto-mark-done/${requestId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Updated notifications for status change:', data);
                
                // Update notification badge
                if (window.updateNotificationBadge) {
                    window.updateNotificationBadge();
                }
                
                // Show success message
                this.showStatusChangeMessage(status);
            }
        })
        .catch(error => {
            console.error('Error updating notifications for status change:', error);
        });
    }

    /**
     * Update UI based on request status
     */
    updateRequestUI(requestId, status) {
        // Find the request row in the table
        const requestRow = document.querySelector(`[data-request-id="${requestId}"]`);
        if (!requestRow) return;

        // Update status badge
        const statusBadge = requestRow.querySelector('.status-badge');
        if (statusBadge) {
            statusBadge.textContent = status.charAt(0).toUpperCase() + status.slice(1);
            
            // Update badge color
            const badgeClasses = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'approved': 'bg-green-100 text-green-800',
                'rejected': 'bg-red-100 text-red-800',
                'claimed': 'bg-blue-100 text-blue-800'
            };
            
            Object.values(badgeClasses).forEach(cls => statusBadge.classList.remove(...cls.split(' ')));
            statusBadge.classList.add(...badgeClasses[status].split(' '));
        }

        // Update action buttons
        const actionCell = requestRow.querySelector('.request-actions');
        if (actionCell && ['approved', 'rejected', 'claimed'].includes(status)) {
            // Disable approve/reject buttons if request is processed
            const approveBtn = actionCell.querySelector('.approve-btn');
            const rejectBtn = actionCell.querySelector('.reject-btn');
            
            if (approveBtn) approveBtn.disabled = true;
            if (rejectBtn) rejectBtn.disabled = true;
            
            // Show claim button if approved
            if (status === 'approved') {
                const claimBtn = actionCell.querySelector('.claim-btn');
                if (claimBtn) claimBtn.style.display = 'inline-block';
            }
            
            // Hide claim button if claimed
            if (status === 'claimed') {
                const claimBtn = actionCell.querySelector('.claim-btn');
                if (claimBtn) claimBtn.style.display = 'none';
            }
        }
    }

    /**
     * Show status change message
     */
    showStatusChangeMessage(status) {
        const messages = {
            'approved': 'Request has been approved! The team has been notified.',
            'rejected': 'Request has been rejected! The team has been notified.',
            'claimed': 'Items have been claimed! Stock has been updated.'
        };

        const message = messages[status];
        if (!message) return;

        // Create toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg bg-green-50 text-green-800 border border-green-200 animate-slide-up';
        toast.innerHTML = `
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3 text-green-600"></i>
                <span>${message}</span>
                <button class="ml-4 text-green-600 hover:text-green-800" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;

        document.body.appendChild(toast);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 5000);
    }

    /**
     * Check if UI should be updated
     */
    shouldUpdateUI(status) {
        return ['approved', 'rejected', 'claimed'].includes(status);
    }

    /**
     * Initialize for all requests on page
     */
    initForAllRequests() {
        // Find all request rows
        const requestRows = document.querySelectorAll('[data-request-id]');
        
        requestRows.forEach(row => {
            const requestId = row.getAttribute('data-request-id');
            const status = row.getAttribute('data-request-status');
            
            // If request is already processed, mark notifications as done
            if (status && ['approved', 'rejected', 'claimed'].includes(status)) {
                this.autoMarkAsDone(requestId);
            }
            
            // Add click handler to auto-mark when viewing
            const viewLink = row.querySelector('a[href*="/requests/"]');
            if (viewLink) {
                viewLink.addEventListener('click', () => {
                    this.autoMarkAsDone(requestId);
                });
            }
        });
    }
}

// Create global instance
window.autoNotificationSystem = new AutoNotificationSystem();

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize for all requests on requests index page
    if (window.autoNotificationSystem) {
        window.autoNotificationSystem.initForAllRequests();
    }
    
    // Add CSS for animations
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slide-up {
            from {
                transform: translateY(20px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .animate-slide-up {
            animation: slide-up 0.3s ease-out;
        }
    `;
    document.head.appendChild(style);
});