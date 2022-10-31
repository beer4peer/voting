<x-modal-confirm
    event-to-open-modal="custom-show-delete-modal"
    event-to-close-modal="pollWasDeleted"
    modal-title="Delete Poll"
    modal-description="Are you sure you want to delete this poll? This action cannot be undone."
    modal-confirm-button-text="Delete"
    wire-click="deletePoll"
/>
