<x-modal-confirm
    event-to-open-modal="custom-show-mark-poll-as-not-spam-modal"
    event-to-close-modal="pollWasMarkedAsNotSpam"
    modal-title="Reset Spam Counter"
    modal-description="Are you sure you want to mark this poll as NOT spam? This will reset the spam counter to 0."
    modal-confirm-button-text="Reset Spam Counter"
    wire-click="markAsNotSpam"
/>
