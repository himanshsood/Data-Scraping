function submitPreviewData() {
    const csvPath = localStorage.getItem("last_csv_path");
    const csvType = localStorage.getItem("last_csv_type");

    if (!csvPath || !csvType) {
        alert("❌ Missing CSV path or type. Please upload the CSV again.");
        return;
    }

    const formData = new FormData();
    formData.append("filepath", csvPath);
    formData.append("board_type", csvType);

    fetch("http://3.234.76.112:5000/api/updates/csv/confirm", {
        method: "POST",
        body: formData
    })
    .then(async res => {
        const contentType = res.headers.get("content-type") || "";
        if (!contentType.includes("application/json")) {
            throw new Error("Invalid server response");
        }

        const data = await res.json();

        if (!res.ok || data.status !== "success") {
            throw new Error(data.message || "Unknown error during upload");
        }

      
        window.location.reload() ; 
    })
    .catch(err => {
        alert("❌ Error in uploading data: " + err.message);
    });
}
