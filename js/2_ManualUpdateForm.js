// FORM 2 : MANUAL UPDATE
const btn = document.getElementById('manualUpdateBtn');
const statusText = document.getElementById('updateStatus');
const completeText = document.getElementById('updateComplete');
btn.addEventListener('click', () => {
    btn.disabled = true;
    statusText.style.display = 'block';
    completeText.style.display = 'none';

    // alert('Manual update triggered!'); // ALERT TO NOTIFY THAT MANUAL UPDATE IS TRIGGERED ! 

    fetch('process_form.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'manual_update=1'
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            pollUpdateStatus(data.run_id);  // pass run_id or message if needed
        } else {
            alert('Error starting update: ' + data.message);
            btn.disabled = false;
            statusText.style.display = 'none';
        }
    })
    .catch(() => {
        alert('Failed to start update.');
        btn.disabled = false;
        statusText.style.display = 'none';
    });
});




// Poll backend for status every 2 seconds
function pollUpdateStatus(runId) {
    const interval = setInterval(() => {
        fetch('process_form.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `check_progress=1&run_id=${encodeURIComponent(runId)}`
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.progress === 100) {
                clearInterval(interval);
                statusText.style.display = 'none';
                completeText.style.display = 'block';
                btn.disabled = false;
            } else if (data.status === 'error') {
                clearInterval(interval);
                alert('Update error: ' + data.message);
                btn.disabled = false;
                statusText.style.display = 'none';
            }
            // else: still in progress, do nothing
        })
        .catch(() => {
            clearInterval(interval);
            alert('Error checking update status.');
            btn.disabled = false;
            statusText.style.display = 'none';
        });
    }, 2000);
}