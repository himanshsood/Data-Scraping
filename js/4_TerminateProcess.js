// CANCEL/TERMINATE FUNCTION
function CancelFunction(id) {
    if (!confirm(`Are you sure you want to cancel current job?`)) return;

    fetch('process_form.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'terminate_run=' + encodeURIComponent(id)
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        window.location.reload(); // Refresh after termination
    })
    .catch(error => { // this is running i dont know why 
        console.error('Error:', error);
        alert('Termination failed');
    });
}