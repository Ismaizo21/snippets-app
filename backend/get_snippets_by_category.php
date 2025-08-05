<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'db_connect.php';

// Gérer la requête preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

$category = $_GET['category'] ?? '';

if (!in_array($category, ['PHP', 'HTML', 'CSS'])) {
  http_response_code(400);
  echo json_encode(['error' => 'Catégorie invalide']);
  exit;
}

try {
  $stmt = $pdo->prepare("SELECT * FROM snippets WHERE category = ? ORDER BY created_at DESC");
  $stmt->execute([$category]);
  $snippets = $stmt->fetchAll();
  echo json_encode($snippets);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
}
