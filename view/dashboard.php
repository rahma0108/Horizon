<?php
$siteTitle = "Fitsense - Club Multisports";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($siteTitle); ?></title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            background-position: center;
            color: #fff;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden; /* Prevent horizontal scroll */
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 50px;
            background: rgba(0, 0, 0, 0.85);
            z-index: 1000;
            transition: all 0.3s ease-in-out;
        }

        .navbar .logo {
            font-size: 2rem;
            font-weight: 700;
            color: #c5ff38;
        }

        .navbar .menu a {
            color: #fff;
            text-decoration: none;
            margin: 0 15px;
            font-size: 1rem;
            transition: color 0.3s ease-in-out;
        }

        .navbar .menu a:hover {
            color: #c5ff38;
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            z-index: 1;
            animation: fadeIn 1.5s ease-in-out;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 30px;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        }

        .hero .btn-group {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .hero a {
            background: #c5ff38;
            color: #000;
            padding: 15px 30px;
            border-radius: 25px;
            font-size: 1.2rem;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease-in-out;
        }

        .hero a:hover {
            background: #a8d82e;
            transform: translateY(-5px);
        }

        /* Navbar & Hero Responsiveness */
        @media (max-width: 768px) {
            .navbar {
                padding: 10px 20px;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero a {
                font-size: 1rem;
                padding: 12px 25px;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 10px 15px;
            }

            .hero h1 {
                font-size: 2rem;
                margin-bottom: 15px;
            }

            .hero .btn-group {
                flex-direction: column;
            }

            .hero a {
                margin-bottom: 15px;
            }
        }

        /* Animation for smooth fade-in */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Greenmove</div>
        <div class="menu">
            <a href="#">Accueil</a>
            <a href="#">actualite</a>
            <a href="#">Événements</a>
            <a href="#">forum</a>
            <a href="#">magasin</a>
            <a href="#">Activités</a>
        </div>
    </nav>
    
    <!-- Hero Section -->
    <section class="hero">
        <h1>Vivez le Sport Autrement</h1>
        <div class="btn-group">
            <!-- Lien vers la page de connexion (front2.php) -->
            <a href="front2_recaptcha.php">Se connecter</a>
            <a href="inscrire.php">S'inscrire</a>
        </div>
    </section>

</body>
</html>
