<?php
/**
 * Configuration de la base de données
 * Ce fichier contient les informations de connexion à la base de données
 */

// Informations de connexion à la base de données
$db_config = [
    'host' => 'localhost',
    'dbname' => 'ESPORTS',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4'
];

// Création de la connexion PDO
try {
    $db = new PDO(
        "mysql:host={$db_config['host']};dbname={$db_config['dbname']};charset={$db_config['charset']}", 
        $db_config['username'], 
        $db_config['password'],
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch(PDOException $e) {
    // En production, utilisez un message d'erreur plus générique
    die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
}

// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}