<?php
/**
 * Fonctions utilitaires pour le site
 */

// Fonction pour nettoyer les entrées utilisateur
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fonction pour formater le prix
function format_price($price) {
    return number_format($price, 2, ',', ' ') . ' €';
}

// Fonction pour récupérer les membres de l'équipe
function get_team_members($db, $limit = null) {
    $sql = "SELECT * FROM membres_equipe WHERE equipe_id = 1 ORDER BY id";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur dans get_team_members: " . $e->getMessage());
        return [];
    }
}

// Fonction pour récupérer les palmarès
function get_achievements($db, $limit = null) {
    $sql = "SELECT * FROM palmares WHERE equipe_id = 1 ORDER BY date_obtention DESC";
    if ($limit) {
        $sql .= " LIMIT " . intval($limit);
    }
    
    try {
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur dans get_achievements: " . $e->getMessage());
        return [];
    }
}

// Fonction pour récupérer les partenaires
function get_partners($db) {
    try {
        $stmt = $db->prepare("SELECT * FROM partenaires ORDER BY ordre");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur dans get_partners: " . $e->getMessage());
        return [];
    }
}

// Fonction pour récupérer les produits
function get_products($db, $filters = []) {
    $sql = "SELECT p.*, c.nom as categorie_nom, c.slug as categorie_slug 
            FROM produits p 
            LEFT JOIN categories c ON p.categorie_id = c.id";
    
    $where_clauses = [];
    $params = [];
    
    // Filtre par ID
    if (isset($filters['id']) && $filters['id']) {
        $where_clauses[] = "p.id = :id";
        $params[':id'] = $filters['id'];
    }
    
    // Filtre par slug
    if (isset($filters['slug']) && $filters['slug']) {
        $where_clauses[] = "p.slug = :slug";
        $params[':slug'] = $filters['slug'];
    }
    
    // Filtre par catégorie
    if (isset($filters['categorie']) && $filters['categorie']) {
        $where_clauses[] = "c.slug = :categorie";
        $params[':categorie'] = $filters['categorie'];
    }
    
    // Filtre par popularité
    if (isset($filters['populaire']) && $filters['populaire']) {
        $where_clauses[] = "p.est_populaire = 1";
    }
    
    // Filtre par nouveautés
    if (isset($filters['nouveau']) && $filters['nouveau']) {
        $where_clauses[] = "p.est_nouveau = 1";
    }
    
    // Filtre par recherche
    if (isset($filters['search']) && $filters['search']) {
        $where_clauses[] = "(p.nom LIKE :search OR p.description LIKE :search)";
        $params[':search'] = '%' . $filters['search'] . '%';
    }
    
    // Construction de la clause WHERE
    if (!empty($where_clauses)) {
        $sql .= " WHERE " . implode(" AND ", $where_clauses);
    }
    
    // Ordre
    $sql .= " ORDER BY ";
    if (isset($filters['order'])) {
        switch ($filters['order']) {
            case 'prix_asc':
                $sql .= "p.prix ASC";
                break;
            case 'prix_desc':
                $sql .= "p.prix DESC";
                break;
            case 'nouveautes':
                $sql .= "p.est_nouveau DESC, p.date_creation DESC";
                break;
            case 'populaires':
                $sql .= "p.est_populaire DESC";
                break;
            default:
                $sql .= "p.date_creation DESC";
        }
    } else {
        $sql .= "p.est_nouveau DESC, p.est_populaire DESC, p.date_creation DESC";
    }
    
    // Limite
    if (isset($filters['limit']) && $filters['limit']) {
        $sql .= " LIMIT " . intval($filters['limit']);
    }
    
    try {
        $stmt = $db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        
        if (isset($filters['id']) || isset($filters['slug'])) {
            return $stmt->fetch(); // Retourne un seul produit
        }
        
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur dans get_products: " . $e->getMessage());
        return isset($filters['id']) || isset($filters['slug']) ? null : [];
    }
}

// Fonction pour récupérer les options d'un produit
function get_product_options($db, $product_id) {
    try {
        $stmt = $db->prepare("SELECT * FROM options_produit WHERE produit_id = :produit_id");
        $stmt->bindParam(':produit_id', $product_id);
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur dans get_product_options: " . $e->getMessage());
        return [];
    }
}

// Fonction pour récupérer les catégories
function get_categories($db) {
    try {
        $stmt = $db->prepare("SELECT * FROM categories ORDER BY nom");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (PDOException $e) {
        error_log("Erreur dans get_categories: " . $e->getMessage());
        return [];
    }
}

// Fonction pour vérifier si l'utilisateur est connecté
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

// Fonction pour vérifier si l'utilisateur est un administrateur
function is_admin() {
    return isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Fonction pour générer une URL propre
function slugify($text) {
    // Remplacer les caractères non alphanumériques par des tirets
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // Translittération
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // Supprimer les caractères indésirables
    $text = preg_replace('~[^-\w]+~', '', $text);
    // Trim
    $text = trim($text, '-');
    // Supprimer les tirets doubles
    $text = preg_replace('~-+~', '-', $text);
    // Mettre en minuscules
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

// Fonction pour vérifier si une chaîne est vide
function is_empty($value) {
    return !isset($value) || $value === '';
}

// Fonction pour rediriger
function redirect($url) {
    header("Location: $url");
    exit;
}

// Fonction pour afficher un message
function set_message($message, $type = 'success') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Fonction pour afficher un message et rediriger
function set_message_and_redirect($message, $url, $type = 'success') {
    set_message($message, $type);
    redirect($url);
}

// Fonction pour générer un token CSRF
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Fonction pour vérifier un token CSRF
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && $_SESSION['csrf_token'] === $token;
}