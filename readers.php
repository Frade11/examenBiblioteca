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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestiune Cititori - BIBLIO-SYS</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="main-nav">
    <div class="nav-container">
        <a class="brand" href="index.php">BIBLIO<span>SYS</span></a>
        <div class="nav-links">
            <a href="index.php">Carti</a>
            <a href="readers.php" class="active">Cititori</a>
        </div>
    </div>
</nav>

<main class="content-wrapper">
    <header class="page-header">
        <h1>Raport Cititori</h1>
        <a href="edit_reader.php" class="btn btn-add">Adauga Cititor</a>
    </header>

    <section class="filter-section">
        <form class="filter-form">
            <div class="input-group">
                <label for="filter_date">Data inregistrarii:</label>
                <input type="date" id="filter_date" name="filter_date" value="<?= htmlspecialchars($filter_date) ?>">
            </div>
            <div class="button-group">
                <button type="submit" class="btn btn-search">Aplica Filtru</button>
                <a href="readers.php" class="btn btn-reset">Reset</a>
            </div>
        </form>
    </section>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nume Prenume</th>
                    <th>Telefon</th>
                    <th>Email</th>
                    <th>Data Inregistrarii</th>
                    <th class="actions-head">Actiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($readers)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            Nu s-au gasit cititori pentru criteriile selectate.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach($readers as $r): ?>
                <tr>
                    <td><span class="id-badge"><?= $r['ID_cititor'] ?></span></td>
                    <td class="reader-name"><?= htmlspecialchars($r['Nume'] . " " . $r['Prenume']) ?></td>
                    <td class="text-muted"><?= htmlspecialchars($r['Telefon']) ?></td>
                    <td><a href="mailto:<?= htmlspecialchars($r['Email']) ?>" class="email-link"><?= htmlspecialchars($r['Email']) ?></a></td>
                    <td>
                        <span class="date-tag">
                            <?= date('d.m.Y', strtotime($r['Data_inregistrarii'])) ?>
                        </span>
                    </td>
                    <td class="action-buttons">
                        <a href="edit_reader.php?id=<?= $r['ID_cititor'] ?>" class="btn-icon edit">Edit</a>
                        <a href="delete.php?type=reader&id=<?= $r['ID_cititor'] ?>" 
                           class="btn-icon delete" 
                           onclick="return confirm('Sigur doresti sa stergi acest cititor?')">Sterge</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>