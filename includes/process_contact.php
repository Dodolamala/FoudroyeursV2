<?php
/**
 * Traitement des formulaires de contact
 */
require_once '../config/database.php';
require_once 'functions.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données
    $nom = isset($_POST['nom']) ? clean_input($_POST['nom']) : '';
    $email = isset($_POST['email']) ? clean_input($_POST['email']) : '';
    $sujet = isset($_POST['sujet']) ? clean_input($_POST['sujet']) : '';
    $message = isset($_POST['message']) ? clean_input($_POST['message']) : '';
    
    // Validation des données
    $errors = [];
    
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire";
    }
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide";
    }
    
    if (empty($sujet)) {
        $errors[] = "Le sujet est obligatoire";
    }
    
    if (empty($message)) {
        $errors[] = "Le message est obligatoire";
    }
    
    // Vérifier s'il y a des erreurs
    if (empty($errors)) {
        // Insertion du message dans la base de données
        try {
            $stmt = $db->prepare("
                INSERT INTO messages_contact (nom, email, sujet, message) 
                VALUES (:nom, :email, :sujet, :message)
            ");
            
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':sujet', $sujet);
            $stmt->bindParam(':message', $message);
            
            $stmt->execute();
            
            // Préparation de la réponse
            $response = [
                'success' => true,
                'message' => "Votre message a bien été envoyé. Nous vous répondrons dans les plus brefs délais."
            ];
        } catch (PDOException $e) {
            // Log de l'erreur
            error_log("Erreur lors de l'enregistrement du message de contact: " . $e->getMessage());
            
            // Réponse d'erreur
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer ultérieurement."
            ];
        }
    } else {
        // Réponse avec les erreurs
        $response = [
            'success' => false,
            'message' => "Veuillez corriger les erreurs suivantes : " . implode(", ", $errors)
        ];
    }
    
    // Si c'est une requête AJAX, on retourne du JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Sinon, on stocke le message en session et on redirige
    $_SESSION['message'] = $response['message'];
    $_SESSION['message_type'] = $response['success'] ? 'success' : 'error';
    
    header('Location: ../contact.php');
    exit;
}

// Si on arrive ici sans POST, rediriger vers la page de contact
header('Location: ../contact.php');
exit;