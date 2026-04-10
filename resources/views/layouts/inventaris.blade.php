<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Inventaris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f4f7fe;
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            width: 260px;
            height: 100vh;
            position: fixed;
            background: #30419b;
            color: white;
            transition: all 0.3s;
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.7);
            padding: 12px 20px;
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
            border-left: 4px solid #fff;
        }

        .sidebar .nav-link i {
            margin-right: 12px;
            font-size: 1.2rem;
        }

        .main-content {
            margin-left: 260px;
            padding: 20px;
        }

        .top-nav {
            background: white;
            padding: 15px 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="p-4 text-center">
            <h4 class="fw-bold">INVENTARIS</h4>
            <hr>
        </div>
        <nav class="nav flex-column">
            <small class="px-4 text-uppercase opacity-50">Menu</small>

            <a class="nav-link {{ Request::is('*/dashboard') ? 'active' : '' }}"
                href="{{ auth()->user()->role == 'admin' ? url('/admin/dashboard') : url('/staff/dashboard') }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            @if (auth()->user()->role == 'admin')
                <small class="px-4 mt-3 text-uppercase opacity-50">Item Data</small>

                <a class="nav-link {{ Request::is('admin/categories*') ? 'active' : '' }}"
                    href="{{ route('categories.index') }}">
                    <i class="bi bi-tags"></i> Categories
                </a>
                <a class="nav-link {{ Request::is('admin/items*') ? 'active' : '' }}" href="{{ route('items.index') }}">
                    <i class="bi bi-box-seam"></i> Items
                </a>
                <small class="px-4 mt-3 text-uppercase opacity-50">Accounts</small>
                <div class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ Request::is('admin/users*') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#userDropdown">
                        <span><i class="bi bi-people"></i> Users</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ Request::is('admin/users*') ? 'show' : '' }}" id="userDropdown"
                        style="background: rgba(0,0,0,0.1)">
                        <a class="nav-link ps-5 small {{ request('role') == 'admin' ? 'text-white fw-bold' : '' }}"
                            href="{{ route('users.index', ['role' => 'admin']) }}">
                            <i class="bi bi-circle small"></i> Admin
                        </a>
                        <a class="nav-link ps-5 small {{ request('role') == 'staff' ? 'text-white fw-bold' : '' }}"
                            href="{{ route('users.index', ['role' => 'staff']) }}">
                            <i class="bi bi-circle small"></i> Staff
                        </a>
                    </div>
                </div>
            @else
                <small class="px-4 mt-3 text-uppercase opacity-50">Item Data</small>
                <a class="nav-link {{ Request::is('items*') ? 'active' : '' }}" href="{{ route('items.index') }}">
                    <i class="bi bi-box-seam"></i> Items
                </a>
                <a class="nav-link {{ Request::is('staff/lendings*') ? 'active' : '' }}"
                    href="{{ route('lendings.index') }}">
                    <i class="bi bi-arrow-left-right"></i> Lending
                </a>
                <small class="px-4 mt-3 text-uppercase opacity-50">User</small>
                <div class="nav-item">
                    <a class="nav-link d-flex justify-content-between align-items-center {{ Request::is('profile/edit') ? 'active' : '' }}"
                        data-bs-toggle="collapse" href="#userStaffDropdown">
                        <span><i class="bi bi-people"></i> Users</span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ Request::is('profile/edit') ? 'show' : '' }}" id="userStaffDropdown"
                        style="background: rgba(0,0,0,0.1)">
                        <a class="nav-link ps-5 small {{ Request::is('profile/edit') ? 'text-white fw-bold' : '' }}"
                            href="{{ route('profile.edit') }}">
                            <i class="bi bi-circle small"></i> Edit
                        </a>
                    </div>
                </div>
            @endif

        </nav>
    </div>
    <div class="main-content">
        <div class="top-nav">
            <div class="d-flex align-items-center">
                <i class="bi bi-list fs-4 me-3"></i>
                <h5 class="m-0">Welcome Back, {{ auth()->user()->name }}</h5>
            </div>
            <div class="dropdown">
                <button class="btn border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle fs-5 me-2"></i> {{ auth()->user()->name }}
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item text-danger" type="submit">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @yield('content')

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
