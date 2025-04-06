<?php
/**
 * Page détail produit des Foudroyeurs
 */
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/cart.php';

// Récupérer le slug du produit
$slug = isset($_GET['slug']) ? clean_input($_GET['slug']) : '';

// Si aucun slug n'est fourni, rediriger vers la boutique
if (empty($slug)) {
    header('Location: eshop.php');
    exit;
}

// Récupérer les informations du produit
$product = get_products($db, ['slug' => $slug]);

// Si le produit n'existe pas, rediriger vers la boutique
if (!$product) {
    set_message_and_redirect('Produit non trouvé.', 'eshop.php', 'error');
}

// Récupérer les options du produit
$options = get_product_options($db, $product['id']);

// Organiser les options par type
$options_by_type = [];
foreach ($options as $option) {
    if (!isset($options_by_type[$option['type']])) {
        $options_by_type[$option['type']] = [];
    }
    $options_by_type[$option['type']][] = $option;
}

// Récupérer des produits similaires
$similar_products = get_products($db, [
    'categorie' => $product['categorie_slug'],
    'limit' => 4
]);

// Filtrer le produit actuel des produits similaires
$similar_products = array_filter($similar_products, function($item) use ($product) {
    return $item['id'] != $product['id'];
});
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo htmlspecialchars($product['nom']); ?> - E-Shop officiel des Foudroyeurs">
    <title><?php echo htmlspecialchars($product['nom']); ?> - Les Foudroyeurs</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">
            <span class="logo-icon">⚡</span>
            Les Foudroyeurs
        </a>
        <ul class="nav-links">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="equipe.php">Équipe</a></li>
            <li><a href="palmares.php">Palmarès</a></li>
            <li><a href="eshop.php" class="active">E-Shop</a></li>
            <li><a href="partenaires.php">Partenaires</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="panier.php">Panier <?php if(isset($_SESSION['cart']) && $_SESSION['cart']['count'] > 0): ?><span class="cart-count"><?php echo $_SESSION['cart']['count']; ?></span><?php endif; ?></a></li>
        </ul>
        <button class="mobile-menu-btn">☰</button>
    </nav>
    
    <section class="section" style="margin-top: 100px;">
        <div class="container">
            <?php if(isset($_SESSION['message'])): ?>
            <div class="message message-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
            <?php endif; ?>
            
            <div style="display: flex; flex-wrap: wrap; gap: 50px; margin-bottom: 50px;">
                <div style="flex: 1; min-width: 300px;">
                    <div style="background-color: rgba(255, 255, 255, 0.05); border-radius: 10px; overflow: hidden; padding: 20px; border: 1px solid var(--primary-color);">
                        <img src="assets/images/<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['nom']); ?>" style="width: 100%; height: auto; border-radius: 5px;">
                    </div>
                </div>
                
                <div style="flex: 1; min-width: 300px;">
                    <div style="margin-bottom: 20px;">
                        <a href="eshop.php" style="color: var(--accent-color); text-decoration: none;">← Retour à la boutique</a>
                    </div>
                    
                    <?php if($product['est_nouveau']): ?>
                    <div style="display: inline-block; background-color: var(--primary-color); color: var(--secondary-color); padding: 5px 10px; border-radius: 3px; font-weight: 600; font-size: 0.8rem; margin-bottom: 10px;">Nouveau</div>
                    <?php elseif($product['est_populaire']): ?>
                    <div style="display: inline-block; background-color: var(--primary-color); color: var(--secondary-color); padding: 5px 10px; border-radius: 3px; font-weight: 600; font-size: 0.8rem; margin-bottom: 10px;">Populaire</div>
                    <?php endif; ?>
                    
                    <h1 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 10px;"><?php echo htmlspecialchars($product['nom']); ?></h1>
                    
                    <p style="font-size: 1.8rem; color: var(--accent-color); font-weight: 700; margin-bottom: 20px;"><?php echo format_price($product['prix']); ?></p>
                    
                    <div style="margin-bottom: 20px;">
                        <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    </div>
                    
                    <form class="add-to-cart-form" action="includes/cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        
                        <?php foreach($options_by_type as $type => $type_options): ?>
                        <div style="margin-bottom: 20px;">
                            <label for="option-<?php echo $type; ?>" style="display: block; margin-bottom: 5px; font-weight: 600;"><?php echo ucfirst($type); ?> :</label>
                            <select name="option_id" id="option-<?php echo $type; ?>" class="form-input" required>
                                <option value="">Sélectionnez <?php echo $type === 'taille' ? 'une' : 'un'; ?> <?php echo $type; ?></option>
                                <?php foreach($type_options as $option): ?>
                                <option value="<?php echo $option['id']; ?>" <?php echo $option['stock'] < 1 ? 'disabled' : ''; ?>>
                                    <?php echo htmlspecialchars($option['valeur']); ?> 
                                    <?php echo $option['stock'] < 1 ? '(Épuisé)' : ''; ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endforeach; ?>
                        
                        <div style="margin-bottom: 20px;">
                            <label for="quantity" style="display: block; margin-bottom: 5px; font-weight: 600;">Quantité :</label>
                            <input type="number" name="quantity" id="quantity" value="1" min="1" max="10" class="form-input" style="width: 80px;">
                        </div>
                        
                        <button type="submit" class="btn" style="width: 100%;">Ajouter au panier</button>
                    </form>
                    
                    <div style="margin-top: 30px; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 20px;">
                        <div style="margin-bottom: 10px; display: flex; align-items: center;">
                            <span style="margin-right: 10px;">🚚</span>
                            <span>Livraison gratuite à partir de 50€</span>
                        </div>
                        <div style="margin-bottom: 10px; display: flex; align-items: center;">
                            <span style="margin-right: 10px;">↩️</span>
                            <span>Retours gratuits sous 30 jours</span>
                        </div>
                        <div style="display: flex; align-items: center;">
                            <span style="margin-right: 10px;">🔒</span>
                            <span>Paiement sécurisé</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if(!empty($similar_products)): ?>
            <div style="margin-top: 80px;">
                <h2 class="section-title">Vous aimerez aussi</h2>
                
                <div class="products-grid">
                    <?php foreach($similar_products as $similar): ?>
                    <div class="product-card">
                        <?php if($similar['est_nouveau']): ?>
                        <div class="product-badge">Nouveau</div>
                        <?php elseif($similar['est_populaire']): ?>
                        <div class="product-badge">Populaire</div>
                        <?php endif; ?>
                        
                        <div class="product-img-container">
                            <img src="assets/images/<?php echo $similar['image_url']; ?>" alt="<?php echo $similar['nom']; ?>" class="product-img">
                        </div>
                        
                        <div class="product-info">
                            <h3 class="product-name"><?php echo $similar['nom']; ?></h3>
                            <p class="product-price"><?php echo format_price($similar['prix']); ?></p>
                            <div class="product-actions">
                                <a href="produit.php?slug=<?php echo $similar['slug']; ?>" class="btn btn-small btn-outline">Détails</a>
                                <form class="add-to-cart-form" action="includes/cart.php" method="post">
                                    <input type="hidden" name="product_id" value="<?php echo $similar['id']; ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-small">Ajouter</button>
                                    <input type="hidden" name="cart_action" value="add">
                                </form>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <footer>
        <div class="container">
            <div class="footer-columns">
                <div class="footer-column">
                    <h4>Navigation</h4>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="equipe.php">Équipe</a></li>
                        <li><a href="palmares.php">Palmarès</a></li>
                        <li><a href="eshop.php">E-Shop</a></li>
                        <li><a href="partenaires.php">Partenaires</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>E-Shop</h4>
                    <ul>
                        <li><a href="eshop.php?categorie=maillots">Maillots</a></li>
                        <li><a href="eshop.php?categorie=vetements">Vêtements</a></li>
                        <li><a href="eshop.php?categorie=accessoires">Accessoires</a></li>
                        <li><a href="eshop.php?categorie=equipement">Équipement</a></li>
                        <li><a href="panier.php">Panier</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Légal</h4>
                    <ul>
                        <li><a href="#">Mentions légales</a></li>
                        <li><a href="#">Politique de confidentialité</a></li>
                        <li><a href="#">CGV</a></li>
                    </ul>
                </div>
                <div class="footer-column">
                    <h4>Contact</h4>
                    <ul>
                        <li>Les Foudroyeurs</li>
                        <li>123 Avenue des Champions</li>
                        <li>75001 Paris, France</li>
                        <li>Email: contact@foudroyeurs.fr</li>
                        <li>Tél: 01 23 45 67 89</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-logo">⚡ Les Foudroyeurs</div>
                
                <div class="social-links">
                    <a href="#" class="social-link">T</a>
                    <a href="#" class="social-link">F</a>
                    <a href="#" class="social-link">I</a>
                    <a href="#" class="social-link">D</a>
                </div>
                
                <p class="copyright">© <?php echo date('Y'); ?> Les Foudroyeurs. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    
    <script src="assets/js/main.js"></script>
</body>
</html>