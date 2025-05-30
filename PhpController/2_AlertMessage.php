<?php
    $message = '';
    if (isset($_SESSION['message'])) {
        $message = addslashes($_SESSION['message']);
        echo "
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alertBox = document.getElementById('custom-alert');
                alertBox.innerText = '{$message}';
                alertBox.style.display = 'block';

                // Hide after 3 seconds
                setTimeout(() => {
                    alertBox.style.display = 'none';
                }, 3000);
            });
        </script>
        ";
        unset($_SESSION['message']);
    }
?>