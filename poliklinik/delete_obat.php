<?php
session_start();
include 'koneksi.php';

// Check if delete request is received
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id_obat'])) {
    $delete_id_obat = $_POST['delete_id_obat'];

    // Delete obat from the detail_periksa table
    $delete_query = "DELETE FROM detail_periksa WHERE id_obat = ?";
    
    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($mysqli, $delete_query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $delete_id_obat);
        mysqli_stmt_execute($stmt);

        // Check for affected rows
        $delete_result = mysqli_stmt_affected_rows($stmt);

        mysqli_stmt_close($stmt);

        if ($delete_result > 0) {
            // Redirect back to detail_periksa.php
            header("Location: detail_periksa.php?id={$_POST['id_periksa']}");
            exit();
        } else {
            // No rows were affected, log or handle this case
            echo '<div class="alert alert-warning mt-3" role="alert">No obat deleted. Maybe it does not exist.</div>';
        }
    } else {
        // Log the error to a file
        error_log('Failed to delete obat. MySQL Error: ' . mysqli_error($mysqli), 3, 'error.log');

        echo '<div class="alert alert-danger mt-3" role="alert">Gagal menghapus obat. Silakan coba lagi.</div>';
    }
} else {
    echo 'Invalid delete request.';
}
?>
