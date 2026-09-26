<dialog id="confirm-dialog" aria-labelledby="confirm-title" aria-describedby="confirm-message"
    class="m-auto w-[calc(100%-2rem)] max-w-md rounded-2xl border border-line bg-white p-0 text-ink shadow-2xl backdrop:bg-lobby-950/60">
    <div class="p-6">
        <h2 id="confirm-title" class="font-display text-2xl font-bold tracking-wide text-lobby-900 uppercase">Confirm</h2>
        <p id="confirm-message" class="mt-2 text-muted"></p>
    </div>
    <div class="flex flex-col-reverse gap-3 border-t border-line bg-porcelain px-6 py-4 sm:flex-row sm:justify-end">
        <button type="button" id="confirm-cancel" class="btn btn-secondary">Cancel</button>
        <button type="button" id="confirm-accept" class="btn btn-primary">Confirm</button>
    </div>
</dialog>

<script>
    (() => {
        const dialog = document.getElementById('confirm-dialog');
        let pendingForm = null;

        document.getElementById('confirm-cancel').addEventListener('click', () => dialog.close());
        document.getElementById('confirm-accept').addEventListener('click', () => {
            const form = pendingForm;
            dialog.close();
            form?.submit();
        });
        dialog.addEventListener('close', () => {
            pendingForm = null;
        });

        window.handleFormSubmission = (event, message, title, formId) => {
            event.preventDefault();
            pendingForm = document.getElementById(formId);
            document.getElementById('confirm-title').textContent = title;
            document.getElementById('confirm-message').textContent = message;
            dialog.showModal();
            document.getElementById('confirm-accept').focus();
            return false;
        };
    })();
</script>
