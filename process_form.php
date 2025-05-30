<?php
session_start();

// --- AUTH CHECK ---
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('HTTP/1.1 401 Unauthorized');
    exit(json_encode(['success' => false, 'message' => 'Unauthorized']));
}


// --- HANDLE POST REQUESTS ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1) Automatic Schedule
    require './PhpController/6_AutomaticSchedule.php' ; 

    // 2) Manual Update
    require './PhpController/4_StartManualUpdate.php' ; 


    // 3) Polling progress check
    require './PhpController/5_PollingProcessCheck.php' ; 

    // 4) Terminate Process
    require './PhpController/7_TerminateProcess.php' ; 

}