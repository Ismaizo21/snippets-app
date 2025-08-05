<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:3000');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include 'db_connect.php';

// Gérer la requête preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  http_response_code(200);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $data = json_decode(file_get_contents('php://input'), true);
  $title = $data['title'] ?? '';
  $description = $data['description'] ?? '';
  $category = $data['category'] ?? '';
  $code = $data['code'] ?? '';

  if (empty($title) || empty($category) || empty($code)) {
    http_response_code(400);
    echo json_encode(['error' => 'Champs obligatoires manquants']);
    exit;
  }

  if (!in_array($category, ['PHP', 'HTML', 'CSS'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Catégorie invalide']);
    exit;
  }

  try {
    $stmt = $pdo->prepare("INSERT INTO snippets (title, description, category, code) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $category, $code]);
    http_response_code(201);
    echo json_encode(['message' => 'Snippet ajouté avec succès']);
  } catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erreur serveur : ' . $e->getMessage()]);
  }
} else {
  http_response_code(405);
  echo json_encode(['error' => 'Méthode non autorisée']);
}
