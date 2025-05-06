<?php
require_once __DIR__ . '/../../Controller/CoursController.php';
require_once __DIR__ . '/../../Model/Cours.php';

$coursController = new CoursController();

// Gérer les actions
$action = isset($_GET['action']) ? $_GET['action'] : 'list';

// Gérer le tri par prix
$sortOrder = isset($_GET['sort']) ? $_GET['sort'] : '';
$validSortOrders = ['asc', 'desc'];
if (!in_array($sortOrder, $validSortOrders)) {
    $sortOrder = '';
}

// Gérer la recherche
$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
if (!empty($searchTerm)) {
    $cours = $coursController->searchCoursByType($searchTerm, $sortOrder);
} else {
    if (!empty($sortOrder)) {
        $cours = $coursController->listCoursWithSort($sortOrder);
    } else {
        $cours = $coursController->listCours();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Cours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Style moderne pour le front-office */
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background-color: #2c3e50;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            color: white !important;
            font-weight: bold;
            font-size: 1.5rem;
        }

        .course-card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .course-card:hover {
            transform: translateY(-5px);
        }

        .card-header {
            background-color: #3498db;
            color: white;
            font-weight: bold;
            padding: 15px;
        }

        .price-tag {
            background-color: #2ecc71;
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
        }

        .course-info {
            padding: 20px;
        }

        .course-date {
            color: #7f8c8d;
            font-size: 0.9rem;
        }

        .location-icon {
            color: #e74c3c;
        }

        .header-section {
            background-color: #2c3e50;
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section-description {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .search-container {
            background-color: rgba(52, 73, 94, 0.8);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .search-container h4 {
            font-weight: 600;
            margin-bottom: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .search-container .form-control {
            border: none;
            background-color: rgba(255, 255, 255, 0.95);
            height: 50px;
            font-size: 16px;
        }

        .search-container .btn-light {
            background-color: white;
            border-color: white;
            color: #2c3e50;
            font-weight: bold;
            height: 50px;
        }

        .search-container .btn-outline-light {
            border-color: white;
            color: white;
            font-weight: 500;
            padding: 8px 16px;
        }

        .search-container .btn-outline-light:hover {
            background-color: white;
            color: #2c3e50;
        }

        .course-card.highlight {
            border-left: 4px solid #3498db;
            animation: highlight 1.5s ease-in-out;
        }

        @keyframes highlight {
            0% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0.5); }
            70% { box-shadow: 0 0 0 10px rgba(52, 152, 219, 0); }
            100% { box-shadow: 0 0 0 0 rgba(52, 152, 219, 0); }
        }

        .no-results {
            padding: 30px;
            background-color: #f8f9fa;
            border-radius: 10px;
            text-align: center;
            margin: 20px 0;
        }

        /* Styles pour le bouton météo */
        .weather-btn {
            width: 32px;
            height: 32px;
            padding: 0;
            line-height: 30px;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .weather-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            background-color: #17a2b8;
            color: white;
        }

        /* Styles pour la modal météo */
        .weather-data {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
        }

        .weather-temp {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 0;
        }

        .weather-desc {
            text-transform: capitalize;
            color: #6c757d;
        }

        .weather-details p {
            margin-bottom: 10px;
            font-size: 1.1rem;
        }

        .forecast-day {
            background-color: white;
            border-radius: 8px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            text-align: center;
        }

        .forecast-date {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .forecast-temp {
            font-size: 1.2rem;
        }



        /* Styles pour le tri */
        .dropdown-menu {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: none;
            border-radius: 8px;
        }

        .dropdown-item {
            padding: 10px 15px;
        }

        .dropdown-item.active {
            background-color: #3498db;
            color: white;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item.active:hover {
            background-color: #2980b9;
        }

        .sort-indicator {
            display: inline-block;
            width: 20px;
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Centre de Formation</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Cours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="templateReservation.php">Réservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Header Section -->
    <div class="header-section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h1 class="section-title">Découvrez Nos Cours</h1>
                    <p class="section-description">
                        Explorez notre sélection de cours de qualité pour développer vos compétences
                    </p>
                </div>
                <div class="col-md-6">
                    <div class="search-container mt-4">
                        <h4 class="text-white mb-3"><i class="fas fa-search me-2"></i>Rechercher un cours</h4>
                        <form id="searchForm" action="" method="GET">
                            <div class="input-group">
                                <input type="text" id="searchInput" name="search" class="form-control form-control-lg"
                                       placeholder="Entrez le type de cours..."
                                       value="<?= htmlspecialchars($searchTerm) ?>">
                                <?php if (!empty($sortOrder)): ?>
                                <input type="hidden" name="sort" value="<?= htmlspecialchars($sortOrder) ?>">
                                <?php endif; ?>
                                <button type="submit" class="btn btn-light btn-lg">
                                    <i class="fas fa-search me-2"></i> Rechercher
                                </button>
                            </div>
                        </form>
                        <?php if (!empty($searchTerm)): ?>
                        <div class="mt-2 text-end">
                            <a href="template2.php" class="btn btn-outline-light">
                                <i class="fas fa-times me-2"></i> Réinitialiser
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Section -->
    <div class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <?php if (!empty($searchTerm)): ?>
                <div class="alert alert-info mb-0 flex-grow-1 me-3">
                    <i class="fas fa-filter me-2"></i>
                    <strong>Résultats pour "<?= htmlspecialchars($searchTerm) ?>"</strong>
                    (<?= count($cours) ?> cours trouvé(s))
                </div>
            <?php else: ?>
                <h3 class="mb-0">Tous les cours (<?= count($cours) ?>)</h3>
            <?php endif; ?>

            <div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-sort me-2"></i>
                    <?php if ($sortOrder === 'asc'): ?>
                        Prix: croissant
                    <?php elseif ($sortOrder === 'desc'): ?>
                        Prix: décroissant
                    <?php else: ?>
                        Trier par prix
                    <?php endif; ?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                    <li>
                        <a class="dropdown-item <?= $sortOrder === 'asc' ? 'active' : '' ?>"
                           href="template2.php?<?= !empty($searchTerm) ? 'search='.urlencode($searchTerm).'&' : '' ?>sort=asc">
                            <i class="fas fa-sort-amount-down-alt me-2"></i> Prix croissant
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item <?= $sortOrder === 'desc' ? 'active' : '' ?>"
                           href="template2.php?<?= !empty($searchTerm) ? 'search='.urlencode($searchTerm).'&' : '' ?>sort=desc">
                            <i class="fas fa-sort-amount-down me-2"></i> Prix décroissant
                        </a>
                    </li>
                    <?php if (!empty($sortOrder)): ?>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="template2.php<?= !empty($searchTerm) ? '?search='.urlencode($searchTerm) : '' ?>">
                            <i class="fas fa-times me-2"></i> Réinitialiser le tri
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <?php if (!empty($searchTerm)): ?>
            <div class="mb-3 text-end">
                <a href="template2.php<?= !empty($sortOrder) ? '?sort='.$sortOrder : '' ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-list me-1"></i> Voir tous les cours
                </a>
            </div>
        <?php endif; ?>

        <?php if (empty($cours)): ?>
            <div class="no-results">
                <i class="fas fa-search fa-3x mb-3 text-muted"></i>
                <h3>Aucun cours trouvé</h3>
                <?php if (!empty($searchTerm)): ?>
                    <p>Aucun cours ne correspond à votre recherche "<?= htmlspecialchars($searchTerm) ?>".</p>
                    <a href="template2.php" class="btn btn-primary mt-3">
                        <i class="fas fa-arrow-left me-2"></i>Retour à tous les cours
                    </a>
                <?php else: ?>
                    <p>Aucun cours n'est disponible pour le moment.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="row" id="coursesContainer">
                <?php foreach ($cours as $c): ?>
                <div class="col-md-4 course-item">
                    <div class="card course-card <?= !empty($searchTerm) ? 'highlight' : '' ?>">
                        <div class="card-header">
                            <h5 class="mb-0"><?= htmlspecialchars($c['type_cours']) ?></h5>
                        </div>
                        <div class="card-body course-info">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="course-date">
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <?= date('d/m/Y', strtotime($c['date_cours'])) ?>
                                </span>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-info me-2 weather-btn"
                                            data-bs-toggle="modal" data-bs-target="#weatherModal<?= $c['id_cours'] ?>"
                                            data-address="<?= htmlspecialchars($c['adresse']) ?>"
                                            data-course-id="<?= $c['id_cours'] ?>"
                                            title="Voir la météo">
                                        <i class="fas fa-cloud-sun"></i>
                                    </button>
                                    <span class="price-tag">
                                        <?= number_format($c['prix'], 2) ?> €
                                    </span>
                                </div>
                            </div>
                            <p class="card-text">
                                <i class="fas fa-map-marker-alt location-icon me-2"></i>
                                <?= htmlspecialchars($c['adresse']) ?>
                            </p>
                            <button class="btn btn-primary w-100" onclick="window.location.href='addReservation.php?id_cours=<?= $c['id_cours'] ?>'">
                                <i class="fas fa-calendar-check me-2"></i>Réserver
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modals pour la météo -->
    <?php foreach ($cours as $c): ?>
    <div class="modal fade" id="weatherModal<?= $c['id_cours'] ?>" tabindex="-1" aria-labelledby="weatherModalLabel<?= $c['id_cours'] ?>" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="weatherModalLabel<?= $c['id_cours'] ?>">
                        <i class="fas fa-cloud-sun me-2"></i>Météo pour <?= htmlspecialchars($c['type_cours']) ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        <p><strong>Adresse:</strong> <?= htmlspecialchars($c['adresse']) ?></p>
                        <p><strong>Date du cours:</strong> <?= date('d/m/Y', strtotime($c['date_cours'])) ?></p>
                    </div>
                    <div class="weather-loading text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-3">Chargement des données météo...</p>
                    </div>
                    <div class="weather-data" style="display: none;">
                        <div class="row text-center">
                            <div class="col-md-6">
                                <div class="weather-icon mb-2">
                                    <img src="" alt="Conditions météo" class="img-fluid" style="width: 100px; height: 100px;">
                                </div>
                                <h4 class="weather-temp">--°C</h4>
                                <p class="weather-desc">--</p>
                            </div>
                            <div class="col-md-6">
                                <div class="weather-details">
                                    <p><i class="fas fa-tint me-2"></i> <span class="weather-humidity">--%</span></p>
                                    <p><i class="fas fa-wind me-2"></i> <span class="weather-wind">-- km/h</span></p>
                                    <p><i class="fas fa-sun me-2"></i> <span class="weather-sunrise">--:--</span></p>
                                    <p><i class="fas fa-moon me-2"></i> <span class="weather-sunset">--:--</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="weather-forecast mt-4">
                            <h5 class="text-center mb-3">Prévisions pour les prochains jours</h5>
                            <div class="row forecast-container">
                                <!-- Les prévisions seront ajoutées ici par JavaScript -->
                            </div>
                        </div>
                    </div>
                    <div class="weather-error alert alert-danger mt-3" style="display: none;">
                        Impossible de charger les données météo pour cette adresse.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>À propos de nous</h5>
                    <p>Centre de formation professionnel proposant des cours de qualité.</p>
                </div>
                <div class="col-md-4">
                    <h5>Contact</h5>
                    <p>
                        <i class="fas fa-phone me-2"></i> +33 1 23 45 67 89<br>
                        <i class="fas fa-envelope me-2"></i> contact@formation.com
                    </p>
                </div>
                <div class="col-md-4">
                    <h5>Suivez-nous</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Script pour la recherche en temps réel et le tri -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const searchForm = document.getElementById('searchForm');

            // Mettre en évidence les termes de recherche dans les résultats
            if (searchInput.value.trim() !== '') {
                highlightSearchTerms(searchInput.value);
            }

            // Mettre en évidence les prix selon le tri
            highlightPrices();

            // Recherche en temps réel
            let typingTimer;
            const doneTypingInterval = 500; // délai en ms

            searchInput.addEventListener('input', function() {
                clearTimeout(typingTimer);
                if (searchInput.value.trim().length > 2) {
                    typingTimer = setTimeout(function() {
                        searchForm.submit();
                    }, doneTypingInterval);
                }
            });

            // Fonction pour mettre en évidence les termes de recherche
            function highlightSearchTerms(term) {
                if (!term) return;

                term = term.toLowerCase();
                const courseCards = document.querySelectorAll('.course-card');

                courseCards.forEach(card => {
                    const courseTitle = card.querySelector('.card-header h5');
                    if (courseTitle) {
                        const titleText = courseTitle.textContent.toLowerCase();
                        if (titleText.includes(term)) {
                            card.classList.add('highlight');
                        }
                    }
                });
            }

            // Fonction pour mettre en évidence les prix selon le tri
            function highlightPrices() {
                // Récupérer le paramètre de tri depuis l'URL
                const urlParams = new URLSearchParams(window.location.search);
                const sortOrder = urlParams.get('sort');

                if (sortOrder) {
                    const priceTags = document.querySelectorAll('.price-tag');

                    priceTags.forEach(tag => {
                        if (sortOrder === 'asc') {
                            tag.innerHTML = '<i class="fas fa-arrow-up text-success me-1"></i>' + tag.innerHTML;
                        } else if (sortOrder === 'desc') {
                            tag.innerHTML = '<i class="fas fa-arrow-down text-danger me-1"></i>' + tag.innerHTML;
                        }
                    });
                }
            }
        });


    </script>

    <!-- Script pour la météo -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Clé API OpenWeatherMap (à remplacer par votre propre clé)
            const apiKey = '5f472b7acba333cd8a035ea85a0d4d4c'; // Clé de démo OpenWeatherMap

            // Écouter l'ouverture des modals météo
            document.querySelectorAll('.weather-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const courseId = this.getAttribute('data-course-id');
                    const address = this.getAttribute('data-address');
                    const modalId = `weatherModal${courseId}`;
                    const modal = document.getElementById(modalId);

                    // Réinitialiser la modal
                    modal.querySelector('.weather-loading').style.display = 'block';
                    modal.querySelector('.weather-data').style.display = 'none';
                    modal.querySelector('.weather-error').style.display = 'none';

                    // Récupérer les données météo
                    getWeatherData(address, modal);
                });
            });

            // Fonction pour récupérer les données météo
            function getWeatherData(address, modal) {
                // Convertir l'adresse en coordonnées géographiques (geocoding)
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            const lat = data[0].lat;
                            const lon = data[0].lon;

                            // Récupérer les données météo actuelles
                            return fetch(`https://api.openweathermap.org/data/2.5/weather?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=fr`);
                        } else {
                            throw new Error('Adresse non trouvée');
                        }
                    })
                    .then(response => response.json())
                    .then(weatherData => {
                        // Récupérer les prévisions météo
                        const lat = weatherData.coord.lat;
                        const lon = weatherData.coord.lon;

                        // Afficher les données météo actuelles
                        displayCurrentWeather(weatherData, modal);

                        // Récupérer les prévisions pour les prochains jours
                        return fetch(`https://api.openweathermap.org/data/2.5/forecast?lat=${lat}&lon=${lon}&appid=${apiKey}&units=metric&lang=fr`);
                    })
                    .then(response => response.json())
                    .then(forecastData => {
                        // Afficher les prévisions
                        displayForecast(forecastData, modal);

                        // Cacher le chargement et afficher les données
                        modal.querySelector('.weather-loading').style.display = 'none';
                        modal.querySelector('.weather-data').style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Erreur lors de la récupération des données météo:', error);
                        modal.querySelector('.weather-loading').style.display = 'none';
                        modal.querySelector('.weather-error').style.display = 'block';
                    });
            }

            // Fonction pour afficher les données météo actuelles
            function displayCurrentWeather(data, modal) {
                const iconUrl = `https://openweathermap.org/img/wn/${data.weather[0].icon}@2x.png`;
                const temp = Math.round(data.main.temp);
                const description = data.weather[0].description;
                const humidity = data.main.humidity;
                const windSpeed = Math.round(data.wind.speed * 3.6); // Convertir m/s en km/h

                // Convertir les timestamps en heures locales
                const sunriseTime = new Date(data.sys.sunrise * 1000).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                const sunsetTime = new Date(data.sys.sunset * 1000).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

                // Mettre à jour les éléments HTML
                modal.querySelector('.weather-icon img').src = iconUrl;
                modal.querySelector('.weather-temp').textContent = `${temp}°C`;
                modal.querySelector('.weather-desc').textContent = description;
                modal.querySelector('.weather-humidity').textContent = `${humidity}%`;
                modal.querySelector('.weather-wind').textContent = `${windSpeed} km/h`;
                modal.querySelector('.weather-sunrise').textContent = sunriseTime;
                modal.querySelector('.weather-sunset').textContent = sunsetTime;
            }

            // Fonction pour afficher les prévisions météo
            function displayForecast(data, modal) {
                const forecastContainer = modal.querySelector('.forecast-container');
                forecastContainer.innerHTML = '';

                // Récupérer les prévisions pour midi (12:00) des prochains jours
                const forecasts = data.list.filter(item => {
                    const time = new Date(item.dt * 1000).getHours();
                    return time >= 11 && time <= 13; // Prendre les prévisions autour de midi
                }).slice(0, 5); // Limiter à 5 jours

                // Créer un élément pour chaque jour
                forecasts.forEach(forecast => {
                    const date = new Date(forecast.dt * 1000);
                    const dayName = date.toLocaleDateString('fr-FR', { weekday: 'long' });
                    const formattedDate = date.toLocaleDateString('fr-FR', { day: 'numeric', month: 'short' });
                    const temp = Math.round(forecast.main.temp);
                    const iconUrl = `https://openweathermap.org/img/wn/${forecast.weather[0].icon}.png`;
                    const description = forecast.weather[0].description;

                    const forecastDay = document.createElement('div');
                    forecastDay.className = 'col-md-4 col-6 mb-3';
                    forecastDay.innerHTML = `
                        <div class="forecast-day">
                            <div class="forecast-date">${dayName}</div>
                            <div class="forecast-date-num">${formattedDate}</div>
                            <img src="${iconUrl}" alt="${description}" class="img-fluid my-2" style="width: 50px;">
                            <div class="forecast-temp">${temp}°C</div>
                            <div class="forecast-desc small">${description}</div>
                        </div>
                    `;

                    forecastContainer.appendChild(forecastDay);
                });
            }
        });
    </script>
</body>
</html>
