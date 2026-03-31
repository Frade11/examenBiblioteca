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

    // Validare simplă
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
    <title>Formular Cititor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container" style="max-width: 500px;">
        <div class="card shadow border-0">
            <div class="card-body p-4">
                <h4 class="mb-4"><?= $id ? 'Editează' : 'Înregistrează' ?> Cititor</h4>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nume</label>
                        <input type="text" name="Nume" class="form-control" value="<?= htmlspecialchars($reader['Nume']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prenume</label>
                        <input type="text" name="Prenume" class="form-control" value="<?= htmlspecialchars($reader['Prenume']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Telefon</label>
                        <input type="text" name="Telefon" class="form-control" value="<?= htmlspecialchars($reader['Telefon']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="Email" class="form-control" value="<?= htmlspecialchars($reader['Email']) ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Data Înregistrării</label>
                        <input type="date" name="Data_inregistrarii" class="form-control" value="<?= $reader['Data_inregistrarii'] ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Salvează Cititor</button>
                    <a href="readers.php" class="btn btn-link w-100 text-muted mt-2">Renunță</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>