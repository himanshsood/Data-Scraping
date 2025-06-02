function lock(isProcessRunning) {
    const manualBtn = document.getElementById('manualUpdateBtn');
    const formElements = document.querySelectorAll('#csvUploadForm input, #csvUploadForm select, #csvUploadForm button');

    if (isProcessRunning) {
        // Disable the manual update button
        if (manualBtn) {
            manualBtn.disabled = true;
            manualBtn.classList.add('disable'); // No dot (.) here!
            manualBtn.style.backgroundColor = 'rgb(179, 179, 179)';
            manualBtn.style.pointerEvents = 'none';  // Disables hover and clicks
        }

        // Disable all form inputs/selects/buttons and style buttons
        formElements.forEach(el => {
            el.disabled = true;

            if (el.tagName === 'BUTTON') {
                el.classList.add('disable');
                el.style.backgroundColor = 'rgb(179, 179, 179)';
                el.style.pointerEvents = 'none';  // Disables hover and clicks
            }
        });
    } else {
        // Enable the manual update button
        if (manualBtn) {
            manualBtn.disabled = false;
            manualBtn.classList.remove('.disable');
        }

        // Enable all form inputs/selects/buttons and remove .disable class from buttons
        formElements.forEach(el => {
            el.disabled = false;
            if (el.tagName === 'BUTTON') {
                el.classList.remove('.disable');
            }
        });
    }
}
