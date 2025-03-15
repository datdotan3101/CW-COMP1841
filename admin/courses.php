<?php
require __DIR__ . '/../includes/config.php';

$sql = "SELECT * FROM subjects";
$stmt = $conn->prepare($sql);
$stmt->execute();
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; // Nếu không có dữ liệu, trả về mảng rỗng
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module Management</title>
    <link rel="stylesheet" href="../styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">
    <h2 class="text-center mb-4">Module</h2>

    <div class="d-flex justify-content-between mb-3">
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addModuleModal">Add Modules</button>
        <a href="index.php" class="btn btn-secondary">Back</a>
    </div>

    <table class="table table-bordered table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Module</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="moduleList">
            <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $row): ?>
                    <tr id="module-<?= $row['id'] ?>">
                        <td><?= htmlspecialchars($row['id']) ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm" onclick="editModule(<?= $row['id'] ?>)">Edit</button>
                            <a href="#" class="btn btn-danger btn-sm" onclick="confirmDelete(<?= $row['id'] ?>)">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">No modules found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Modal Add Module -->
    <div class="modal fade" id="addModuleModal" tabindex="-1" aria-labelledby="addModuleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModuleLabel">Add New Module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addModuleForm">
                        <div class="mb-3">
                            <label for="moduleName" class="form-label">Module Name:</label>
                            <input type="text" id="moduleName" name="course_name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Module -->
    <div class="modal fade" id="editModuleModal" tabindex="-1" aria-labelledby="editModuleLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModuleLabel">Edit Module</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editModuleForm">
                        <input type="hidden" id="editModuleId" name="id">
                        <div class="mb-3">
                            <label for="editModuleName" class="form-label">Module Name:</label>
                            <input type="text" id="editModuleName" name="name" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editModule(id) {
            fetch(`edit_course.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById("editModuleId").value = data.id;
                        document.getElementById("editModuleName").value = data.name;

                        let editModal = new bootstrap.Modal(document.getElementById("editModuleModal"));
                        editModal.show();
                    } else {
                        alert("Error fetching module data!");
                    }
                })
                .catch(error => console.error("Error:", error));
        }

        document.getElementById("editModuleForm").addEventListener("submit", function(event) {
            event.preventDefault();
            let formData = new FormData(this);

            fetch("update_course.php", {
                    method: "POST",
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`#module-${data.id} td:nth-child(2)`).textContent = data.name;
                        let editModal = bootstrap.Modal.getInstance(document.getElementById("editModuleModal"));
                        editModal.hide();
                    } else {
                        alert("Error updating module!");
                    }
                })
                .catch(error => console.error("Error:", error));
        });
    </script>
</body>

</html>