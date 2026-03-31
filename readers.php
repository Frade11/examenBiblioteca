<?php 
require 'db.php'; 

$filter_date = $_GET['filter_date'] ?? '';

if ($filter_date) {
    $stmt = $conn->prepare("SELECT * FROM cititori WHERE Data_inregistrarii = ?");
    $stmt->bind_param("s", $filter_date);
} else {
    $stmt = $conn->prepare("SELECT * FROM cititori");
}

$stmt->execute();
$readers = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Gestiune Cititori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">BIBLIO-SYS</a>
        <div class="navbar-nav">
            <a class="nav-link" href="index.php">Cărți</a>
            <a class="nav-link active" href="readers.php">Cititori</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>👥 Raport Cititori</h2>
        <a href="edit_reader.php" class="btn btn-success">Adaugă Cititor</a>
    </div>

    <div class="card p-3 mb-4 border-0 shadow-sm">
        <form class="row g-3 align-items-center">
            <div class="col-auto">
                <label>Filtrează după data înregistrării:</label>
            </div>
            <div class="col-auto">
                <input type="date" name="filter_date" class="form-control" value="<?= htmlspecialchars($filter_date) ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Aplică Filtru</button>
                <a href="readers.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>

    <div class="card border-0 shadow-sm">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nume Prenume</th>
                    <th>Telefon</th>
                    <th>Email</th>
                    <th>Data Înregistrării</th>
                    <th class="text-center">Acțiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($readers as $r): ?>
                <tr>
                    <td><?= $r['ID_cititor'] ?></td>
                    <td class="fw-bold"><?= htmlspecialchars($r['Nume'] . " " . $r['Prenume']) ?></td>
                    <td><?= htmlspecialchars($r['Telefon']) ?></td>
                    <td><?= htmlspecialchars($r['Email']) ?></td>
                    <td><?= date('d.m.Y', strtotime($r['Data_inregistrarii'])) ?></td>
                    <td class="text-center">
                        <a href="edit_reader.php?id=<?= $r['ID_cititor'] ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                        <a href="delete.php?type=reader&id=<?= $r['ID_cititor'] ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Sigur dorești să ștergi acest cititor?')">Șterge</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>