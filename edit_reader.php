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
        $error = "Te rugam sa completezi corect numele, prenumele si adresa de email.";
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
            header("Location: readers.php?msg=Salvare reusita");
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
    <title><?= $id ? 'Editeaza' : 'Inregistreaza' ?> Cititor - BIBLIO-SYS</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="main-nav">
    <div class="nav-container">
        <a class="brand" href="index.php">BIBLIO<span>SYS</span></a>
        <div class="nav-links">
            <a href="index.php">Carti</a>
            <a href="readers.php" class="active">Cititori</a>
        </div>
    </div>
</nav>

<main class="form-wrapper">
    <div class="form-card">
        <header class="form-header">
            <h2><?= $id ? 'Editeaza' : 'Inregistreaza' ?> Cititor</h2>
            <p>Completeaza informatiile de contact ale cititorului</p>
        </header>

        <?php if(isset($error)): ?>
            <div class="alert-box error">
                <span class="alert-icon">⚠️</span>
                <p><?= $error ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" class="styled-form">
            <div class="form-row">
                <div class="input-group full">
                    <label>Nume</label>
                    <input type="text" name="Nume" value="<?= htmlspecialchars($reader['Nume']) ?>" placeholder="Ex: Nume" required>
                </div>
            </div>

            <div class="form-row">
                <div class="input-group full">
                    <label>Prenume</label>
                    <input type="text" name="Prenume" value="<?= htmlspecialchars($reader['Prenume']) ?>" placeholder="Ex: Prenume" required>
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
                    <label>Data Inregistrarii</label>
                    <input type="date" name="Data_inregistrarii" value="<?= $reader['Data_inregistrarii'] ?>">
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-save">Salveaza Cititorul</button>
                <a href="readers.php" class="btn btn-cancel">Renunta</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>