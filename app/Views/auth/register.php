<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - POS Skripsi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .register-container { margin-top: 80px; max-width: 450px; }
    </style>
</head>
<body>
<div class="container register-container">
    <div class="card shadow">
        <div class="card-body">
            <h3 class="text-center mb-4">Daftar Akun Baru</h3>
            
            <form action="/register" method="post">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Daftar Sekarang</button>
            </form>
            <p class="text-center mt-3">Sudah punya akun? <a href="/login">Login</a></p>
        </div>
    </div>
</div>
</body>
</html>