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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Management Biblioteca</title>
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

<main class="content-wrapper">
    <header class="page-header">
        <h1>Lista Cartilor</h1>
        <a href="edit_book.php" class="btn btn-add">Adauga Carte</a>
    </header>

    <section class="search-section">
        <form class="search-form">
            <input type="text" name="search" placeholder="Cauta dupa titlu..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-search">Cauta</button>
        </form>
    </section>

    <div class="table-container">
        <table class="styled-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titlu</th>
                    <th>Autor</th>
                    <th>An</th>
                    <th>Gen</th>
                    <th>Stoc</th>
                    <th class="actions-head">Actiuni</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($books)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            Nu s-au gasit carti in baza de date.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach($books as $book): ?>
                <tr>
                    <td><span class="id-badge"><?= $book['ID_carte'] ?></span></td>
                    <td class="book-title"><?= htmlspecialchars($book['Titlu']) ?></td>
                    <td><?= htmlspecialchars($book['Autor']) ?></td>
                    <td><?= $book['Anul_publicarii'] ?></td>
                    <td><span class="genre-tag"><?= htmlspecialchars($book['Gen']) ?></span></td>
                    <td>
                        <span class="stock-status <?= ($book['Cantitate'] > 0) ? 'in-stock' : 'out-of-stock' ?>">
                            <?= $book['Cantitate'] ?> buc.
                        </span>
                    </td>
                    <td class="action-buttons">
                        <a href="edit_book.php?id=<?= $book['ID_carte'] ?>" class="btn-icon edit" title="Editeaza">Edit</a>
                        <a href="delete.php?type=book&id=<?= $book['ID_carte'] ?>" 
                           class="btn-icon delete" 
                           onclick="return confirm('Sigur doresti stergerea?')" title="Sterge">Sterge</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</main>

</body>
</html>