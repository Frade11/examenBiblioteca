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
        header("Location: index.php?msg=Operatiune reusita");
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
    <title><?= $id ? 'Editeaza' : 'Adauga' ?> Carte - BIBLIO-SYS</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<nav class="main-nav">
    <div class="nav-container">
        <a class="brand" href="index.php">BIBLIO<span>SYS</span></a>
        <div class="nav-links">
            <a href="index.php" class="active">Carti</a>
            <a href="readers.php">Cititori</a>
        </div>
    </div>
</nav>

<main class="form-wrapper">
    <div class="form-card">
        <header class="form-header">
            <h2><?= $id ? 'Editeaza' : 'Adauga' ?> Carte</h2>
            <p>Introdu detaliile volumului in baza de date</p>
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
                    <label>Titlu Carte</label>
                    <input type="text" name="Titlu" value="<?= htmlspecialchars($book['Titlu']) ?>" placeholder="Ex: Cel mai iubit dintre pamanteni" required>
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
                <button type="submit" class="btn btn-save">Salveaza Modificarile</button>
                <a href="index.php" class="btn btn-cancel">Inapoi la lista</a>
            </div>
        </form>
    </div>
</main>

</body>
</html>