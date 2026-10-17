<?php
session_start();
if (isset($_SESSION['login'])) {
    header("Location: ../admin/index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Monstera Cafe</title>
    <link rel="stylesheet" href="../assets/style.css">
    <link rel="stylesheet" href="../admin/assets/admin.css">
    <style>
        body.login-body {
            background: #1d3b2f;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }
        .login-card {
            background: #ffffff;
            padding: 40px;
            border-radius: 16px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        .login-card h2 {
            margin-top: 0;
            color: #1d3b2f;
            text-align: center;
            font-weight: 800;
        }
        .login-card p {
            text-align: center;
            color: #6d7e77;
            font-size: 0.9rem;
            margin-bottom: 25px;
        }
        .alert-error {
            background: #fde8e8;
            color: #c53030;
            padding: 10px;
            border-radius: 6px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            text-align: center;
        }
        /* Styling untuk pembungkus input password dan ikon mata */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }
        .password-wrapper input {
            width: 100%;
            padding-right: 45px; /* Memberikan ruang untuk ikon mata */
        }
        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            cursor: pointer;
            color: #6d7e77;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .toggle-password:hover {
            color: #1d3b2f;
        }
    </style>
</head>
<body class="login-body">

    <div class="login-card">
        <h2>Admin Login</h2>
        <p>Silakan masuk ke panel admin Monstera Eatery</p>

        <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal'): ?>
            <div class="alert-error">Username atau Password salah!</div>
        <?php endif; ?>

        <form action="proses_login.php" method="POST" class="admin-form">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required autocomplete="off">
            </div>

            <div class="form-group" style="margin-bottom: 25px;">
                <label>Password</label>
                <div class="password-wrapper">
                    <input type="password" name="password" id="passwordInput" placeholder="Masukkan password" required>
                    <button type="button" class="toggle-password" id="togglePasswordBtn" aria-label="Tampilkan password">
                        <!-- Icon Mata Tertutup (Default) -->
                        <svg id="eyeIcon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                            <line x1="1" y1="1" x2="23" y2="23"></line>
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-primary" style="width: 100%; background: #1d3b2f; color: #fff; padding: 12px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer;">MASUK</button>
        </form>
    </div>

    <!-- Script JavaScript untuk Fitur Lihat Password (Mata) -->
    <script>
        const passwordInput = document.getElementById('passwordInput');
        const togglePasswordBtn = document.getElementById('togglePasswordBtn');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePasswordBtn.addEventListener('click', function () {
            // Ubah tipe input antara password dan text
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Ganti ikon SVG mata (terbuka / tertutup)
            if (type === 'text') {
                // Ikon Mata Terbuka
                eyeIcon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            } else {
                // Ikon Mata Tertutup
                eyeIcon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            }
        });
    </script>
</body>
</html>