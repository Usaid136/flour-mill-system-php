<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Flour Mill Management System</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="../assets/style.css">

    <style>
        body {
            overflow-x: hidden;
        }

        .content {
            width: 100%;
            padding: 20px;
        }

        @media (max-width: 768px) {
            #sidebar-wrapper {
                width: 100%;
                position: relative;
            }
        }


        #sidebar-wrapper .list-group-item:hover {
            background-color: #ffc107;
            color: white;
        }

        #sidebar-wrapper .list-group-item {
            background-color: #ffc107;
            color: black;
        }

        #sidebar-wrapper {
            background-color: #ffc107;
        }

        .alert{
            margin-top: 60px;
        }
        

    </style>
</head>

<body>
    <!-- Flash Messages -->
    <?php if ($msg = getFlash('success')): ?>
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x zindex-tooltip" role="alert" style="min-width:300px;">
            <b>Success! </b><?= e($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if ($msg = getFlash('error')): ?>
        <div class="alert alert-error alert-danger alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x zindex-tooltip" role="alert" style="min-width:300px;">
            <b>Error! </b><?= e($msg); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <!-- Navbar -->
    <?php include "navbar.php"; ?>

    <div class="d-flex">
        <?php include 'sidebar.php'; ?>
        <div class="content">