// UPLOAD CSV LOGIC
document.getElementById("csvUploadForm").addEventListener("submit", function (e) {
    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const progressContainer = document.getElementById("progressContainer");
    const progressMessage = document.getElementById("progressMessage");

    progressContainer.style.display = "block";
    progressMessage.textContent = "Uploading CSV...";


    const LOADING = document.getElementById('loadingOverlay') ; 
    LOADING.style.display = 'flex';

    fetch("http://3.234.76.112:5000/api/updates/csv/preview", {
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
        
        if (data.status === "success" && data.data) {
            
            console.log(data) ; 
            // Store CSV metadata in localStorage
            if (data.last_csv_path && data.last_csv_type) {
                localStorage.setItem("last_csv_path", data.last_csv_path);
                localStorage.setItem("last_csv_type", data.last_csv_type);
            }

            const LOADING = document.getElementById('loadingOverlay');
            LOADING.style.display = 'none';
            // alert("DATA FETCH SUCCESSFULLY!");

        // Populate table in modal
        const popupTableContainer = document.getElementById("popupTableContainer");
        const responseArray = data.data; // assuming this is the array

        let tableHTML = "<table style='width: 100%; border-collapse: collapse;'>";
        tableHTML += `
        <thead>
            <tr style='background: #f2f2f2;'>
            <th style="border: 1px solid #ccc; padding: 8px;">Sr. No.</th>
            <th style="border: 1px solid #ccc; padding: 8px;">Name</th>
            <th style="border: 1px solid #ccc; padding: 8px;">CM1</th>
            <th style="border: 1px solid #ccc; padding: 8px;">CM2</th>
            <th style="border: 1px solid #ccc; padding: 8px;">CM3</th>
            </tr>
        </thead><tbody>`;

        responseArray.forEach((row, index) => {
            const name = row.name || "";
            const cm1 = row.new_ch1 || "";
            const cm2 = row.current?.ch1 || "";
            const cm3 = row.current?.ch2 || "";

            tableHTML += `
            <tr>
                <td style="border: 1px solid #ccc; padding: 8px;">${index + 1}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">${name}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">${cm1}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">${cm2}</td>
                <td style="border: 1px solid #ccc; padding: 8px;">${cm3}</td>
            </tr>`;
        });

        tableHTML += "</tbody></table>";
        popupTableContainer.innerHTML = tableHTML;

        // Show the popup
        document.getElementById("popupOverlay").style.display = "flex";

        } else {
            throw new Error(data.message || "Unknown error occurred");
        }
    })
    .catch(err => {
        console.error("Upload error:", err);
        progressMessage.textContent = `Upload error: ${err.message}`;
    });
});