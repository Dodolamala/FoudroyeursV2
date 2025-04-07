<?php
/**
 * Page du palmarès des Foudroyeurs
 */
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/cart.php';

// Récupérer tous les palmarès
$achievements = get_achievements($db);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Découvrez le palmarès et les victoires de l'équipe e-sport Les Foudroyeurs">
    <title>Notre Palmarès - Les Foudroyeurs</title>
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
                <li><a href="palmares.php" class="active">Palmarès</a></li>
                <li><a href="eshop.php">E-Shop</a></li>
                <li><a href="partenaires.php">Partenaires</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="panier.php">Panier <?php if(isset($_SESSION['cart']) && $_SESSION['cart']['count'] > 0): ?><span class="cart-count"><?php echo $_SESSION['cart']['count']; ?></span><?php endif; ?></a></li>
            </ul>
            <button class="mobile-menu-btn">☰</button>
        </nav>
        
        <div class="hero-content">
            <h1 class="hero-title">Notre Palmarès</h1>
            <p class="hero-subtitle">Nos victoires et accomplissements à travers le monde</p>
        </div>
    </header>
    
    <section class="section">
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
        </div>
    </section>
    
    <section class="section section-dark">
        <div class="container">
            <h2 class="section-title">Notre Histoire</h2>
            
            <div style="max-width: 800px; margin: 0 auto;">
                <p style="margin-bottom: 20px;">
                    Fondée en 2024, l'équipe Les Foudroyeurs s'est rapidement imposée comme une force dominante dans l'univers de League of Legends. 
                    Notre parcours est marqué par un engagement constant envers l'excellence et l'innovation.
                </p>
                
                <p style="margin-bottom: 20px;">
                    Dès nos débuts, nous avons remporté plusieurs tournois régionaux, nous propulsant sur la scène nationale puis internationale. 
                    Notre stratégie unique et notre cohésion d'équipe nous ont permis d'atteindre des sommets que peu d'équipes ont pu conquérir 
                    en si peu de temps.
                </p>
                
                <p>
                    Avec une équipe composée de talents exceptionnels et une direction visionnaire, nous continuons à écrire 
                    notre histoire et à élargir notre palmarès. Les Foudroyeurs représentent plus qu'une simple équipe : 
                    nous incarnons une nouvelle approche du jeu, alliant stratégie innovante et exécution parfaite.
                </p>
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