<?php
require_once("dbconnect.php");

$name = mysqli_real_escape_string($conn, $_POST['name']);
$type = mysqli_real_escape_string($conn, $_POST['type']);
$address = mysqli_real_escape_string($conn, $_POST['address']);

$sql = "INSERT INTO Location (name, type, address)
        VALUES ('$name', '$type', '$address')";

if (!mysqli_query($conn, $sql)) {
    $error = mysqli_error($conn);

    if (strpos($error, 'Only one hub is allowed') !== false) {
        echo "<script>
                alert('There can only be ONE hub location.');
                window.history.back();
              </script>";
        exit();
    }

    if (strpos($error, 'Cannot delete the only hub') !== false) {
        echo "<script>
                alert('You cannot delete the only hub.');
                window.history.back();
              </script>";
        exit();
    }

    die($error);
}

header("Location: show_locations.php");
exit();
