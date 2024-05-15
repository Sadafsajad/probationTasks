<?php
// Include database connection
include_once 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['addChecklist'])) {
        // Retrieve form data for checklists table
        $eventTitle = $_POST['eventTitle'];
        $startDate = $_POST['startDate'];
        $endDate = $_POST['endDate'];
        $repeatOption = $_POST['repeatOption'];
        $description = $_POST['description'];
        $status = $_POST['status'];
        $priority = $_POST['priority'];
        $category = $_POST['category'];
        $comment = $_POST['comment'];
        $accessLevel = $_POST['accessLevel'];
        $colorPicker = $_POST['colorPicker'];
        $subChecklist = isset($_POST['subChecklist']) ? 1 : 0; // Checkbox value

        try {
            // Prepare SQL statement for checklists table
            $stmt = $pdo->prepare("INSERT INTO checklists (title, date_from, date_to, status, priority, category, comment, accessLevel, description, created_at, updated_at, is_active)
                               VALUES (:eventTitle, :startDate, :endDate, :status, :priority, :category, :comment, :accessLevel, :description, NOW(), NOW(), 1)");

            // Bind parameters for checklists table
            $stmt->bindParam(':eventTitle', $eventTitle);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':priority', $priority);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':comment', $comment);
            $stmt->bindParam(':accessLevel', $accessLevel);
            $stmt->bindParam(':description', $description);
            // $stmt->bindParam(':colorPicker', $colorPicker);

            // Execute the prepared statement for checklists table
            $stmt->execute();

            // Get the ID of the inserted checklist entry
            $checklistId = $pdo->lastInsertId();

            // If subChecklist checkbox is checked, insert data into sub_checklists table
            if ($subChecklist) {
                // Retrieve form data for sub_checklists table
                $subChecklistName = $_POST['subChecklistname'];
                $subChecklistDate = $_POST['subChecklistdate'];
                $subChecklistDescription = $_POST['subChecklistDescription'];
                $subChecklistStatus = $_POST['subChecklistStatus'];
                $subChecklistPriority = $_POST['subChecklistPriority'];
                $subChecklistCategory = $_POST['subChecklistCategory'];
                $subChecklistComment = $_POST['subChecklistComment'];
                // var_dump($subChecklistName, $subChecklistDate, $subChecklistDescription);
                // die();
                // // Prepare SQL statement for sub_checklists table
                $stmt = $pdo->prepare("INSERT INTO sub_checklists (title, sub_checklist_date, description, status, priority, category, comment, created_by, updated_by, checklist_id, is_active)
                               VALUES (:subChecklistName, :subChecklistDate, :subChecklistDescription, :subChecklistStatus, :subChecklistPriority, :subChecklistCategory, :subChecklistComment, NOW(), NOW(), :checklistId, 1)");

                // Bind parameters for sub_checklists table
                $stmt->bindParam(':subChecklistName', $subChecklistName);
                $stmt->bindParam(':subChecklistDate', $subChecklistDate);
                $stmt->bindParam(':subChecklistDescription', $subChecklistDescription);
                $stmt->bindParam(':subChecklistStatus', $subChecklistStatus);
                $stmt->bindParam(':subChecklistPriority', $subChecklistPriority);
                $stmt->bindParam(':subChecklistCategory', $subChecklistCategory);
                $stmt->bindParam(':subChecklistComment', $subChecklistComment);
                $stmt->bindParam(':checklistId', $checklistId);

                // Execute the prepared statement for sub_checklists table
                $stmt->execute();
            }

            // Redirect to a success page or display a success message
            header("Location: /probationTasks/");
            exit();
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    }
    // Check if the form is for adding leave
    if (isset($_POST['addLeave'])) {
        // Retrieve form data for leaves table
        $leaveTitle = $_POST['leaveTitle'];
        $startDate = $_POST['leaveStartDate'];
        $endDate = $_POST['leaveEndDate'];
        $category = $_POST['leaveCategory'];

        try {
            // Prepare SQL statement for leaves table
            $stmt = $pdo->prepare("INSERT INTO leaves (title, start_date, end_date, category, created_at, is_active)
                                   VALUES (:leaveTitle, :startDate, :endDate, :category, NOW(), 1)");

            // Bind parameters for leaves table
            $stmt->bindParam(':leaveTitle', $leaveTitle);
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':endDate', $endDate);
            $stmt->bindParam(':category', $category);

            // Execute the prepared statement for leaves table
            $stmt->execute();

            // Redirect to a success page or display a success message
            header("Location:  /probationTasks/");
            exit();
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
        }
    }
}
?>