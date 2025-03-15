<body class="bg-light d-flex justify-content-center align-items-center vh-100">
    <div class="card shadow-sm p-4" style="max-width: 400px; width: 100%;">
        <h2 class="text-center mb-3">Admin Login</h2>

        <!-- Hiển thị thông báo lỗi phía trên form -->
        <?php if (!empty($errors['username']) || !empty($errors['password'])) : ?>
            <div class="alert alert-danger text-center">
                <?= !empty($errors['username']) ? $errors['username'] : $errors['password'] ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control <?= !empty($errors['username']) ? 'is-invalid' : '' ?>" placeholder="Username" value="<?= htmlspecialchars($username ?? '') ?>">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" placeholder="Password">
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="text-center mt-3">
            Don't have an account? <a href="register.php" class="text-decoration-none">Register</a>
        </p>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            let username = document.querySelector('input[name="username"]');
            let password = document.querySelector('input[name="password"]');
            let valid = true;

            if (username.value.trim() === '') {
                username.classList.add('is-invalid');
                valid = false;
            } else {
                username.classList.remove('is-invalid');
            }

            if (password.value.trim() === '') {
                password.classList.add('is-invalid');
                valid = false;
            } else {
                password.classList.remove('is-invalid');
            }

            if (!valid) event.preventDefault();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>