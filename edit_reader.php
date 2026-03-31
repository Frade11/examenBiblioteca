<?php
require 'db.php';

$id = $_GET['id'] ?? null;
$reader = ['Nume' => '', 'Prenume' => '', 'Telefon' => '', 'Email' => '', 'Data_inregistrarii' => date('Y-m-d')];

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM cititori WHERE ID_cititor = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $reader = $stmt->get_result()->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nume = trim($_POST['Nume']);
    $prenume = trim($_POST['Prenume']);
    $tel = trim($_POST['Telefon']);
    $email = trim($_POST['Email']);
    $data = $_POST['Data_inregistrarii'];

    if (empty($nume) || empty($prenume) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Te rugăm să completezi corect numele, prenumele și adresa de email.";
    } else {
        if ($id) {
            $sql = "UPDATE cititori SET Nume=?, Prenume=?, Telefon=?, Email=?, Data_inregistrarii=? WHERE ID_cititor=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $nume, $prenume, $tel, $email, $data, $id);
        } else {
            $sql = "INSERT INTO cititori (Nume, Prenume, Telefon, Email, Data_inregistrarii) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nume, $prenume, $tel, $email, $data);
        }

        if ($stmt->execute()) {
            header("Location: readers.php");
            exit;
        } else {
            $error = "Eroare la baza de date: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Editează' : 'Înregistrează' ?> Cititor - BIBLIОСYS</title>
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
            <span class="topbar-title"><?= $id ? 'Editează' : 'Înregistrează' ?> Cititor</span>
            <div class="topbar-right">
                <a href="readers.php" style="color:var(--text-secondary);text-decoration:none;font-size:0.82rem;">← Înapoi la cititori</a>
            </div>
        </header>

        <main class="form-wrapper">
            <div class="form-card">
                <div class="form-header">
                    <h2><?= $id ? 'Editează' : 'Înregistrează' ?> Cititor</h2>
                    <p>Completează informațiile de contact ale cititorului</p>
                </div>

                <?php if(isset($error)): ?>
                    <div class="alert-box error">
                        <span class="alert-icon">⚠️</span>
                        <p><?= $error ?></p>
                    </div>
                <?php endif; ?>

                <form method="POST" class="styled-form">
                    <div class="form-grid-2">
                        <div class="input-group">
                            <label>Nume</label>
                            <input type="text" name="Nume" value="<?= htmlspecialchars($reader['Nume']) ?>" placeholder="Nume" required>
                        </div>
                        <div class="input-group">
                            <label>Prenume</label>
                            <input type="text" name="Prenume" value="<?= htmlspecialchars($reader['Prenume']) ?>" placeholder="Prenume" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full">
                            <label>Telefon</label>
                            <input type="text" name="Telefon" value="<?= htmlspecialchars($reader['Telefon']) ?>" placeholder="07xx xxx xxx">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full">
                            <label>Email</label>
                            <input type="email" name="Email" value="<?= htmlspecialchars($reader['Email']) ?>" placeholder="nume@exemplu.com" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="input-group full">
                            <label>Data Înregistrării</label>
                            <input type="date" name="Data_inregistrarii" value="<?= $reader['Data_inregistrarii'] ?>">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn-save">Salvează cititorul</button>
                        <a href="readers.php" class="btn-cancel">Renunță</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
</div>

</body>
</html>
