<?php
require 'db.php';

$id = (int)($_GET['id'] ?? 0);
$type = $_GET['type'] ?? '';

if ($id > 0) {
    if ($type == 'book') {
        $stmt = $conn->prepare("DELETE FROM carti WHERE ID_carte = ?");
    } else {
        $stmt = $conn->prepare("DELETE FROM cititori WHERE ID_cititor = ?");
    }
    
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: " . ($type == 'book' ? 'index.php' : 'readers.php'));
exit;