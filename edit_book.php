<?php
require 'db.php';

$id = $_GET['id'] ?? null;
$book = ['Titlu' => '', 'Autor' => '', 'Anul_publicarii' => '', 'Gen' => '', 'Cantitate' => 0];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM carti WHERE ID_carte = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $book = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $t = trim($_POST['Titlu']);
    $a = trim($_POST['Autor']);
    $an = (int)$_POST['Anul_publicarii'];
    $g = trim($_POST['Gen']);
    $c = (int)$_POST['Cantitate'];

    if ($id) {
        $sql = "UPDATE carti SET Titlu=?, Autor=?, Anul_publicarii=?, Gen=?, Cantitate=? WHERE ID_carte=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisii", $t, $a, $an, $g, $c, $id);
    } else {
        $sql = "INSERT INTO carti (Titlu, Autor, Anul_publicarii, Gen, Cantitate) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $t, $a, $an, $g, $c);
    }

    if ($stmt->execute()) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Eroare la salvare: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Editează' : 'Adaugă' ?> Carte - BIBLIОСYS</title>
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
            <span class="topbar-title"><?= $id ? 'Editează' : 'Adaugă' ?> Carte</span>
            <div class="topbar-right">
                <a href="index.php" style="color:var(--text-secondary);text-decoration:none;font-size:0.82rem;">← Înapoi la catalog</a>
            </div>
        </header>

        <main class="form-wrapper">
            <div class="form-card">
                <div class="form-header">
                    <h2><?= $id ? 'Editează' : 'Adaugă' ?> Carte</h2>
                    <p>Introdu detaliile volumului în baza de date</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert-box error">
                        <span class="alert-icon">⚠️</span>
                        <p><?= $error ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" class="styled-form">
                    <div class="form-row">
                        <div class="input-group full">
                            <label>Titlu Carte</label>
                            <input type="text" name="Titlu" value="<?= htmlspecialchars($book['Titlu']) ?>" placeholder="Ex: Cel mai iubit dintre pământeni" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full">
                            <label>Autor</label>
                            <input type="text" name="Autor" value="<?= htmlspecialchars($book['Autor']) ?>" placeholder="Ex: Marin Preda" required>
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="input-group">
                            <label>An Publicare</label>
                            <input type="number" name="Anul_publicarii" value="<?= $book['Anul_publicarii'] ?>" placeholder="2024">
                        </div>
                        <div class="input-group">
                            <label>Stoc (buc.)</label>
                            <input type="number" name="Cantitate" value="<?= $book['Cantitate'] ?>" placeholder="0">
                        </div>
                    </div>

                    <div class="form-row mt-1">
                        <div class="input-group full">
                            <label>Gen Literar</label>
                            <input type="text" name="Gen" value="<?= htmlspecialchars($book['Gen']) ?>" placeholder="Ex: Roman, SF, Istorie">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Salvează modificările</button>
                        <a href="index.php" class="btn-cancel">Înapoi la listă</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

</body>
</html>
