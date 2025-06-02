function gettime() {
    const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

    fetch('http://3.234.76.112:5000/api/user/timezone', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ timezone })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // alert("Timezone saved: " + data.timezone);
            console.log("Timezone saved:", data);
        } else {
            alert("Error: " + (data.message || "Something went wrong while saving timezone."));
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        alert("Error: Unable to contact timezone service. Please contact developer.");
    });
}

gettime();
