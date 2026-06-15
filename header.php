<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!doctype html>
<html lang="et">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Autorent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary mb-4">
    <div class="container">

        <a class="navbar-brand" href="/index.php">Autorent</a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="/index.php">Avaleht</a>
                </li>

                <?php if (empty($_SESSION['logged_in'])) { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/register.php">Registreeri</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/login.php">Logi sisse</a>
                    </li>

                <?php } else { ?>

                    <?php if ($_SESSION['role'] === 'admin') { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/admin/index.php">Admin</a>
                        </li>

                    <?php } else { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/my_reservations.php">Minu broneeringud</a>
                        </li>

                    <?php } ?>

                <?php } ?>

            </ul>

            <form class="d-flex me-3" method="get" action="/index.php">
                <input class="form-control me-2"
                       type="search"
                       placeholder="Search"
                       name="otsi">

                <button class="btn btn-outline-success" type="submit">
                    Otsi
                </button>
            </form>

            <?php if (!empty($_SESSION['logged_in'])) { ?>

                <span class="me-3">
                    <?php echo htmlspecialchars($_SESSION['email']); ?>
                </span>

                <a href="/logout.php" class="btn btn-danger">
                    Logi välja
                </a>

            <?php } ?>

        </div>
    </div>
</nav>
