<div id="modal-overlay" class="fixed inset-0 bg-gray-700/50 hidden z-50">
    <div class="fixed inset-0 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl shadow-lg max-w-sm w-full relative">
            <button id="close-button" onclick="closeModal()"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700">
                {{-- &times; --}}
                Close
            </button>
            <h2 class="text-xl font-bold mb-2" id="modal-title">Popup Title</h2>
            <p id="modal-message">This is the content of your popup.</p>

            <div class="mt-4">
                <button class=" bg-gray-200 p-2 px-4 rounded-xl hover:bg-gray-300" id="btn_confirm">Confirm</button>
                <button class=" bg-gray-200 p-2 px-4 rounded-xl hover:bg-red-400" id="btn_cancel">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    // confirmation modal
    function confirmAction(message, title) {
        const modalOverlay = document.getElementById('modal-overlay');
        const modalMessage = document.getElementById('modal-message');
        const modalTitle = document.getElementById('modal-title');
        modalMessage.textContent = message;
        modalTitle.textContent = title
        modalOverlay.classList.remove('hidden');

        return new Promise((resolve) => {
            const yesBtn = document.getElementById('btn_confirm');
            const noBtn = document.getElementById('btn_cancel');

            // console.log("confirm action promise is running");

            yesBtn.addEventListener('click', function() {
                resolve(true);
                closeModal();
                // console.log("modal confirmed");
            });

            noBtn.addEventListener('click', function() {
                resolve(false);
                closeModal();
            });
        });
    }

    function closeModal() {
        const modalOverlay = document.getElementById('modal-overlay');
        modalOverlay.classList.add('hidden');
    }

    // ========================================================================= 

    let form_id = "";
    let popupAccepted = false; // Flag to track popup acceptance

    function handleFormSubmission(event, message, title, _form_id) {
        if (!popupAccepted) {
            form_id = _form_id
            event.preventDefault(); // Prevent default form submission
            openCustomPopup(message, title);
            return false; // Prevent form submission
        }
        return true; // Allow form submission if popup is accepted
    }

    const yesBtn = document.getElementById('btn_confirm');
    const noBtn = document.getElementById('btn_cancel');

    // console.log("confirm action promise is running");

    yesBtn.addEventListener('click', function() {
        acceptPopup()
        // console.log("modal confirmed");
    });

    noBtn.addEventListener('click', function() {
        declinePopup()
    });

    function openCustomPopup(message, title) {
        const modalMessage = document.getElementById('modal-message');
        const modalTitle = document.getElementById('modal-title');
        modalMessage.textContent = message;
        modalTitle.textContent = title;

        const modalOverlay = document.getElementById('modal-overlay');
        modalOverlay.classList.remove('hidden');
    }

    function closeCustomPopup() {
        const modalOverlay = document.getElementById('modal-overlay');
        modalOverlay.classList.add('hidden');
    }

    function acceptPopup() {
        popupAccepted = true;
        closeCustomPopup();
        // form.submit();
        console.log(form_id)
        document.getElementById(form_id).submit(); // Submit the form after acceptance
    }

    function declinePopup() {
        popupAccepted = false;
        closeCustomPopup();
        // Optionally, you can add further actions here if the user declines
        // alert("Form submission cancelled. Please accept the terms to proceed.");
    }
</script>

<!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
