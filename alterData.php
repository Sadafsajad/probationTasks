<?php
// Include database connection
include_once 'db_connect.php';

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['action'])) {
        // Check the action requested
        $action = $_POST['action'];

        if ($action === 'fetchSubchecklistData') {
            // Handle fetching subchecklist data
            $subchecklistId = $_POST['subchecklist_id'];

            try {
                $stmt = $pdo->prepare("SELECT * FROM sub_checklists WHERE id = :subchecklistId");
                $stmt->bindParam(':subchecklistId', $subchecklistId);
                $stmt->execute();
                $subchecklist = $stmt->fetch(PDO::FETCH_ASSOC);
                // Return subchecklist data as JSON response
                echo json_encode($subchecklist);
            } catch (PDOException $e) {
                // Handle database error
                echo json_encode(['error' => $e->getMessage()]);
            }
        } elseif ($action === 'updateSubchecklist') {
            // return $_POST['subchecklist_id'];
            // Handle updating subchecklist
            $subchecklistId = $_POST['subchecklist_id'];
            $startDate = $_POST['startDate'];
            // Get other form data similarly

            try {
                $stmt = $pdo->prepare("UPDATE sub_checklists SET sub_checklist_date = :startDate WHERE id = :subchecklistId");
                $stmt->bindParam(':startDate', $startDate);
                $stmt->bindParam(':subchecklistId', $subchecklistId);
                $stmt->execute();

                // Check if any rows were affected
                $rowCount = $stmt->rowCount();
                if ($rowCount > 0) {
                    // Data updated successfully
                    echo json_encode(['success' => true]);
                } else {
                    // No rows were affected, data might not have been updated
                    echo json_encode(['error' => 'No rows were affected']);
                }
            } catch (PDOException $e) {
                // Handle database error
                echo json_encode(['error' => $e->getMessage()]);
            }
        } elseif ($action === 'deleteSubchecklist') {
            $subchecklistId = $_POST['subchecklist_id'];

            // Perform deletion in the database
            $stmt = $pdo->prepare("DELETE FROM sub_checklists WHERE id = :subchecklistId");
            $stmt->bindParam(':subchecklistId', $subchecklistId);

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Sublist deleted successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to delete sublist'];
            }

            // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
         elseif ($action === 'deleteAllData') {

            $stmtLeaves = $pdo->prepare("DELETE FROM leaves");
            $successLeaves = $stmtLeaves->execute();

            // Perform deletion in the database for checklists
            $stmtChecklists = $pdo->prepare("DELETE FROM checklists");
            $successChecklists = $stmtChecklists->execute();

            if ($successLeaves && $successChecklists) {
                $response = ['success' => true, 'message' => 'All data deleted successfully'];
            } else {
                $response = ['success' => false, 'message' => 'Failed to delete all data'];
            }

            // Send JSON response
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
         elseif ($action === 'viewSubchecklist') {
            $checklistId = $_POST['checklistId'];

            // Assuming you have a database connection established

            // Prepare and execute the query to fetch sub-checklists
            $stmt = $pdo->prepare("SELECT * FROM sub_checklists WHERE checklist_id = ?");
            $stmt->execute([$checklistId]);

            // Fetch the results
            $subChecklists = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Send the sub-checklists as a JSON response
            echo json_encode($subChecklists);
            
        }
    }
}
?>