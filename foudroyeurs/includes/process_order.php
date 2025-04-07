<?php
/**
 * Traitement des commandes
 * Ce fichier gère le processus de validation, d'enregistrement et de confirmation des commandes
 */
require_once '../config/database.php';
require_once 'functions.php';
require_once 'cart.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer et nettoyer les données
    $email = isset($_POST['email']) ? clean_input($_POST['email']) : '';
    $prenom = isset($_POST['prenom']) ? clean_input($_POST['prenom']) : '';
    $nom = isset($_POST['nom']) ? clean_input($_POST['nom']) : '';
    $telephone = isset($_POST['telephone']) ? clean_input($_POST['telephone']) : '';
    $adresse = isset($_POST['adresse']) ? clean_input($_POST['adresse']) : '';
    $complement = isset($_POST['complement']) ? clean_input($_POST['complement']) : '';
    $code_postal = isset($_POST['code_postal']) ? clean_input($_POST['code_postal']) : '';
    $ville = isset($_POST['ville']) ? clean_input($_POST['ville']) : '';
    $pays = isset($_POST['pays']) ? clean_input($_POST['pays']) : '';
    $paiement = isset($_POST['paiement']) ? clean_input($_POST['paiement']) : '';
    $note = isset($_POST['note']) ? clean_input($_POST['note']) : '';
    $cgu = isset($_POST['cgu']) ? true : false;

    // Récupérer le panier
    $cart = get_cart();

    // Validation des données
    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email est invalide";
    }

    if (empty($prenom)) {
        $errors[] = "Le prénom est obligatoire";
    }

    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire";
    }

    if (empty($telephone)) {
        $errors[] = "Le téléphone est obligatoire";
    }

    if (empty($adresse)) {
        $errors[] = "L'adresse est obligatoire";
    }

    if (empty($code_postal)) {
        $errors[] = "Le code postal est obligatoire";
    }

    if (empty($ville)) {
        $errors[] = "La ville est obligatoire";
    }

    if (empty($pays)) {
        $errors[] = "Le pays est obligatoire";
    }

    if (empty($paiement)) {
        $errors[] = "Le mode de paiement est obligatoire";
    }

    if (!$cgu) {
        $errors[] = "Vous devez accepter les conditions générales de vente";
    }

    // Vérifier si le panier est vide
    if (count($cart['items']) === 0) {
        $errors[] = "Votre panier est vide";
    }

    // Vérifier s'il y a des erreurs
    if (empty($errors)) {
        try {
            // Commencer une transaction
            $db->beginTransaction();

            // Utilisateur connecté ?
            $utilisateur_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

            // Calculer le montant total (avec frais de livraison si nécessaire)
            $shipping = ($cart['total'] >= 50) ? 0 : 5.99;
            $total = $cart['total'] + $shipping;

            // Insertion de la commande
            $stmt = $db->prepare("
                INSERT INTO commandes (utilisateur_id, email, status, montant_total, date_creation) 
                VALUES (:utilisateur_id, :email, 'en_attente', :montant_total, NOW())
            ");
            
            $stmt->bindParam(':utilisateur_id', $utilisateur_id);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':montant_total', $total);
            $stmt->execute();
            
            // Récupérer l'ID de la commande
            $commande_id = $db->lastInsertId();
            
            // Insertion des éléments de la commande
            foreach ($cart['items'] as $item) {
                $stmt = $db->prepare("
                    INSERT INTO elements_commande (commande_id, produit_id, option_id, quantite, prix_unitaire) 
                    VALUES (:commande_id, :produit_id, :option_id, :quantite, :prix_unitaire)
                ");
                
                $stmt->bindParam(':commande_id', $commande_id);
                $stmt->bindParam(':produit_id', $item['product_id']);
                $stmt->bindParam(':option_id', $item['option_id']);
                $stmt->bindParam(':quantite', $item['quantity']);
                $stmt->bindParam(':prix_unitaire', $item['price']);
                $stmt->execute();
            }
            
            // Insertion de l'adresse de livraison
            $stmt = $db->prepare("
                INSERT INTO adresses (utilisateur_id, adresse_ligne1, adresse_ligne2, ville, code_postal, pays, type) 
                VALUES (:utilisateur_id, :adresse_ligne1, :adresse_ligne2, :ville, :code_postal, :pays, 'livraison')
            ");
            
            $stmt->bindParam(':utilisateur_id', $utilisateur_id);
            $stmt->bindParam(':adresse_ligne1', $adresse);
            $stmt->bindParam(':adresse_ligne2', $complement);
            $stmt->bindParam(':ville', $ville);
            $stmt->bindParam(':code_postal', $code_postal);
            $stmt->bindParam(':pays', $pays);
            $stmt->execute();
            
            // Valider la transaction
            $db->commit();
            
            // Stocker les infos de commande en session pour la page de confirmation
            $_SESSION['order'] = [
                'id' => $commande_id,
                'total' => $total,
                'items_count' => $cart['count']
            ];
            
            // Vider le panier
            clear_cart();
            
            // Rediriger vers la page de confirmation
            header('Location: ../confirmation.php');
            exit;
            
        } catch (PDOException $e) {
            // Annuler la transaction en cas d'erreur
            $db->rollBack();
            
            // Log de l'erreur
            error_log("Erreur lors de l'enregistrement de la commande: " . $e->getMessage());
            
            // Informer l'utilisateur
            set_message_and_redirect("Une erreur est survenue lors de l'enregistrement de votre commande. Veuillez réessayer ultérieurement.", "../checkout.php", "error");
        }
    } else {
        // Stocker les erreurs en session
        set_message_and_redirect("Veuillez corriger les erreurs suivantes : " . implode(", ", $errors), "../checkout.php", "error");
    }
} else {
    // Redirection si accès direct
    header('Location: ../checkout.php');
    exit;
}