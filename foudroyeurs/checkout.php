<?php
/**
 * Page de paiement (checkout) des Foudroyeurs
 */
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/cart.php';

// Récupérer le contenu du panier
$cart = get_cart();

// Rediriger si le panier est vide
if (count($cart['items']) === 0) {
    set_message_and_redirect("Votre panier est vide. Ajoutez des produits avant de passer à la caisse.", "panier.php", "error");
}

// Vérifier si l'utilisateur est connecté
$user_logged_in = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Finalisez votre commande - E-Shop officiel des Foudroyeurs">
    <title>Finaliser la commande - Les Foudroyeurs</title>
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
            <li><a href="panier.php">Panier <?php if($cart['count'] > 0): ?><span class="cart-count"><?php echo $cart['count']; ?></span><?php endif; ?></a></li>
        </ul>
        <button class="mobile-menu-btn">☰</button>
    </nav>
    
    <section class="section" style="margin-top: 80px;">
        <div class="container">
            <h1 class="section-title">Finaliser votre commande</h1>
            
            <?php if(isset($_SESSION['message'])): ?>
            <div class="message message-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
            <?php endif; ?>
            
            <div style="max-width: 1000px; margin: 0 auto;">
                <div style="display: flex; flex-wrap: wrap; gap: 30px;">
                    <!-- Formulaire de commande -->
                    <div style="flex: 3; min-width: 300px;">
                        <div style="background-color: rgba(34, 34, 34, 0.6); border-radius: 10px; padding: 20px; border: 1px solid var(--primary-color); margin-bottom: 20px;">
                            <h2 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.5rem;">Informations personnelles</h2>
                            
                            <form id="checkout-form" action="includes/process_order.php" method="post">
                                <!-- Email (requis pour tous) -->
                                <div class="form-row">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" class="form-input" value="<?php echo $user_logged_in ? $_SESSION['user_email'] : ''; ?>" required>
                                </div>
                                
                                <!-- Nom et prénom -->
                                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                    <div class="form-row" style="flex: 1; min-width: 120px;">
                                        <label for="prenom">Prénom</label>
                                        <input type="text" id="prenom" name="prenom" class="form-input" value="<?php echo $user_logged_in ? $_SESSION['user_prenom'] : ''; ?>" required>
                                    </div>
                                    
                                    <div class="form-row" style="flex: 1; min-width: 120px;">
                                        <label for="nom">Nom</label>
                                        <input type="text" id="nom" name="nom" class="form-input" value="<?php echo $user_logged_in ? $_SESSION['user_nom'] : ''; ?>" required>
                                    </div>
                                </div>
                                
                                <!-- Téléphone -->
                                <div class="form-row">
                                    <label for="telephone">Téléphone</label>
                                    <input type="tel" id="telephone" name="telephone" class="form-input" required>
                                </div>
                            </div>
                            
                            <div style="background-color: rgba(34, 34, 34, 0.6); border-radius: 10px; padding: 20px; border: 1px solid var(--primary-color); margin-bottom: 20px;">
                                <h2 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.5rem;">Adresse de livraison</h2>
                                
                                <!-- Adresse -->
                                <div class="form-row">
                                    <label for="adresse">Adresse</label>
                                    <input type="text" id="adresse" name="adresse" class="form-input" required>
                                </div>
                                
                                <!-- Complément d'adresse (optionnel) -->
                                <div class="form-row">
                                    <label for="complement">Complément d'adresse (optionnel)</label>
                                    <input type="text" id="complement" name="complement" class="form-input">
                                </div>
                                
                                <!-- Code postal et ville -->
                                <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                    <div class="form-row" style="flex: 1; min-width: 100px;">
                                        <label for="code_postal">Code postal</label>
                                        <input type="text" id="code_postal" name="code_postal" class="form-input" required>
                                    </div>
                                    
                                    <div class="form-row" style="flex: 2; min-width: 150px;">
                                        <label for="ville">Ville</label>
                                        <input type="text" id="ville" name="ville" class="form-input" required>
                                    </div>
                                </div>
                                
                                <!-- Pays -->
                                <div class="form-row">
                                    <label for="pays">Pays</label>
                                    <select id="pays" name="pays" class="form-input" required>
                                        <option value="France" selected>France</option>
                                        <option value="Belgique">Belgique</option>
                                        <option value="Suisse">Suisse</option>
                                        <option value="Luxembourg">Luxembourg</option>
                                        <option value="Canada">Canada</option>
                                        <option value="Autre">Autre</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div style="background-color: rgba(34, 34, 34, 0.6); border-radius: 10px; padding: 20px; border: 1px solid var(--primary-color);">
                                <h2 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.5rem;">Mode de paiement</h2>
                                
                                <!-- Mode de paiement -->
                                <div class="form-row">
                                    <div style="margin-bottom: 15px;">
                                        <input type="radio" id="carte" name="paiement" value="carte" checked style="margin-right: 5px;">
                                        <label for="carte">Carte bancaire</label>
                                    </div>
                                    
                                    <div style="margin-bottom: 15px;">
                                        <input type="radio" id="paypal" name="paiement" value="paypal" style="margin-right: 5px;">
                                        <label for="paypal">PayPal</label>
                                    </div>
                                </div>
                                
                                <!-- Informations de carte (affichées conditionnellement avec JavaScript) -->
                                <div id="carte-info">
                                    <div class="form-row">
                                        <label for="carte_numero">Numéro de carte</label>
                                        <input type="text" id="carte_numero" name="carte_numero" class="form-input" placeholder="1234 5678 9012 3456">
                                    </div>
                                    
                                    <div style="display: flex; gap: 15px; flex-wrap: wrap;">
                                        <div class="form-row" style="flex: 1; min-width: 100px;">
                                            <label for="carte_expiration">Date d'expiration</label>
                                            <input type="text" id="carte_expiration" name="carte_expiration" class="form-input" placeholder="MM/AA">
                                        </div>
                                        
                                        <div class="form-row" style="flex: 1; min-width: 100px;">
                                            <label for="carte_cvv">CVV</label>
                                            <input type="text" id="carte_cvv" name="carte_cvv" class="form-input" placeholder="123">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Note de commande (optionnel) -->
                                <div class="form-row" style="margin-top: 20px;">
                                    <label for="note">Note de commande (optionnel)</label>
                                    <textarea id="note" name="note" class="form-input" placeholder="Instructions particulières pour la livraison..."></textarea>
                                </div>
                                
                                <div class="form-row" style="margin-top: 20px;">
                                    <input type="checkbox" id="cgu" name="cgu" required style="margin-right: 5px;">
                                    <label for="cgu">J'accepte les <a href="#" style="color: var(--primary-color);">conditions générales de vente</a></label>
                                </div>
                            </div>
                    </div>
                    
                    <!-- Résumé de la commande -->
                    <div style="flex: 2; min-width: 300px;">
                        <div style="background-color: rgba(34, 34, 34, 0.6); border-radius: 10px; padding: 20px; border: 1px solid var(--primary-color); position: sticky; top: 100px;">
                            <h2 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.5rem;">Résumé de la commande</h2>
                            
                            <!-- Articles -->
                            <div style="margin-bottom: 20px;">
                                <?php foreach($cart['items'] as $item): ?>
                                <div style="display: flex; margin-bottom: 15px; border-bottom: 1px solid rgba(255, 255, 255, 0.1); padding-bottom: 15px;">
                                    <div style="width: 60px; height: 60px; margin-right: 10px;">
                                        <img src="assets/images/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 5px;">
                                    </div>
                                    <div style="flex-grow: 1;">
                                        <p style="margin: 0 0 5px 0; font-weight: 600;"><?php echo $item['name']; ?></p>
                                        <?php if(!empty($item['option_name'])): ?>
                                        <p style="margin: 0 0 5px 0; font-size: 0.9rem; color: var(--accent-color);"><?php echo $item['option_name']; ?></p>
                                        <?php endif; ?>
                                        <div style="display: flex; justify-content: space-between;">
                                            <p style="margin: 0; font-size: 0.9rem;">Qté: <?php echo $item['quantity']; ?></p>
                                            <p style="margin: 0; font-weight: 600;"><?php echo format_price($item['price'] * $item['quantity']); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Sous-total -->
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem;">
                                <span>Sous-total</span>
                                <span><?php echo format_price($cart['total']); ?></span>
                            </div>
                            
                            <!-- Livraison -->
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem;">
                                <span>Frais de livraison</span>
                                <span><?php echo $cart['total'] >= 50 ? 'Gratuit' : format_price(5.99); ?></span>
                            </div>
                            
                            <!-- TVA -->
                            <div style="display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 0.95rem;">
                                <span>TVA (20%)</span>
                                <span><?php echo format_price($cart['total'] * 0.2); ?></span>
                            </div>
                            
                            <!-- Total -->
                            <div style="display: flex; justify-content: space-between; margin-bottom: 25px; font-size: 1.2rem; font-weight: 700; color: var(--primary-color);">
                                <span>Total</span>
                                <span>
                                    <?php 
                                    $shipping = ($cart['total'] >= 50) ? 0 : 5.99;
                                    $total = $cart['total'] + $shipping;
                                    echo format_price($total); 
                                    ?>
                                </span>
                            </div>
                            
                            <!-- Bouton de commande -->
                            <button type="submit" form="checkout-form" class="btn" style="width: 100%; font-size: 1.1rem; padding: 15px;">
                                Passer la commande
                            </button>
                            
                            <!-- Note sur la sécurité -->
                            <p style="text-align: center; margin-top: 15px; font-size: 0.9rem; opacity: 0.7;">
                                <span style="margin-right: 5px;">🔒</span> Paiement sécurisé
                            </p>
                            
                            <!-- Retour au panier -->
                            <div style="text-align: center; margin-top: 20px;">
                                <a href="panier.php" style="color: var(--accent-color); text-decoration: underline;">
                                    Retour au panier
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
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
    <script>
        // Script pour afficher/masquer les informations de carte selon le mode de paiement
        document.addEventListener('DOMContentLoaded', function() {
            const carteRadio = document.getElementById('carte');
            const paypalRadio = document.getElementById('paypal');
            const carteInfo = document.getElementById('carte-info');
            
            function toggleCarteInfo() {
                if (carteRadio.checked) {
                    carteInfo.style.display = 'block';
                } else {
                    carteInfo.style.display = 'none';
                }
            }
            
            carteRadio.addEventListener('change', toggleCarteInfo);
            paypalRadio.addEventListener('change', toggleCarteInfo);
            
            // Initialiser l'affichage
            toggleCarteInfo();
        });
    </script>
</body>
</html>