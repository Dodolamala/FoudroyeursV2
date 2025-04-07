<?php
/**
 * Page e-shop des Foudroyeurs
 */
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/cart.php';

// Récupérer la catégorie sélectionnée
$selected_category = isset($_GET['categorie']) ? clean_input($_GET['categorie']) : null;

// Récupérer l'ordre de tri
$selected_order = isset($_GET['tri']) ? clean_input($_GET['tri']) : null;

// Récupérer le terme de recherche
$search_term = isset($_GET['recherche']) ? clean_input($_GET['recherche']) : null;

// Construire les filtres
$filters = [];

if ($selected_category) {
    $filters['categorie'] = $selected_category;
}

if ($selected_order) {
    $filters['order'] = $selected_order;
}

if ($search_term) {
    $filters['search'] = $search_term;
}

// Récupérer les produits filtrés
$products = get_products($db, $filters);

// Récupérer toutes les catégories
$categories = get_categories($db);

// Produits en vedette
$featured_products = get_products($db, ['populaire' => true, 'limit' => 1]);
$featured_product = !empty($featured_products) ? $featured_products[0] : null;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="E-Shop officiel de l'équipe Les Foudroyeurs - Maillots, vêtements et accessoires aux couleurs de votre équipe préférée">
    <title>E-Shop - Les Foudroyeurs</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <header class="header-medium">
        <div class="overlay"></div>
        
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
        
        <div class="hero-content">
            <h1 class="hero-title">E-Shop Officiel</h1>
            <p class="hero-subtitle">Arborez les couleurs des Foudroyeurs, incarnez la puissance et la détermination !</p>
        </div>
    </header>
    
    <?php if($featured_product): ?>
    <section class="section section-dark">
        <div class="container">
            <h2 class="section-title">Collection Limitée Champion 2024</h2>
            
            <div style="max-width: 1000px; margin: 0 auto; background-color: rgba(255, 255, 255, 0.05); border-radius: 10px; overflow: hidden; padding: 30px; border: 1px solid var(--primary-color); display: flex; gap: 40px; flex-wrap: wrap;">
                <div style="flex: 1; min-width: 300px;">
                    <img src="assets/images/<?php echo $featured_product['image_url']; ?>" alt="<?php echo $featured_product['nom']; ?>" style="width: 100%; height: auto; border-radius: 5px;">
                </div>
                
                <div style="flex: 1; min-width: 300px;">
                    <h3 style="font-size: 2rem; color: var(--primary-color); margin-bottom: 15px;"><?php echo $featured_product['nom']; ?></h3>
                    <p style="margin-bottom: 20px; line-height: 1.6;"><?php echo $featured_product['description']; ?></p>
                    <p style="font-size: 1.5rem; color: var(--accent-color); font-weight: 700; margin-bottom: 20px;"><?php echo format_price($featured_product['prix']); ?></p>
                    
                    <form class="add-to-cart-form" action="includes/cart.php" method="post">
                        <input type="hidden" name="product_id" value="<?php echo $featured_product['id']; ?>">
                        <input type="hidden" name="quantity" value="1">
                        <button type="submit" class="btn">Ajouter au panier</button>
                        <input type="hidden" name="cart_action" value="add">
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
    
    <section class="section">
        <div class="container">
            <h2 class="section-title">Catalogue</h2>
            
            <div class="eshop-categories">
                <a href="eshop.php" class="category-btn <?php echo !$selected_category ? 'active' : ''; ?>">Tout</a>
                <?php foreach($categories as $category): ?>
                <a href="eshop.php?categorie=<?php echo $category['slug']; ?>" class="category-btn <?php echo $selected_category === $category['slug'] ? 'active' : ''; ?>"><?php echo $category['nom']; ?></a>
                <?php endforeach; ?>
            </div>
            
            <div style="display: flex; justify-content: space-between; margin: 30px 0; flex-wrap: wrap; gap: 20px;">
                <div>
                    <label for="sort-by">Trier par:</label>
                    <select id="sort-by" onchange="window.location.href=this.value">
                        <option value="eshop.php<?php echo $selected_category ? '?categorie=' . $selected_category : ''; ?>" <?php echo !$selected_order ? 'selected' : ''; ?>>Les plus récents</option>
                        <option value="eshop.php?<?php echo $selected_category ? 'categorie=' . $selected_category . '&' : ''; ?>tri=prix_asc" <?php echo $selected_order === 'prix_asc' ? 'selected' : ''; ?>>Prix croissant</option>
                        <option value="eshop.php?<?php echo $selected_category ? 'categorie=' . $selected_category . '&' : ''; ?>tri=prix_desc" <?php echo $selected_order === 'prix_desc' ? 'selected' : ''; ?>>Prix décroissant</option>
                        <option value="eshop.php?<?php echo $selected_category ? 'categorie=' . $selected_category . '&' : ''; ?>tri=populaires" <?php echo $selected_order === 'populaires' ? 'selected' : ''; ?>>Popularité</option>
                    </select>
                </div>
                
                <form action="eshop.php" method="get">
                    <?php if($selected_category): ?>
                    <input type="hidden" name="categorie" value="<?php echo $selected_category; ?>">
                    <?php endif; ?>
                    
                    <?php if($selected_order): ?>
                    <input type="hidden" name="tri" value="<?php echo $selected_order; ?>">
                    <?php endif; ?>
                    
                    <div style="display: flex;">
                        <input type="text" name="recherche" placeholder="Rechercher un produit..." value="<?php echo $search_term; ?>" style="padding: 8px 12px; border: 1px solid var(--primary-color); border-radius: 5px 0 0 5px; background-color: rgba(255, 255, 255, 0.1); color: white;">
                        <button type="submit" style="padding: 8px 12px; background-color: var(--primary-color); color: var(--secondary-color); border: 1px solid var(--primary-color); border-left: none; border-radius: 0 5px 5px 0; cursor: pointer;">🔍</button>
                    </div>
                </form>
            </div>
            
            <?php if(empty($products)): ?>
            <div style="text-align: center; padding: 50px 0;">
                <p>Aucun produit ne correspond à votre recherche.</p>
                <a href="eshop.php" class="btn btn-outline" style="margin-top: 20px;">Voir tous les produits</a>
            </div>
            <?php else: ?>
            <div class="products-grid">
                <?php foreach($products as $product): ?>
                <div class="product-card" data-category="<?php echo $product['categorie_slug']; ?>">
                    <?php if($product['est_nouveau']): ?>
                    <div class="product-badge">Nouveau</div>
                    <?php elseif($product['est_populaire']): ?>
                    <div class="product-badge">Populaire</div>
                    <?php endif; ?>
                    
                    <div class="product-img-container">
                        <img src="assets/images/<?php echo $product['image_url']; ?>" alt="<?php echo $product['nom']; ?>" class="product-img">
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-name"><?php echo $product['nom']; ?></h3>
                        <p class="product-price"><?php echo format_price($product['prix']); ?></p>
                        <div class="product-actions">
                            <a href="produit.php?slug=<?php echo $product['slug']; ?>" class="btn btn-small btn-outline">Détails</a>
                            <form class="add-to-cart-form" action="includes/cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-small">Ajouter</button>
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    
    <section class="section section-dark">
        <div class="container">
            <h2 class="section-title">Informations</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px; max-width: 1200px; margin: 0 auto;">
                <div style="background-color: var(--secondary-color); border-radius: 10px; padding: 30px 20px; text-align: center; border: 1px solid var(--primary-color); transition: transform 0.3s;">
                    <div style="font-size: 2.5rem; margin-bottom: 15px;">🚚</div>
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.2rem;">Livraison Rapide</h3>
                    <p style="opacity: 0.8; font-size: 0.95rem;">Livraison gratuite en France à partir de 50€</p>
                </div>
                
                <div style="background-color: var(--secondary-color); border-radius: 10px; padding: 30px 20px; text-align: center; border: 1px solid var(--primary-color); transition: transform 0.3s;">
                    <div style="font-size: 2.5rem; margin-bottom: 15px;">↩️</div>
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.2rem;">Retours Faciles</h3>
                    <p style="opacity: 0.8; font-size: 0.95rem;">Retours gratuits sous 30 jours</p>
                </div>
                
                <div style="background-color: var(--secondary-color); border-radius: 10px; padding: 30px 20px; text-align: center; border: 1px solid var(--primary-color); transition: transform 0.3s;">
                    <div style="font-size: 2.5rem; margin-bottom: 15px;">🔒</div>
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.2rem;">Paiement Sécurisé</h3>
                    <p style="opacity: 0.8; font-size: 0.95rem;">Vos données sont protégées</p>
                </div>
                
                <div style="background-color: var(--secondary-color); border-radius: 10px; padding: 30px 20px; text-align: center; border: 1px solid var(--primary-color); transition: transform 0.3s;">
                    <div style="font-size: 2.5rem; margin-bottom: 15px;">💫</div>
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.2rem;">Qualité Garantie</h3>
                    <p style="opacity: 0.8; font-size: 0.95rem;">Matériaux premium pour nos produits</p>
                </div>
            </div>
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