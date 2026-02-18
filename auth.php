<?php
session_start();

if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

// ROLE CHECK FUNCTION
function require_role($allowed_roles = []) {
    if (!in_array($_SESSION['role'], $allowed_roles)) {
        die("<h2 style='color:red;text-align:center;'>Access Denied</h2>
             <p style='text-align:center;'>You do not have permission to access this page.</p>");
    }
}
