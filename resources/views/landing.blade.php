<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris SMK Wikrama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
        }
        .hero-section {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-align: center;
        }
        .card-custom {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .btn-login {
            background-color: #ffffff;
            color: #764ba2;
            font-weight: 600;
            padding: 10px 30px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            background-color: #f0f0f0;
            transform: translateY(-3px);
        }
        .modal-content {
            border-radius: 20px;
            border: none;
        }
        .modal-header {
            background-color: #764ba2;
            color: white;
            border-top-left-radius: 20px;
            border-top-right-radius: 20px;
        }
        .form-control {
            border-radius: 10px;
            padding: 12px;
        }
    </style>
</head>
<body>
    <section class="hero-section">
        <div class="container">
            <img src="https://smkwikrama.sch.id/assets2/img/logo-wk.png" alt="Logo" width="100" class="mb-4">
            <h1 class="display-4 fw-bold">Inventory Management</h1>
            <p class="lead mb-5">Management of incoming and outgoing items at SMK Wikrama Bogor.</p>
            <button type="button" class="btn btn-login btn-lg shadow" data-bs-toggle="modal" data-bs-target="#loginModal">
                Login to System
            </button>
        </div>
    </section>
     <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg">
                <form action="{{ route('login.auth') }}" method="POST">
                    @csrf
                    <div class="modal-header border-0 py-4">
                        <h5 class="modal-title w-100 text-center fw-bold">Selamat Datang</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-4">
                        @if($errors->any())
                            <div class="alert alert-danger border-0 rounded-3 text-center">
                                <strong>Oops!</strong> Gagal login, silakan cek kembali.
                            </div>
                        @endif
                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">EMAIL ADDRESS</label>
                            <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold">PASSWORD</label>
                            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" style="background: #764ba2; border: none; border-radius: 10px;">
                            LOGIN
                        </button>
                    </div>
                    <div class="modal-footer border-0 pb-4 justify-content-center">
                        <small class="text-muted">Inventaris SMK Wikrama &copy; 2026</small>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var myModal = new bootstrap.Modal(document.getElementById('loginModal'));
            myModal.show();
        });
    </script>
    @endif
</body>
</html>
