<x-modal-confirm
    event-to-open-modal="custom-show-mark-poll-as-spam-modal"
    event-to-close-modal="pollWasMarkedAsSpam"
    modal-title="Mark Poll as Spam"
    modal-description="Are you sure you want to mark this poll as spam?"
    modal-confirm-button-text="Mark as Spam"
    wire-click="markAsSpam"
/>
