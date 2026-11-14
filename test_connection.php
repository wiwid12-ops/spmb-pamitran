
<?php

require_once 'config/database.php';

$sql = "SELECT id, kode_prodi, nama_prodi, jenjang
        FROM program_studi
        ORDER BY id ASC";

$stmt = $pdo->prepare($sql);

$stmt->execute();

$data = $stmt->fetchAll();

?>

<h2>Data Program Studi</h2>

<table border="1" cellpadding="10">

    <tr>
        <th>ID</th>
        <th>Kode</th>
        <th>Program Studi</th>
        <th>Jenjang</th>
    </tr>

    <?php foreach ($data as $row): ?>

        <tr>

            <td><?= htmlspecialchars($row['id']) ?></td>

            <td><?= htmlspecialchars($row['kode_prodi']) ?></td>

            <td><?= htmlspecialchars($row['nama_prodi']) ?></td>

            <td><?= htmlspecialchars($row['jenjang']) ?></td>

        </tr>

    <?php endforeach; ?>

</table>
