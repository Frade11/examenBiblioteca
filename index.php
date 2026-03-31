<?php 
require 'db.php'; 

$search = $_GET['search'] ?? '';
$search_param = "%$search%";

$stmt = $conn->prepare("SELECT * FROM carti WHERE Titlu LIKE ?");
$stmt->bind_param("s", $search_param); 
$stmt->execute();
$result = $stmt->get_result();
$books = $result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Biblioteca</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">BIBLIO-SYS</a>
        <div class="navbar-nav">
            <a class="nav-link active" href="index.php">Cărți</a>
            <a class="nav-link" href="readers.php">Cititori</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Lista Cărților</h2>
        <a href="edit_book.php" class="btn btn-success">Adaugă Carte</a>
    </div>

    <form class="row g-2 mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Caută după titlu..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Caută</button>
        </div>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Titlu</th>
                        <th>Autor</th>
                        <th>An</th>
                        <th>Gen</th>
                        <th>Stoc</th>
                        <th class="text-center">Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($books as $book): ?>
                    <tr>
                        <td><?= $book['ID_carte'] ?></td>
                        <td class="fw-bold"><?= htmlspecialchars($book['Titlu']) ?></td>
                        <td><?= htmlspecialchars($book['Autor']) ?></td>
                        <td><?= $book['Anul_publicarii'] ?></td>
                        <td><span class="badge bg-info text-dark"><?= htmlspecialchars($book['Gen']) ?></span></td>
                        <td><?= $book['Cantitate'] ?></td>
                        <td class="text-center">
                            <a href="edit_book.php?id=<?= $book['ID_carte'] ?>" class="btn btn-sm btn-outline-warning">Edit</a>
                            <a href="delete.php?type=book&id=<?= $book['ID_carte'] ?>" 
                               class="btn btn-sm btn-outline-danger" 
                               onclick="return confirm('Sigur dorești ștergerea?')">Șterge</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>