// AUTO-UPDATE FORM STATE 
document.addEventListener('DOMContentLoaded', function() {
    const autoUpdateCheckbox = document.getElementById('enableAutoUpdate');
    if (autoUpdateCheckbox) {
        autoUpdateCheckbox.dispatchEvent(new Event('change'));
    }
});


// SHOW AND HIDE EDIT SCHEDULE FORM !
function showEditForm() {
    document.getElementById('displayView').style.display = 'none';
    document.getElementById('editForm').style.display = 'block';
}

function cancelEdit() {
    document.getElementById('editForm').style.display = 'none';
    document.getElementById('displayView').style.display = 'block';
}


// USING PHP VARIABLES !