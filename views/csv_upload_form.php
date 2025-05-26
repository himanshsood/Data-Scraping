<style>
    .csv-upload-form {
        width: 100%;
        max-width: 100%;
        margin: 30px auto;
        background-color: #f8f9fa;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 25px;
        font-family: Arial, sans-serif;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
    }

    .csv-upload-form form {
        width: 100%;
        max-width: 800px;
        display: flex;
        justify-content:center;
        gap: 20px;
    }

    .workflow-indicator {
        display: flex;
        justify-content: space-between;
        padding: 10px 15px;
        background-color: #e9ecef;
        border-radius: 6px;
        font-weight: 500;
    }

    .workflow-step {
        padding: 5px 10px;
        border-radius: 5px;
        background-color: #dee2e6;
        color: #495057;
        transition: background-color 0.3s;
    }

    .workflow-step.completed {
        background-color: #28a745;
        color: white;
    }

    .input-group {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        justify-content:center;
    }

    .input-group select,
    .input-group button {
        padding: 8px 12px;
        font-size: 14px;
        border-radius: 5px;
        border: 1px solid #ced4da;
    }

    .input-group select:focus,
    .input-group button:focus {
        outline: none;
        border-color: #80bdff;
        box-shadow: 0 0 0 2px rgba(0,123,255,.25);
    }

    #file_button {
        cursor: pointer;
    }

    #file_name {
        font-weight: bold;
        min-width: 150px;
    }

    .btn {
        border: none;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-info:hover {
        background-color: #138496;
    }

    .btn-completed {
        background-color: #6c757d;
        color: white;
        cursor: default;
    }

    .btn-completed:hover {
        background-color: #6c757d;
    }
</style>


<div class="csv-upload-form">
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="job_name" id="current_job_name" 
               value="CSV UPLOAD"
               placeholder="Enter job name..." required style="display:none;">

        <?php if (!empty($_SESSION['current_job']['job_name'])): ?>
        <div class="workflow-indicator">
            <span class="workflow-step <?php echo $_SESSION['current_job']['data_fetched'] ? 'completed' : ''; ?>">
                Data Fetch
            </span>
            <span class="workflow-step <?php echo $_SESSION['current_job']['csv_uploaded'] ? 'completed' : ''; ?>">
                CSV Upload
            </span>
            <span class="workflow-step <?php echo $_SESSION['current_job']['sent_to_monday'] ? 'completed' : ''; ?>">
                Monday Integration
            </span>
        </div>
        <?php endif; ?>

        <div class="input-group">
            <input type="file" id="csv_upload" name="csv_file" accept=".csv" required style="display:none;">
            <!-- Gender Dropdown Selector -->
            <select name="gender" id="gender_select" required>
                <option value="">Gender</option>
                <option value="Men">Men</option>
                <option value="Women">Women</option>
            </select>
            <button type="button" id="file_button" class="btn" style="background-color: #ff6600; color: white;">
                Choose File
            </button>
            <span id="file_name" class="ms-2" style="color:<?= $File === null ? 'red' : 'black'; ?>">
                <?= $File === null ? 'No File Selected' : htmlspecialchars($File); ?>
            </span>
            <button type="submit" name="action" value="send_to_monday_current"
                class="btn <?php echo $_SESSION['current_job']['sent_to_monday'] ? 'btn-completed' : 'btn-info'; ?>">
                Send to Monday
            </button>
        </div>
    </form>
</div>

<script> 
// File input script
const fileInput = document.getElementById('csv_upload');
const fileButton = document.getElementById('file_button');
const fileNameSpan = document.getElementById('file_name');

fileButton.onclick = () => fileInput.click();
fileInput.onchange = (e) => {
    const fileName = e.target.files[0]?.name || 'No file selected';
    fileNameSpan.textContent = fileName;
    fileNameSpan.style.color = 'black';
};
</script>