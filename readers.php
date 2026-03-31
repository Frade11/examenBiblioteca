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
$total = count($readers);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cititori - BIBLIОСYS</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="app-shell">

    <aside class="sidebar">
        <div class="sidebar-brand">
            <a href="index.php" class="logo-text">
                <div class="logo-icon"><img src="logo.png" alt=""></div>
                BIBLIO<span>SYS</span>
            </a>
        </div>

        <p class="sidebar-section-label">Menu</p>

        <a href="index.php" class="nav-item">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            <span>Carti</span>
        </a>
        <a href="readers.php" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
            <span>Cititori</span>
        </a>

        <div class="sidebar-footer">
            <small>BIBLIОСYS</small>
        </div>
    </aside>

    <div class="main-area">

        <header class="topbar">
            <span class="topbar-title">Gestiune Biblioteca</span>
            <div class="topbar-right">
                <span class="topbar-badge"><?= date('d M Y') ?></span>
            </div>
        </header>

        <main class="page-content">

            <div class="page-header">
                <div>
                    <h1>Raport Cititori</h1>
                    <p class="subtitle">Gestionează membrii bibliotecii</p>
                </div>
                <a href="edit_reader.php" class="btn btn-add">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Adaugă Cititor
                </a>
            </div>

            <!-- FILTER -->
            <section class="filter-section">
                <form class="filter-form" method="GET">
                    <div class="input-group">
                        <label for="filter_date">Data înregistrării</label>
                        <input type="date" id="filter_date" name="filter_date" value="<?= htmlspecialchars($filter_date) ?>">
                    </div>
                    <div class="button-group">
                        <button type="submit" class="btn btn-search">Aplică filtru</button>
                        <a href="readers.php" class="btn btn-reset">Resetează</a>
                    </div>
                    <?php if($filter_date): ?>
                        <span class="date-tag">Filtrat: <?= date('d.m.Y', strtotime($filter_date)) ?></span>
                    <?php endif; ?>
                </form>
            </section>

            <div class="table-container">
                <div class="table-topbar">
                    <span class="table-topbar-title">Lista cititori</span>
                    <span class="table-topbar-count"><?= $total ?> înregistrați</span>
                </div>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nume Prenume</th>
                            <th>Telefon</th>
                            <th>Email</th>
                            <th>Data Înreg.</th>
                            <th class="actions-head">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($readers)): ?>
                            <tr><td colspan="6">
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                                    <div>Nu s-au găsit cititori pentru criteriile selectate.</div>
                                </div>
                            </td></tr>
                        <?php endif; ?>

                        <?php foreach($readers as $r): ?>
                        <tr>
                            <td><span class="id-badge">#<?= $r['ID_cititor'] ?></span></td>
                            <td class="reader-name"><?= htmlspecialchars($r['Nume'] . " " . $r['Prenume']) ?></td>
                            <td style="color:var(--text-secondary)"><?= htmlspecialchars($r['Telefon']) ?></td>
                            <td><a href="mailto:<?= htmlspecialchars($r['Email']) ?>" class="email-link"><?= htmlspecialchars($r['Email']) ?></a></td>
                            <td><span class="date-tag"><?= date('d.m.Y', strtotime($r['Data_inregistrarii'])) ?></span></td>
                            <td class="action-buttons">
                                <a href="edit_reader.php?id=<?= $r['ID_cititor'] ?>" class="btn-icon edit">Editează</a>
                                <a href="delete.php?type=reader&id=<?= $r['ID_cititor'] ?>"
                                   class="btn-icon delete"
                                   onclick="return confirm('Sigur dorești să ștergi acest cititor?')">Șterge</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </main>
    </div>
</div>

</body>
</html>
