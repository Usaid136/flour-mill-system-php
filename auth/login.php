<?php
session_start();
include "../includes/db.php";
include "../includes/functions.php";


?>

<!DOCTYPE html>
<html>

<head>

    <title>Login - Flour Mill System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background: linear-gradient(95deg, yellow, orange);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial;
        }

        button {
            background: linear-gradient(95deg, yellow, orange);
        }
    </style>

</head>

<body>

    <!-- Flash Messages -->
    <?php if ($msg = getFlash('success')): ?>
        <div class="alert mt-3 alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x zindex-tooltip" role="alert" style="min-width:300px;">
            <b>Success! </b><?= e($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($msg = getFlash('error')): ?>
        <div class="alert mt-3 alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x zindex-tooltip" role="alert" style="min-width:300px;">
            <b>Error! </b><?= e($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>


    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col col-md-6">
                <div class="card">
                    <div class="card-body py-4">
                        <h3 class="fw-bold">Flour Mill System</h3>
                        <h6 class="text-muted">Login to continue</h6>
                        <form method="post" action="login_process.php">
                            <div class="mb-3">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter your email" id="staticEmail">
                            </div>
                            <div class="mb-3">
                                <label for="pass" class="col-sm-2 col-form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Enter your password" id="pass">
                            </div>
                            <!-- Remember me -->
                            <button type="submit" class="btn w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/script.js"></script>
</body>

</html>