<?php
// YourController.php

class Apps
{
    public function index()
    {
        include_once 'db_connect.php';

        // Initialize variables
        $checklists = [];
        $leaves = [];

        // Fetch checklist entries and leaves from the database
        try {
            $stmt = $pdo->prepare("SELECT * FROM checklists");
            $stmt->execute();
            $checklists = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $stmt = $pdo->prepare("SELECT * FROM leaves");
            $stmt->execute();
            $leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Handle database error
            echo "Error: " . $e->getMessage();
            exit(); // Stop further execution
        }

        // Load the view
        include 'Views/index.php';
    }
    public function fetchSubchecklistData()
    {
        include_once 'db_connect.php';

        // Fetch subchecklist data based on ID sent via AJAX
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
    }

    public function updateSubchecklist()
    {
        include_once 'db_connect.php';

        // Handle submission of modal form to update subchecklist
        // Receive updated data via AJAX and update the database
        $subchecklistId = $_POST['subchecklist_id'];
        $startDate = $_POST['startDate'];
        // Get other form data similarly

        try {
            $stmt = $pdo->prepare("UPDATE sub_checklists SET startDate = :startDate WHERE id = :subchecklistId");
            $stmt->bindParam(':startDate', $startDate);
            $stmt->bindParam(':subchecklistId', $subchecklistId);
            $stmt->execute();
            // Return success response
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            // Handle database error
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
}

// Route AJAX requests to appropriate methods
if (isset($_POST['action'])) {
    $apps = new Apps();
    if ($_POST['action'] === 'fetchSubchecklistData') {
        $apps->fetchSubchecklistData();
    } elseif ($_POST['action'] === 'updateSubchecklist') {
        $apps->updateSubchecklist();
    }
}

