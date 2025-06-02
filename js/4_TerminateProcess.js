// CANCEL/TERMINATE FUNCTION
function CancelFunction(id) {
    if (!confirm(`Are you sure you want to cancel current job?`)) return;
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.style.display = 'flex';

    }
    fetch('process_form.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'terminate_run=' + encodeURIComponent(id)
    })
    .then(response => response.json())
    .then(data => {
        setTimeout(() => {
            loadingOverlay.style.display = 'none';
            window.location.reload() ; 
        }, 15000);

        // window.location.reload(); // Refresh after termination
    })
    .catch(error => { // this is running i dont know why 
        console.error('Error:', error);
        alert('Termination failed');
    });
}