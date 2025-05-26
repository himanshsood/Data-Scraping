<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Golf Rankings Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h2 class="mb-4">Update History</h2>
    <div class="table-responsive">
      <table class="table table-bordered" id="historyTable">
        <thead>
          <tr>
            <th>Run ID</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="historyTableBody">
          <!-- JS dynamically fills this -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    const STATUS_MAP = {
      0: { label: 'Pending', color: 'secondary' },
      1: { label: 'Initializing', color: 'info' },
      2: { label: 'Fetching', color: 'primary' },
      3: { label: 'Merging', color: 'primary' },
      4: { label: 'Uploading', color: 'warning' },
      5: { label: 'Completed', color: 'success' },
      6: { label: 'Failed', color: 'danger' },
      7: { label: 'Terminated', color: 'dark' }
    };

    function loadUpdateHistory() {
      fetch('/api/updates/recent')
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById('historyTableBody');
          tbody.innerHTML = '';
          data.forEach(run => {
            const status = STATUS_MAP[run.status] || { label: 'Unknown', color: 'secondary' };
            const row = `
              <tr>
                <td>${run.id}</td>
                <td>${run.trigger_type}</td>
                <td><span class="badge bg-${status.color}">${status.label}</span></td>
                <td>
                  ${run.status === 6 || run.status === 7 ? `
                    <button class="btn btn-sm btn-warning me-1" onclick="retryUpdate(${run.id})">Retry</button>
                    <button class="btn btn-sm btn-danger" onclick="rollbackUpdate(${run.id})">Rollback</button>
                  ` : ''}
                </td>
              </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
          });
        });
    }

    function rollbackUpdate(runId) {
      fetch(`/api/updates/rollback/${runId}`, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
          alert(data.status === 'success' ? 'Rollback started.' : 'Rollback failed.');
          loadUpdateHistory();
        });
    }

    function retryUpdate(runId) {
      fetch(`/api/updates/retry/${runId}`, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            alert('Retry started.');
            loadUpdateHistory();
          } else {
            alert('Retry failed.');
          }
        });
    }

    document.addEventListener('DOMContentLoaded', loadUpdateHistory);
  </script>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Golf Rankings Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-4">
    <h2 class="mb-4">Update History</h2>
    <div class="table-responsive">
      <table class="table table-bordered" id="historyTable">
        <thead>
          <tr>
            <th>Run ID</th>
            <th>Type</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="historyTableBody">
          <!-- JS dynamically fills this -->
        </tbody>
      </table>
    </div>
  </div>

  <script>
    const STATUS_MAP = {
      0: { label: 'Pending', color: 'secondary' },
      1: { label: 'Initializing', color: 'info' },
      2: { label: 'Fetching', color: 'primary' },
      3: { label: 'Merging', color: 'primary' },
      4: { label: 'Uploading', color: 'warning' },
      5: { label: 'Completed', color: 'success' },
      6: { label: 'Failed', color: 'danger' },
      7: { label: 'Terminated', color: 'dark' }
    };

    function loadUpdateHistory() {
      fetch('/api/updates/recent')
        .then(res => res.json())
        .then(data => {
          const tbody = document.getElementById('historyTableBody');
          tbody.innerHTML = '';
          data.forEach(run => {
            const status = STATUS_MAP[run.status] || { label: 'Unknown', color: 'secondary' };
            const row = `
              <tr>
                <td>${run.id}</td>
                <td>${run.trigger_type}</td>
                <td><span class="badge bg-${status.color}">${status.label}</span></td>
                <td>
                  ${run.status === 6 || run.status === 7 ? `
                    <button class="btn btn-sm btn-warning me-1" onclick="retryUpdate(${run.id})">Retry</button>
                    <button class="btn btn-sm btn-danger" onclick="rollbackUpdate(${run.id})">Rollback</button>
                  ` : ''}
                </td>
              </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
          });
        });
    }

    function rollbackUpdate(runId) {
      fetch(`/api/updates/rollback/${runId}`, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
          alert(data.status === 'success' ? 'Rollback started.' : 'Rollback failed.');
          loadUpdateHistory();
        });
    }

    function retryUpdate(runId) {
      fetch(`/api/updates/retry/${runId}`, { method: 'POST' })
        .then(res => res.json())
        .then(data => {
          if (data.status === 'success') {
            alert('Retry started.');
            loadUpdateHistory();
          } else {
            alert('Retry failed.');
          }
        });
    }

    document.addEventListener('DOMContentLoaded', loadUpdateHistory);
  </script>
</body>
</html>