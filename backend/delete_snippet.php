<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'db_connect.php';

// Gérer la requête preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
  $id = $_GET['id'] ?? '';

  if (empty($id) || !is_numeric($id)) {
    http_response_code(400);
    echo json_encode(['error' => 'ID du snippet manquant ou invalide']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("DELETE FROM snippets WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->rowCount() > 0) {
      http_response_code(200);
      echo json_encode(['message' => 'Snippet supprimé avec succès']);
    } else {
      http_response_code(404);
      echo json_encode(['error' => 'Snippet non trouvé']);
    }
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
  }
} else {
  http_response_code(405);
  echo json_encode(['error' => 'Méthode non autorisée']);
}
