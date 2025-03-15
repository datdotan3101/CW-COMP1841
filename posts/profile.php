<?php
session_start();
require '../config.php'; // Kết nối database

// Kiểm tra nếu user chưa đăng nhập hoặc không phải user thường
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

// Lấy thông tin user từ database
$user_id = $_SESSION['user_id'];
$sql = "SELECT username, email, role FROM users WHERE id = :id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);

    // Kiểm tra xem username hoặc email đã tồn tại chưa
    $check_sql = "SELECT id FROM users WHERE (username = :username OR email = :email) AND id != :id";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bindParam(":username", $new_username, PDO::PARAM_STR);
    $check_stmt->bindParam(":email", $new_email, PDO::PARAM_STR);
    $check_stmt->bindParam(":id", $user_id, PDO::PARAM_INT);
    $check_stmt->execute();

    if ($check_stmt->rowCount() > 0) {
        $_SESSION['error'] = "Username hoặc email đã được sử dụng!";
    } else {
        if (!empty($_POST['password'])) {
            $new_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET username = :username, email = :email, password = :password WHERE id = :id";
            $stmt = $conn->prepare($update_sql);
            $stmt->bindParam(":password", $new_password, PDO::PARAM_STR);
        } else {
            $update_sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
            $stmt = $conn->prepare($update_sql);
        }
        $stmt->bindParam(":username", $new_username, PDO::PARAM_STR);
        $stmt->bindParam(":email", $new_email, PDO::PARAM_STR);
        $stmt->bindParam(":id", $user_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $_SESSION['username'] = $new_username; // Cập nhật session
            $_SESSION['success'] = "Cập nhật thành công!";
            header("Refresh:0"); // Load lại trang
        } else {
            $_SESSION['error'] = "Lỗi cập nhật!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa thông tin tài khoản</title>
    <link rel="stylesheet" href="../styles.css"> <!-- Link file CSS nếu có -->
</head>

<body>
    <h2>Thông tin tài khoản</h2>
    <?php if (isset($_SESSION['success'])) {
        echo "<p style='color: green;'>" . $_SESSION['success'] . "</p>";
        unset($_SESSION['success']);
    } ?>
    <?php if (isset($_SESSION['error'])) {
        echo "<p style='color: red;'>" . $_SESSION['error'] . "</p>";
        unset($_SESSION['error']);
    } ?>

    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label>Mật khẩu mới (nếu muốn đổi):</label>
        <input type="password" name="password" placeholder="Nhập mật khẩu mới">

        <button type="submit">Cập nhật</button>
    </form>
</body>

</html>