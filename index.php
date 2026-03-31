<?php 
require 'db.php'; 

$search = $_GET['search'] ?? '';
$search_param = "%$search%";

$stmt = $conn->prepare("SELECT * FROM carti WHERE Titlu LIKE ?");
$stmt->bind_param("s", $search_param); 
$stmt->execute();
$result = $stmt->get_result();
$books = $result->fetch_all(MYSQLI_ASSOC);

$total = count($books);
$in_stock = count(array_filter($books, fn($b) => $b['Cantitate'] > 0));
$out_stock = $total - $in_stock;
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carti - BIBLIОСYS</title>
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

        <a href="index.php" class="nav-item active">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
            <span>Carti</span>
        </a>
        <a href="readers.php" class="nav-item">
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
                <span class="topbar-badge"> <?= date('d M Y') ?></span>
            </div>
        </header>

        <main class="page-content">

            <div class="page-header">
                <div>
                    <h1>Lista Cărților</h1>
                    <p class="subtitle">Gestionează catalogul de volume</p>
                </div>
                <a href="edit_book.php" class="btn btn-add">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                    Adaugă Carte
                </a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-card-top">
                        <div class="stat-icon blue">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                        </div>
                        <span class="stat-trend up">Total</span>
                    </div>
                    <div class="stat-value"><?= $total ?></div>
                    <div class="stat-label">Cărți înregistrate</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-top">
                        <div class="stat-icon green">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><polyline points="20 6 9 17 4 12"/></svg>
                        </div>
                        <span class="stat-trend up">↑</span>
                    </div>
                    <div class="stat-value"><?= $in_stock ?></div>
                    <div class="stat-label">În stoc</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-top">
                        <div class="stat-icon red">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
                        </div>
                        <span class="stat-trend down">↓</span>
                    </div>
                    <div class="stat-value"><?= $out_stock ?></div>
                    <div class="stat-label">Stoc epuizat</div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-top">
                        <div class="stat-icon amber">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total > 0 ? round(($in_stock/$total)*100) : 0 ?>%</div>
                    <div class="stat-label">Disponibilitate</div>
                </div>
            </div>

            <section class="search-section">
                <form class="search-form" method="GET">
                    <div class="search-input-wrap">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                        <input type="text" name="search" placeholder="Caută după titlu..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <button type="submit" class="btn btn-search">Caută</button>
                </form>
            </section>

            <div class="table-container">
                <div class="table-topbar">
                    <span class="table-topbar-title">Catalog volume</span>
                    <span class="table-topbar-count"><?= $total ?> rezultate</span>
                </div>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titlu</th>
                            <th>Autor</th>
                            <th>An</th>
                            <th>Gen</th>
                            <th>Stoc</th>
                            <th class="actions-head">Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($books)): ?>
                            <tr><td colspan="7">
                                <div class="empty-state">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"/></svg>
                                    <div>Nu s-au găsit cărți în baza de date.</div>
                                </div>
                            </td></tr>
                        <?php endif; ?>

                        <?php foreach($books as $book): ?>
                        <tr>
                            <td><span class="id-badge">#<?= $book['ID_carte'] ?></span></td>
                            <td class="book-title"><?= htmlspecialchars($book['Titlu']) ?></td>
                            <td style="color:var(--text-secondary)"><?= htmlspecialchars($book['Autor']) ?></td>
                            <td style="color:var(--text-secondary);font-variant-numeric:tabular-nums"><?= $book['Anul_publicarii'] ?></td>
                            <td><span class="genre-tag"><?= htmlspecialchars($book['Gen']) ?></span></td>
                            <td>
                                <span class="<?= ($book['Cantitate'] > 0) ? 'in-stock' : 'out-of-stock' ?>">
                                    <?= $book['Cantitate'] ?> buc.
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="edit_book.php?id=<?= $book['ID_carte'] ?>" class="btn-icon edit">Editează</a>
                                <a href="delete.php?type=book&id=<?= $book['ID_carte'] ?>"
                                   class="btn-icon delete"
                                   onclick="return confirm('Sigur dorești ștergerea?')">Șterge</a>
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
