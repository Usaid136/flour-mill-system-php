    <nav class="navbar navbar-expand-lg navbar-dark text-dark bg-warning sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand text-dark" href="../index.php">Flour Mill Management</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <span class="nav-link text-dark"><i class="fa fa-user me-1"></i> Admin</span>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-outline-danger ms-2" href="<?= BASE_URL ?>auth/logout.php"><i class="fa fa-sign-out-alt me-1"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>