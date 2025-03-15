<?php
require __DIR__ . '/../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $name = trim($_POST['name']);

    if (empty($name)) {
        echo json_encode(["success" => false, "message" => "Module name cannot be empty!"]);
        exit;
    }

    try {
        $stmt = $conn->prepare("UPDATE subjects SET name = :name WHERE id = :id");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        echo json_encode(["success" => true, "id" => $id, "name" => $name]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Error updating module: " . $e->getMessage()]);
    }
}
