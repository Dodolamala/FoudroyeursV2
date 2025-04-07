<?php
/**
 * Page de contact des Foudroyeurs
 */
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/cart.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contactez l'équipe Les Foudroyeurs - Demandes d'informations, sponsoring, partenariats ou recrutement">
    <title>Contact - Les Foudroyeurs</title>
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
            <li><a href="contact.php" class="active">Contact</a></li>
            <li><a href="panier.php">Panier <?php if(isset($_SESSION['cart']) && $_SESSION['cart']['count'] > 0): ?><span class="cart-count"><?php echo $_SESSION['cart']['count']; ?></span><?php endif; ?></a></li>
        </ul>
        <button class="mobile-menu-btn">☰</button>
    </nav>
    
    <section class="section" style="margin-top: 80px;">
        <div class="container">
            <h1 class="section-title">Contactez-nous</h1>
            
            <div style="text-align: center; max-width: 700px; margin: 0 auto 40px;">
                <p>Vous souhaitez rejoindre notre équipe, nous sponsoriser ou simplement nous contacter ? Laissez-nous un message et nous vous répondrons dans les plus brefs délais.</p>
            </div>
            
            <?php if(isset($_SESSION['message'])): ?>
            <div class="message message-<?php echo $_SESSION['message_type']; ?>">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
            </div>
            <?php endif; ?>
            
            <div style="display: flex; flex-wrap: wrap; gap: 50px; max-width: 1200px; margin: 0 auto;">
                <div style="flex: 1; min-width: 300px;">
                    <h2 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.8rem;">Envoyez-nous un message</h2>
                    
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
                </div>
                
                <div style="flex: 1; min-width: 300px;">
                    <h2 style="color: var(--primary-color); margin-bottom: 20px; font-size: 1.8rem;">Informations de contact</h2>
                    
                    <div style="margin-bottom: 30px;">
                        <h3 style="color: var(--accent-color); margin-bottom: 10px; font-size: 1.2rem;">Adresse</h3>
                        <p>Les Foudroyeurs</p>
                        <p>123 Avenue des Champions</p>
                        <p>75001 Paris, France</p>
                    </div>
                    
                    <div style="margin-bottom: 30px;">
                        <h3 style="color: var(--accent-color); margin-bottom: 10px; font-size: 1.2rem;">Coordonnées</h3>
                        <p>Email: contact@foudroyeurs.fr</p>
                        <p>Tél: 01 23 45 67 89</p>
                    </div>
                    
                    <div>
                        <h3 style="color: var(--accent-color); margin-bottom: 10px; font-size: 1.2rem;">Réseaux sociaux</h3>
                        <div class="social-links" style="justify-content: flex-start; margin-top: 10px;">
                            <a href="#" class="social-link">T</a>
                            <a href="#" class="social-link">F</a>
                            <a href="#" class="social-link">I</a>
                            <a href="#" class="social-link">D</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="section section-dark">
        <div class="container">
            <h2 class="section-title">Foire aux questions</h2>
            
            <div style="max-width: 800px; margin: 0 auto;">
                <div style="margin-bottom: 30px;">
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.3rem;">Comment rejoindre votre équipe ?</h3>
                    <p>Pour rejoindre notre équipe, veuillez utiliser le formulaire ci-dessus en sélectionnant "Recrutement" comme sujet. N'oubliez pas de nous partager votre expérience, vos statistiques et vos motivations.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.3rem;">Comment devenir partenaire ?</h3>
                    <p>Pour les demandes de partenariat ou de sponsoring, veuillez nous contacter via le formulaire en choisissant "Sponsoring" comme sujet. Notre équipe marketing vous répondra dans les plus brefs délais.</p>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.3rem;">Possédez-vous un discord officiel ?</h3>
                    <p>Oui, nous avons un Discord officiel où vous pouvez discuter avec notre communauté. Rejoignez-nous sur discord.gg/foudroyeurs pour ne rien manquer de nos actualités !</p>
                </div>
                
                <div>
                    <h3 style="color: var(--primary-color); margin-bottom: 10px; font-size: 1.3rem;">Quels sont vos délais de livraison ?</h3>
                    <p>Pour les commandes de notre e-shop, les délais de livraison sont généralement de 3 à 5 jours ouvrés en France métropolitaine, et de 5 à 10 jours pour l'international.</p>
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