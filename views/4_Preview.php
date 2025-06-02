<div id="popupOverlay" style="
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5);
  backdrop-filter: blur(5px);
  z-index: 99999;
  display: none;
  justify-content: center;
  align-items: center;
">
  <div style="
    background: white;
    padding: 20px;
    border-radius: 8px;
    width: 90%;
    max-width: 1000px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 0 10px rgba(0,0,0,0.25);
  ">
    <h2 style="margin-top: 0;">Preview Data</h2>
    <br>
    <p style="
      margin-bottom: 20px;
      color: #d9534f;
      font-weight: bold;
      font-size: 16px;
      margin:auto;
      text-align:center;
    ">
      ⚠️ Once you click submit, changes will be applied and cannot be rolled back!
    </p>
    <br>
    
    <div id="popupTableContainer"></div>

    <div style="margin-top: 20px; text-align: right;">
      <button onclick="document.getElementById('popupOverlay').style.display='none'" style="
        padding: 8px 16px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 4px;
        margin-right: 10px;
        cursor: pointer;
      ">Cancel</button>
      <button onclick="submitPreviewData()" style="
        padding: 8px 16px;
        background: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
      ">Upload</button>
    </div>
  </div>
</div>
