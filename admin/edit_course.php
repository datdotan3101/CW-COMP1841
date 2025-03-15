<?php
require __DIR__ . '/../includes/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "Invalid course ID."]);
    exit;
}

$course_id = $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT * FROM subjects WHERE id = :id");
    $stmt->bindParam(':id', $course_id, PDO::PARAM_INT);
    $stmt->execute();
    $course = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$course) {
        echo json_encode(["success" => false, "message" => "Module not found."]);
    } else {
        echo json_encode(["success" => true, "id" => $course['id'], "name" => $course['name']]);
    }
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Query error: " . $e->getMessage()]);
}
