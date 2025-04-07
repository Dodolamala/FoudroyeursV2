<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'ESPORTS';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les membres de l'équipe
function get_team_members($db) {
    try {
        $stmt = $db->query("SELECT * FROM membres_equipe WHERE equipe_id = 1");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
        return [];
    }
}

// Récupérer les membres
$team_members = get_team_members($db);

// Initialiser la session si pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LES FOUDROYEURS - Équipe E-Sport Professionnelle</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>
<nav>
            <a href="index.php" class="logo">
                <span class="logo-icon">⚡</span>
                Les Foudroyeurs
            </a>
            <ul class="nav-links">
                <li><a href="index.php">Accueil</a></li>
                <li><a href="equipe.php" class="active">Équipe</a></li>
                <li><a href="palmares.php">Palmarès</a></li>
                <li><a href="eshop.php">E-Shop</a></li>
                <li><a href="partenaires.php">Partenaires</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="panier.php">Panier <?php if(isset($_SESSION['cart']) && $_SESSION['cart']['count'] > 0): ?><span class="cart-count"><?php echo $_SESSION['cart']['count']; ?></span><?php endif; ?></a></li>
            </ul>
            <button class="mobile-menu-btn">☰</button>
        </nav>
    <section id="equipe" class="section team">
        <h2 class="section-title">Notre Équipe</h2>
        <div class="team-grid">
            <div class="team-member">
                <img src="assets/images/loïs.png" alt="lois" class="member-img">
                <div class="member-info">
                    <h3 class="member-name">Loïs "PalpaMX"  GUILLOUX</h3>
                    <p class="member-role">Support / CEO</p>
                    <p class="member-bio">Avec ses réflexes foudroyants et son leadership naturel,Loïs  guide notre équipe vers la victoire depuis sa création en 2024.</p>
                </div>
            </div>
            
            <div class="team-member">
                <img src="assets/images/matthieu.png" alt="matthieu" class="member-img">
                <div class="member-info">
                    <h3 class="member-name">Matthieu "PoiujK" JANVIER</h3>
                    <p class="member-role">Top lane</p>
                    <p class="member-bio">Véritable génie tactique, Matthieu anticipe les mouvements adverses et élabore des stratégies qui ont fait trembler les plus grandes équipes internationales.</p>
                </div>
            </div>
            
            <div class="team-member">
                <img src="assets/images/charlotte.png" alt="charlotte" class="member-img">
                <div class="member-info">
                    <h3 class="member-name">Charlotte "Foudroyeur2tass" BOUVET</h3>
                    <p class="member-role">Mid lane</p>
                    <p class="member-bio">Joueuse de Mid lane comparable à Faker, maîtrisant parfaitement sa lane et qui domine en teamfight grâce à une mécanique irréprochable et une vision de jeu d'élite.</p>
                </div>
            </div>
            
            <div class="team-member">
                <img src="assets/images/nahian.png" alt="nahian" class="member-img">
                <div class="member-info">
                    <h3 class="member-name">Nahian "Nahian22" CHISTY</h3>
                    <p class="member-role">Jungle</p>
                    <p class="member-bio">Malgré le fait qu'il trash ses mates, c'est un joueur de jungle redoutable, avec une capacité inégalée à contrôler la map en permettant grâce à des ganks décisifs. </p>
                </div>
            </div>

            <div class="team-member">
                <img src="assets/images/bao.png" alt="bao" class="member-img">
                <div class="member-info">
                    <h3 class="member-name">Bao-Long "bao v1" LE</h3>
                    <p class="member-role">bot lane</p>
                    <p class="member-bio">Un ADC d'exception qui sait infliger un maximum de pression à l'ennemi grâce à une gestion parfaite de la phase de lane et des objectifs. Il devient souvent la clé de la victoire de son équipe. </p>
                </div>
            </div>
            <div class="team-member">
                <img src="assets/images/dorian.png" alt="dorian" class="member-img">
                <div class="member-info">
                    <h3 class="member-name">Dorian "dodolamala" Lachasse</h3>
                    <p class="member-role">CEO</p>
                    <p class="member-bio">Expert en prompt et en réseau.</p>
                </div>
            </div>
            <div class="team-member">
                <img src="assets/images/kv.png" alt="bkv" class="member-img">
                <div class="member-info">
                    <h3 class="member-name">KV "aka KV" KV</h3>
                    <p class="member-role">CEO</p>
                    <p class="member-bio">Expert en volskwagen et drifter a ces heures perdues</p>
                </div>
            </div>
        </div>
    </section>
    
</body>
</html>