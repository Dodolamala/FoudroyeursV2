<?php
/**
 * Page d'accueil du site Les Foudroyeurs
 */
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/cart.php';

// Récupérer les membres de l'équipe pour la page d'accueil
$team_members = get_team_members($db, 3);

// Récupérer les palmarès pour la page d'accueil
$achievements = get_achievements($db, 3);

// Récupérer les produits populaires pour la page d'accueil
$popular_products = get_products($db, ['populaire' => true, 'limit' => 4]);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Les Foudroyeurs - Équipe e-sport professionnelle de League of Legends">
    <title>Les Foudroyeurs - Équipe E-Sport Professionnelle</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
    <header>
        <video autoplay loop muted playsinline class="background-video">
            <source src="assets/videos/clip bao.webm" type="video/webm">
            <!-- Ajouter une source alternative en mp4 -->
        </video>
        <div class="overlay"></div>
        
        <nav>
            <a href="index.php" class="logo">
                <span class="logo-icon">⚡</span>
                Les Foudroyeurs
            </a>
            <ul class="nav-links">
                <li><a href="index.php" class="active">Accueil</a></li>
                <li><a href="equipe.php">Équipe</a></li>
                <li><a href="palmares.php">Palmarès</a></li>
                <li><a href="eshop.php">E-Shop</a></li>
                <li><a href="partenaires.php">Partenaires</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="panier.php">Panier <?php if(isset($_SESSION['cart']) && $_SESSION['cart']['count'] > 0): ?><span class="cart-count"><?php echo $_SESSION['cart']['count']; ?></span><?php endif; ?></a></li>
            </ul>
            <button class="mobile-menu-btn">☰</button>
        </nav>
        
        <div class="hero-content">
            <h1 class="hero-title">Les Foudroyeurs</h1>
            <p class="hero-subtitle">L'équipe e-sport qui frappe aussi vite que l'éclair</p>
            <a href="equipe.php" class="btn">Découvrir l'équipe</a>
        </div>
    </header>
    
    <section id="palmares" class="section">
        <div class="container">
            <h2 class="section-title">Nos Succès</h2>
            
            <div class="achievements-list">
                <?php foreach($achievements as $achievement): ?>
                <div class="achievement">
                    <div class="achievement-logo">
                        <span><?php echo $achievement['classement']; ?></span>
                    </div>
                    <div class="achievement-details">
                        <h3 class="achievement-title"><?php echo $achievement['titre']; ?></h3>
                        <p class="achievement-date"><?php echo date('F Y', strtotime($achievement['date_obtention'])); ?></p>
                        <p><?php echo $achievement['description']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="palmares.php" class="btn btn-outline">Voir tout notre palmarès</a>
            </div>
        </div>
    </section>
    
    <section id="eshop" class="section section-dark">
        <div class="container">
            <h2 class="section-title">Notre E-Shop</h2>
            
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px;">
                <p>Découvrez notre collection exclusive de produits dérivés aux couleurs des Foudroyeurs et supportez votre équipe favorite !</p>
            </div>
            
            <div class="products-grid">
                <?php foreach($popular_products as $product): ?>
                <div class="product-card">
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
                            <form class="add-to-cart-form" action="includes/cart.php" method="post">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-small">Ajouter au panier</button>
                                <input type="hidden" name="cart_action" value="add">
                            </form>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="text-align: center; margin-top: 40px;">
                <a href="eshop.php" class="btn btn-outline">Voir tout notre catalogue</a>
            </div>
        </div>
    </section>
    
    <section id="contact" class="section">
        <div class="container">
            <h2 class="section-title">Contactez-nous</h2>
            
            <div style="text-align: center; max-width: 600px; margin: 0 auto 40px;">
                <p>Vous souhaitez rejoindre notre équipe, nous sponsoriser ou simplement nous contacter ? Laissez-nous un message !</p>
            </div>
            
            <?php if(isset($_SESSION['message'])): ?>
            <div class="message message-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
            <?php endif; ?>
            
            <form class="form" action="includes/process_contact.php" method="post" data-ajax="true" data-reset-on-success="true">
                <div class="form-row">
                    <input type="text" name="nom" placeholder="Votre nom" class="form-input" required>
                </div>
                <div class="form-row">
                    <input type="email" name="email" placeholder="Votre email" class="form-input" required>
                </div>
                <div class="form-row">
                    <select name="sujet" class="form-input" required>
                        <option value="">Sélectionnez un sujet</option>
                        <option value="sponsoring">Sponsoring</option>
                        <option value="recrutement">Recrutement</option>
                        <option value="presse">Presse</option>
                        <option value="fans">Fans</option>
                        <option value="autre">Autre</option>
                    </select>
                </div>
                <div class="form-row">
                    <textarea name="message" placeholder="Votre message" class="form-input" required></textarea>
                </div>
                <div class="form-row">
                    <button type="submit" class="form-submit">Envoyer</button>
                </div>
            </form>
            
            <div class="social-links">
                <a href="#" class="social-link">T</a>
                <a href="#" class="social-link">F</a>
                <a href="#" class="social-link">I</a>
                <a href="#" class="social-link">D</a>
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