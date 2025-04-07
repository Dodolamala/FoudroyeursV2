<?php
/**
 * Page partenaires des Foudroyeurs
 */
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/cart.php';

// Récupérer tous les partenaires
$partners = get_partners($db);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez les partenaires officiels des Foudroyeurs - Nos sponsors et collaborateurs">
    <title>Nos Partenaires - Les Foudroyeurs</title>
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
                <li><a href="eshop.php">E-Shop</a></li>
                <li><a href="partenaires.php" class="active">Partenaires</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="panier.php">Panier <?php if(isset($_SESSION['cart']) && $_SESSION['cart']['count'] > 0): ?><span class="cart-count"><?php echo $_SESSION['cart']['count']; ?></span><?php endif; ?></a></li>
            </ul>
            <button class="mobile-menu-btn">☰</button>
        </nav>
        
        <div class="hero-content">
            <h1 class="hero-title">Nos Partenaires</h1>
            <p class="hero-subtitle">Ceux qui nous font confiance et nous accompagnent dans notre réussite</p>
        </div>
    </header>
    
    <section class="section">
        <div class="container">
            <h2 class="section-title">Partenaires Officiels</h2>
            
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px;">
                <p>Nous sommes fiers de collaborer avec des marques prestigieuses qui partagent nos valeurs et notre vision. 
                Ces partenariats nous permettent de continuer à innover et à offrir le meilleur à notre communauté.</p>
            </div>
            
            <div class="partners-grid">
                <?php foreach($partners as $partner): ?>
                <div class="partner-card">
                    <img src="assets/images/<?php echo $partner['logo_url']; ?>" alt="<?php echo $partner['nom']; ?>" class="partner-logo">
                    <h3 class="partner-name"><?php echo $partner['nom']; ?></h3>
                    <p class="partner-description"><?php echo $partner['description']; ?></p>
                    <?php if(!empty($partner['lien'])): ?>
                    <a href="<?php echo $partner['lien']; ?>" class="btn btn-small btn-outline" style="margin-top: 15px;" target="_blank">Visiter le site</a>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <section class="section section-dark">
        <div class="container">
            <h2 class="section-title">Devenez Partenaire</h2>
            
            <div style="max-width: 800px; margin: 0 auto; text-align: center;">
                <p style="margin-bottom: 20px;">
                    Rejoignez l'aventure des Foudroyeurs et associez votre marque à une équipe en pleine ascension dans l'univers du e-sport.
                    Nous proposons différentes formules de partenariat adaptées à vos objectifs et à votre budget.
                </p>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; margin: 40px 0;">
                    <div style="background-color: rgba(34, 34, 34, 0.6); border-radius: 10px; padding: 20px; border: 1px solid var(--primary-color);">
                        <h3 style="color: var(--primary-color); margin-bottom: 10px;">Visibilité</h3>
                        <p>Exposition de votre marque sur nos maillots, nos réseaux sociaux et lors des compétitions nationales et internationales.</p>
                    </div>
                    
                    <div style="background-color: rgba(34, 34, 34, 0.6); border-radius: 10px; padding: 20px; border: 1px solid var(--primary-color);">
                        <h3 style="color: var(--primary-color); margin-bottom: 10px;">Communauté</h3>
                        <p>Accès à notre communauté de fans passionnés et engagement auprès d'une audience jeune et connectée.</p>
                    </div>
                    
                    <div style="background-color: rgba(34, 34, 34, 0.6); border-radius: 10px; padding: 20px; border: 1px solid var(--primary-color);">
                        <h3 style="color: var(--primary-color); margin-bottom: 10px;">Innovation</h3>
                        <p>Association à des valeurs d'excellence, d'innovation et de performance dans un secteur en pleine croissance.</p>
                    </div>
                </div>
                
                <a href="contact.php?sujet=sponsoring" class="btn">Nous contacter pour un partenariat</a>
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