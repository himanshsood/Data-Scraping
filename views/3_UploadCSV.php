        <!-- Third Table: CSV Upload Form -->
        <div class="table-container">
            <h3 class="table-title">CSV Upload</h3>
           <form id="csvUploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="genderSelect">Select Gender:</label>
                    <select id="genderSelect" name="board_type" required>
                        <option value="">Choose gender</option>
                        <option value="men">Male</option>
                        <option value="women">Female</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="csvFile">Choose CSV File:</label>
                    <input type="file" id="csvFile" name="file" accept=".csv" required />
                </div>

                
                <button type="submit" class="btn btn-success">Send to Monday</button>
            </form>

            <!-- Progress & Result Message -->
            <div id="progressContainer" style="display: none; margin-top: 10px;">
                <!-- <progress id="progressBar" value="0" max="100" style="width: 100%;"></progress> -->
                <p id="progressMessage" style="display:none;">Uploading...</p>
            </div>

        </div>
    </div>