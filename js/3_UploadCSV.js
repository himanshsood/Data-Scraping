// DISPLAY FILE NAME
document.getElementById('csvFile').addEventListener('change', function(e) {
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const file = e.target.files[0];
    
    if (file) {
        fileNameDisplay.textContent = file.name;
        fileNameDisplay.style.color = '#333';
        fileNameDisplay.style.fontStyle = 'normal';
    } else {
        fileNameDisplay.textContent = 'No file selected';
        fileNameDisplay.style.color = '#666';
        fileNameDisplay.style.fontStyle = 'italic';
    }
});




// UPLOAD CSV LOGIC
document.getElementById("csvUploadForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const progressContainer = document.getElementById("progressContainer");
    // const progressBar = document.getElementById("progressBar");
    const progressMessage = document.getElementById("progressMessage");

    progressContainer.style.display = "block";
    // progressBar.value = 0;
    progressMessage.textContent = "Uploading CSV...";

    fetch("http://localhost:5000/api/updates/csv/preview", {
        method: "POST",
        body: formData
    })
    .then(async res => {
        const contentType = res.headers.get("content-type") || "";
        if (!contentType.includes("application/json")) {
            throw new Error("Invalid response from server");
        }

        const data = await res.json();
        if (!res.ok) {
            throw new Error(data.message || `Server Error: ${res.status}`);
        }

        if (data.status === "success" || data.data || data.data.run_id) {
            progressMessage.textContent = "Upload successful. Monitoring progress...";
            // pollProgress(data.data.run_id);
            // WE ARE GOING TO WORK ON THIS SECTION ! 
        } else {
            throw new Error(data.message || "Unknown error occurred");
        }
    })
    .catch(err => {
        console.error("Upload error:", err);
        progressMessage.textContent = `Upload error: ${err.message}`;
    });
});



// // PROGRESS BAR LOGIC
// function pollProgress(runId) {
//     const progressBar = document.getElementById("progressBar");
//     const progressMessage = document.getElementById("progressMessage");
//     function check() {
//         fetch(`http://localhost:5000/api/updates/progress/${runId}`)
//             .then(res => res.json())
//             .then(data => {
//                 if (data.status === "success") {
//                     const progress = data.progress || 0;
//                     progressBar.value = progress;
//                     progressMessage.textContent = `Progress: ${progress}% - ${data.message}`;

//                     if (progress < 100) {
//                         setTimeout(check, 2000);
//                     } else {
//                         progressMessage.textContent = "✅ Update completed successfully!";
//                     }
//                 } else {
//                     progressMessage.textContent = "⚠️ Progress check failed.";
//                 }
//             })
//             .catch(err => {
//                 console.error(err);
//                 progressMessage.textContent = "❌ Error while checking progress.";
//             });
//     }
//     check();
// }