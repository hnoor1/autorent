<?php
session_start();
include('../config.php');

if (empty($_SESSION['logged_in']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$paring = "SELECT * FROM cars ORDER BY id DESC";
$valjund = mysqli_query($yhendus, $paring);
?>

<?php include('admin_header.php'); ?>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>Autod</h2>
            <p class="text-muted">Halda autorendi autode nimekirja.</p>
        </div>

        <a href="add_car.php" class="btn btn-dark btn-sm">Lisa auto</a>
    </div>

    <table class="table table-striped table-hover align-middle">
        <thead>
            <tr>
                <th>Pilt</th>
                <th>Auto</th>
                <th>Mootor</th>
                <th>Kütus</th>
                <th>Hind</th>
                <th>Kirjeldus</th>
                <th>Tegevused</th>
            </tr>
        </thead>

        <tbody>
        <?php while($rida = mysqli_fetch_assoc($valjund)) { ?>
            <tr>
<td>
    <img src="https://loremflickr.com/150/90/<?= urlencode($rida['mark']); ?>"
         style="width:150px;height:90px;object-fit:cover;border-radius:4px;">
</td>
                <td>
                    <strong><?= htmlspecialchars($rida['mark']); ?> <?= htmlspecialchars($rida['model']); ?></strong><br>
                    <small><?= htmlspecialchars($rida['year']); ?></small>
                </td>

                <td><?= htmlspecialchars($rida['engine']); ?></td>
                <td><?= htmlspecialchars($rida['fuel']); ?></td>
                <td><?= htmlspecialchars($rida['price']); ?> € / päev</td>

                <td><?= htmlspecialchars($rida['description']); ?></td>

                <td>
                    <a href="muuda.php?editid=<?= (int)$rida['id']; ?>" class="btn btn-outline-primary btn-sm">Muuda</a>
                    <a href="kustuta.php?delid=<?= (int)$rida['id']; ?>" 
                       class="btn btn-outline-danger btn-sm"
                       onclick="return confirm('Kas oled kindel, et soovid auto kustutada?');">
                        Kustuta
                    </a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <div class="text-end">
        <a href="add_car.php" class="btn btn-dark btn-sm">Lisa auto</a>
    </div>

</div>

</body>
</html>
