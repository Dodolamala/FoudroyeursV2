<?php
/**
 * Fonctions pour la gestion du panier
 */

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Démarrer la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Inclure les dépendances nécessaires
if (!function_exists('get_products')) {
    require_once __DIR__ . '/../config/database.php';
    require_once __DIR__ . '/functions.php';
}

// Initialiser le panier s'il n'existe pas déjà
function init_cart() {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [
            'items' => [],
            'total' => 0,
            'count' => 0
        ];
    }
}

// Ajouter un produit au panier
function add_to_cart($product_id, $quantity = 1, $option_id = null) {
    global $db;
    
    // Log pour débogage
    error_log("Tentative d'ajout au panier - ID: $product_id, Quantité: $quantity, Option: $option_id");
    
    // S'assurer que le panier est initialisé
    init_cart();
    
    // Récupérer les informations du produit
    try {
        $stmt = $db->prepare("SELECT * FROM produits WHERE id = :id");
        $stmt->bindParam(':id', $product_id, PDO::PARAM_INT);
        $stmt->execute();
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$product) {
            error_log("Produit introuvable: $product_id");
            return [
                'success' => false, 
                'message' => 'Produit introuvable'
            ];
        }
    } catch (PDOException $e) {
        error_log("Erreur DB lors de la récupération du produit: " . $e->getMessage());
        return [
            'success' => false, 
            'message' => 'Erreur lors de la récupération du produit'
        ];
    }
    
    // Vérifier le stock disponible
    $stock_sufficient = true;
    $option_details = null;
    
    if ($option_id) {
        // Vérifier le stock de l'option spécifique
        try {
            $stmt = $db->prepare("SELECT * FROM options_produit WHERE id = :option_id");
            $stmt->bindParam(':option_id', $option_id, PDO::PARAM_INT);
            $stmt->execute();
            $option_details = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$option_details || $option_details['stock'] < $quantity) {
                $stock_sufficient = false;
            }
        } catch (PDOException $e) {
            error_log("Erreur DB lors de la vérification de l'option: " . $e->getMessage());
            return [
                'success' => false, 
                'message' => 'Erreur lors de la vérification du stock'
            ];
        }
    } else {
        // Vérifier le stock général du produit
        if (isset($product['quantite_stock']) && $product['quantite_stock'] < $quantity) {
            $stock_sufficient = false;
        }
    }
    
    if (!$stock_sufficient) {
        error_log("Stock insuffisant pour le produit: $product_id");
        return [
            'success' => false, 
            'message' => 'Stock insuffisant pour ce produit'
        ];
    }
    
    // Créer un identifiant unique pour cet article (produit + option)
    $item_id = $product_id . '-' . ($option_id ?? 'default');
    
    // Vérifier si l'article est déjà dans le panier
    if (isset($_SESSION['cart']['items'][$item_id])) {
        // Mettre à jour la quantité
        $_SESSION['cart']['items'][$item_id]['quantity'] += $quantity;
    } else {
        // Ajouter un nouvel article
        $_SESSION['cart']['items'][$item_id] = [
            'product_id' => $product_id,
            'option_id' => $option_id,
            'name' => $product['nom'],
            'price' => $product['prix'],
            'image' => $product['image_url'],
            'quantity' => $quantity,
            'option_name' => $option_details ? $option_details['type'] . ': ' . $option_details['valeur'] : null
        ];
    }
    
    // Mettre à jour le total et le nombre d'articles
    update_cart_totals();
    
    error_log("Produit ajouté avec succès: $product_id");
    return [
        'success' => true, 
        'message' => 'Produit ajouté au panier',
        'cart' => $_SESSION['cart']
    ];
}

// Mettre à jour la quantité d'un article
function update_cart_item($item_id, $quantity) {
    // S'assurer que le panier est initialisé
    init_cart();
    
    if (!isset($_SESSION['cart']['items'][$item_id])) {
        return [
            'success' => false, 
            'message' => 'Article introuvable dans le panier'
        ];
    }
    
    // Vérifier que la quantité est valide
    if ($quantity < 1) {
        return [
            'success' => false, 
            'message' => 'La quantité doit être d\'au moins 1'
        ];
    }
    
    // Mettre à jour la quantité
    $_SESSION['cart']['items'][$item_id]['quantity'] = $quantity;
    
    // Mettre à jour le total et le nombre d'articles
    update_cart_totals();
    
    return [
        'success' => true, 
        'message' => 'Quantité mise à jour',
        'cart' => $_SESSION['cart']
    ];
}

// Supprimer un article du panier
function remove_from_cart($item_id) {
    // S'assurer que le panier est initialisé
    init_cart();
    
    if (!isset($_SESSION['cart']['items'][$item_id])) {
        return [
            'success' => false, 
            'message' => 'Article introuvable dans le panier'
        ];
    }
    
    // Supprimer l'article
    unset($_SESSION['cart']['items'][$item_id]);
    
    // Mettre à jour le total et le nombre d'articles
    update_cart_totals();
    
    return [
        'success' => true, 
        'message' => 'Article retiré du panier',
        'cart' => $_SESSION['cart']
    ];
}

// Vider le panier
function clear_cart() {
    $_SESSION['cart'] = [
        'items' => [],
        'total' => 0,
        'count' => 0
    ];
    
    return [
        'success' => true, 
        'message' => 'Panier vidé',
        'cart' => $_SESSION['cart']
    ];
}

// Mettre à jour le total et le nombre d'articles du panier
function update_cart_totals() {
    $total = 0;
    $count = 0;
    
    foreach ($_SESSION['cart']['items'] as $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
        $count += $item['quantity'];
    }
    
    $_SESSION['cart']['total'] = $total;
    $_SESSION['cart']['count'] = $count;
}

// Récupérer le contenu du panier
function get_cart() {
    init_cart();
    return $_SESSION['cart'];
}

// Traitement des requêtes AJAX pour le panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cart_action'])) {
    $response = ['success' => false, 'message' => 'Action non reconnue'];
    
    error_log("Requête panier reçue - Action: " . $_POST['cart_action']);
    
    switch ($_POST['cart_action']) {
        case 'add':
            $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            $option_id = isset($_POST['option_id']) && !empty($_POST['option_id']) ? intval($_POST['option_id']) : null;
            
            error_log("Données reçues - Produit: $product_id, Qté: $quantity, Option: $option_id");
            
            $response = add_to_cart($product_id, $quantity, $option_id);
            break;
            
        case 'update':
            $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : '';
            $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
            
            $response = update_cart_item($item_id, $quantity);
            break;
            
        case 'remove':
            $item_id = isset($_POST['item_id']) ? $_POST['item_id'] : '';
            
            $response = remove_from_cart($item_id);
            break;
            
        case 'clear':
            $response = clear_cart();
            break;
            
        default:
            error_log("Action inconnue: " . $_POST['cart_action']);
            break;
    }
    
    // Si c'est une requête AJAX, on renvoie du JSON
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
    }
    
    // Sinon, on stocke le message en session et on redirige
    $_SESSION['message'] = $response['message'];
    $_SESSION['message_type'] = $response['success'] ? 'success' : 'error';
    
    $redirect = isset($_POST['redirect']) ? $_POST['redirect'] : 'panier.php';
    header("Location: $redirect");
    exit;
}

// Initialiser le panier
init_cart();
?>