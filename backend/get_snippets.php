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

try {
  $stmt = $pdo->query("SELECT * FROM snippets ORDER BY created_at DESC");
  $snippets = $stmt->fetchAll();
  echo json_encode($snippets);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
}
