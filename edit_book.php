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
    $t = $_POST['Titlu'];
    $a = $_POST['Autor'];
    $an = $_POST['Anul_publicarii'];
    $g = $_POST['Gen'];
    $c = $_POST['Cantitate'];

    if ($id) {
        // update
        $sql = "UPDATE carti SET Titlu=?, Autor=?, Anul_publicarii=?, Gen=?, Cantitate=? WHERE ID_carte=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisii", $t, $a, $an, $g, $c, $id);
    } else {
        // insert
        $sql = "INSERT INTO carti (Titlu, Autor, Anul_publicarii, Gen, Cantitate) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi", $t, $a, $an, $g, $c);
    }

    if ($stmt->execute()) {
        header("Location: index.php?msg=Operatiune reusita");
    } else {
        $error = "Eroare la salvare: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Formular Carte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 500px;">
        <div class="card shadow-lg border-0">
            <div class="card-body p-4">
                <h4 class="mb-4 text-center"><?= $id ? 'Editează' : 'Adaugă' ?> Carte</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Titlu</label>
                        <input type="text" name="Titlu" class="form-control" value="<?= htmlspecialchars($book['Titlu']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Autor</label>
                        <input type="text" name="Autor" class="form-control" value="<?= htmlspecialchars($book['Autor']) ?>" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">An</label>
                            <input type="number" name="Anul_publicarii" class="form-control" value="<?= $book['Anul_publicarii'] ?>">
                        </div>
                        <div class="col">
                            <label class="form-label">Cantitate</label>
                            <input type="number" name="Cantitate" class="form-control" value="<?= $book['Cantitate'] ?>">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Gen</label>
                        <input type="text" name="Gen" class="form-control" value="<?= htmlspecialchars($book['Gen']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvează Datele</button>
                    <a href="index.php" class="btn btn-link w-100 text-muted mt-2">Înapoi la listă</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>