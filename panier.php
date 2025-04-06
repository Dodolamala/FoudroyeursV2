<?php
/**
 * Page panier des Foudroyeurs
 */
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/cart.php';

// Récupérer le contenu du panier
$cart = get_cart();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Votre panier d'achats - E-Shop officiel des Foudroyeurs">
    <title>Panier - Les Foudroyeurs</title>
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
            <li><a href="eshop.php">E-Shop</a></li>
            <li><a href="partenaires.php">Partenaires</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="panier.php" class="active">Panier <?php if($cart['count'] > 0): ?><span class="cart-count"><?php echo $cart['count']; ?></span><?php endif; ?></a></li>
        </ul>
        <button class="mobile-menu-btn">☰</button>
    </nav>
    
    <section class="section" style="margin-top: 80px;">
        <div class="container">
            <h1 class="section-title">Votre Panier</h1>
            
            <?php if(isset($_SESSION['message'])): ?>
            <div class="message message-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
            <?php endif; ?>
            
            <div class="empty-cart-message" style="text-align: center; margin: 50px 0; display: <?php echo count($cart['items']) === 0 ? 'block' : 'none'; ?>">
                <p>Votre panier est vide.</p>
                <a href="eshop.php" class="btn" style="margin-top: 20px;">Découvrir nos produits</a>
            </div>
            
            <div class="cart-content" style="display: <?php echo count($cart['items']) > 0 ? 'block' : 'none'; ?>">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cart['items'] as $item_id => $item): ?>
                        <tr class="cart-item">
                            <td data-label="Produit">
                                <div style="display: flex; align-items: center;">
                                    <img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-item-image">
                                    <div style="margin-left: 15px;">
                                        <h3 style="margin: 0 0 5px 0; color: var(--primary-color);"><?php echo $item['name']; ?></h3>
                                        <?php if(!empty($item['option_name'])): ?>
                                        <p style="font-size: 0.9rem; color: var(--accent-color);"><?php echo $item['option_name']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Prix" class="item-price" data-price="<?php echo $item['price']; ?>"><?php echo format_price($item['price']); ?></td>
                            <td data-label="Quantité">
                                <input type="number" class="cart-quantity" value="<?php echo $item['quantity']; ?>" min="1" max="10" data-item-id="<?php echo $item_id; ?>">
                            </td>
                            <td data-label="Total" class="item-subtotal"><?php echo format_price($item['price'] * $item['quantity']); ?></td>
                            <td>
                                <button class="remove-item" data-item-id="<?php echo $item_id; ?>">✕</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="cart-total">
                    <span style="font-size: 1.2rem; margin-right: 15px;">Total:</span>
                    <span class="cart-total-value"><?php echo format_price($cart['total']); ?></span>
                </div>
                
                <div class="cart-actions">
                    <a href="eshop.php" class="btn btn-outline">Continuer mes achats</a>
                    
                    <div>
                        <button class="btn btn-outline clear-cart" style="margin-right: 10px; color: var(--error-color); border-color: var(--error-color);">Vider le panier</button>
                        <a href="checkout.php" class="btn">Passer à la caisse</a>
                    </div>
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