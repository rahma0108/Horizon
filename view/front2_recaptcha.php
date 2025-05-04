<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Assurez-vous que le token CSRF est défini
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    error_log('Nouveau token CSRF généré : ' . $_SESSION['csrf_token']);
}

$error_message = '';
$id = '';

define('DIR', __DIR__ . '/../');
require_once DIR . 'vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error_message = 'Erreur de sécurité. Veuillez réessayer.';
    } else {
        $recaptcha_secret = '6LdFWSYrAAAAADGop4YPwm8DzEhORgz0SMUlUca8';
        $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
        $id = trim($_POST['id'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($id) || empty($password)) {
            $error_message = 'Veuillez remplir tous les champs.';
        } elseif (!preg_match('/^\d{8}$/', $id)) {
            $error_message = 'L\'ID doit contenir exactement 8 chiffres.';
        } else {
            if (empty($recaptcha_response)) {
                $error_message = 'Veuillez cocher le CAPTCHA.';
            } else {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'secret' => $recaptcha_secret,
                    'response' => $recaptcha_response
                ]));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $recaptcha_verify = curl_exec($ch);
                curl_close($ch);

                $recaptcha_verify_response = json_decode($recaptcha_verify);
                if (!$recaptcha_verify_response->success) {
                    $error_message = 'Erreur de validation du CAPTCHA : ' . implode(', ', $recaptcha_verify_response->{'error-codes'} ?? ['inconnu']);
                } else {
                    try {
                        require_once DIR . 'model/Databaseconfig.php';
                        $dbConfig = new App\Model\Databaseconfig();
                        $db = $dbConfig->getConnexion();
                        $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE id = :id');
                        $stmt->execute([':id' => $id]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);

                        if ($user && password_verify($password, $user['password'])) {
                            $_SESSION['authenticated'] = true;
                            $_SESSION['user_id'] = $user['id'];
                            $_SESSION['user_role'] = $user['role'];
                            $_SESSION['name'] = $user['name'];
                            header('Location: vide.php');
                            exit;
                        } else {
                            $error_message = 'Identifiant ou mot de passe incorrect.';
                        }
                    } catch (PDOException $e) {
                        $error_message = 'Erreur de connexion à la base de données.';
                        error_log('PDO Error: ' . $e->getMessage());
                    }
                }
            }
        }
    }
}

// ✅ Handle Google Sign-In (JSON POST request)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_POST) && str_contains($_SERVER['CONTENT_TYPE'] ?? '', 'application/json')) {
    $input = json_decode(file_get_contents('php://input'), true);
    $id_token = $input['id_token'] ?? '';

    $response = ['success' => false, 'message' => ''];

    if (empty($id_token)) {
        $response['message'] = 'Token manquant.';
        echo json_encode($response);
        exit;
    }

    try {
        $client = new Google_Client(['client_id' => '541420139782-md4ls2mdlag4vuqd4d82d66ivhenj83b.apps.googleusercontent.com']);
        $payload = $client->verifyIdToken($id_token);

        if ($payload && $payload['aud'] === $client->getClientId()) {
            $email = $payload['email'];
            $google_id = $payload['sub'];
            $name = $payload['name'];

            require_once DIR . 'model/Databaseconfig.php';
            $dbConfig = new App\Model\Databaseconfig();
            $db = $dbConfig->getConnexion();

            $stmt = $db->prepare('SELECT * FROM utilisateurs WHERE email = :email');
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $_SESSION['authenticated'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role'];
                $_SESSION['name'] = $user['name'];
                $response['success'] = true;
                $response['name'] = $user['name'];
            } else {
                $response['message'] = 'Utilisateur non trouvé. Veuillez vous inscrire.';
            }
        } else {
            $response['message'] = 'Token Google invalide.';
        }
    } catch (Exception $e) {
        $response['message'] = 'Erreur Google: ' . $e->getMessage();
        error_log('Google Sign-In Error: ' . $e->getMessage());
    }

    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-signin-client_id" content="541420139782-md4ls2mdlag4vuqd4d82d66ivhenj83b.apps.googleusercontent.com">
    <title>Connexion - Votre Application</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://apis.google.com/js/platform.js?onload=initGoogleSignIn" async defer></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            background: url('https://images.unsplash.com/photo-1599058917212-d750089bc07e?auto=format&fit=crop&w=1920&q=80') no-repeat center center fixed;
            background-size: cover;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            font-size: 14px;
            margin-bottom: 5px;
            display: block;
        }
        input[type="text"],
        input[type="password"],
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 15px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: red;
            text-align: center;
            margin: 10px 0;
        }
        .loading-message {
            color: blue;
            text-align: center;
            margin: 10px 0;
            display: none;
        }
        .forgot-password {
            text-align: center;
            margin: 15px 0;
        }
        .forgot-password a {
            color: #4CAF50;
            text-decoration: none;
        }
        .forgot-password a:hover {
            text-decoration: underline;
        }
        .g-recaptcha {
            margin: 15px 0;
            text-align: center;
        }
        .g-signin2 {
            margin: 20px 0;
            text-align: center;
        }
        .or-divider {
            text-align: center;
            margin: 20px 0;
            position: relative;
        }
        .or-divider:before,
        .or-divider:after {
            content: "";
            display: block;
            width: 40%;
            height: 1px;
            background: #ddd;
            position: absolute;
            top: 50%;
        }
        .or-divider:before {
            left: 0;
        }
        .or-divider:after {
            right: 0;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2>Connexion Utilisateur</h2>
    <div class="loading-message" id="loading-message">Veuillez patienter...</div>

    <?php if (!empty($error_message)): ?>
        <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validateRecaptcha()">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
        
        <label for="id">ID :</label>
        <input type="text" name="id" id="id" value="<?= htmlspecialchars($id) ?>" required>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" id="password" required>

        <div class="g-recaptcha" data-sitekey="6LdFWSYrAAAAAEGLFcvTQBvOhbLjIdyAU-Zbm62p"></div>
        <input type="submit" name="submit" value="Valider">
    </form>

    <div class="forgot-password">
        <a href="forgot_password.php">Mot de passe oublié ?</a>
    </div>

    <div class="or-divider">OU</div>

    <!-- ✅ Google Sign-In Button -->
    <div id="g_id_onload"
         data-client_id="541420139782-md4ls2mdlag4vuqd4d82d66ivhenj83b.apps.googleusercontent.com"
         data-callback="handleCredentialResponse"
         data-auto_prompt="false">
    </div>

    <div class="g_id_signin" data-type="standard"></div>

    <div id="signout-button" style="display: none; text-align: center; margin-top: 10px;">
        <button onclick="signOut()">Se déconnecter de Google</button>
    </div>
</div>

    <script>
    function initGoogleSignIn() {
        gapi.load('auth2', function() {
            gapi.auth2.init({
                client_id: '541420139782-md4ls2mdlag4vuqd4d82d66ivhenj83b.apps.googleusercontent.com'
            }).then(() => {
                console.log('Google Auth2 initialized');
            }).catch(err => {
                console.error('Erreur lors de l\'initialisation de Google Auth2:', err);
            });
        });
    }

    function validateRecaptcha() {
        var response = grecaptcha.getResponse();
        if (response.length === 0) {
            alert("Veuillez cocher le CAPTCHA.");
            return false;
        }
        console.log("reCAPTCHA Response: " + response);
        return true;
    }

    function onSignIn(googleUser) {
        console.log("Google Sign-In successful, user:", googleUser.getBasicProfile().getEmail());
        const id_token = googleUser.getAuthResponse().id_token;
        console.log("ID Token:", id_token);

        if (!id_token) {
            console.error("ID Token is missing!");
            showError("Erreur: ID Token manquant.");
            return;
        }

        // Afficher le message de chargement
        const loadingMessage = document.getElementById('loading-message');
        loadingMessage.style.display = 'block';

        fetch('backend.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id_token: id_token })
        })
        .then(response => {
            console.log("Fetch response status:", response.status);
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            console.log("Response data:", data);
            loadingMessage.style.display = 'none';
            if (data.success) {
                console.log("Redirecting to vide.php");
                window.location.href = 'vide.php';
            } else {
                showError(data.message || 'Erreur de connexion Google');
                document.getElementById('signout-button').style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Erreur lors de la connexion:', error);
            loadingMessage.style.display = 'none';
            showError('Erreur de connexion: ' + error.message);
            document.getElementById('signout-button').style.display = 'block';
        });
    }

    function signOut() {
        const auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(() => {
            console.log('Utilisateur déconnecté de Google.');
            document.getElementById('signout-button').style.display = 'none';
            showError('Vous avez été déconnecté de Google. Veuillez réessayer.');
        });
    }

    function showError(message) {
        const errorElement = document.querySelector('.error-message') || document.createElement('div');
        if (!document.querySelector('.error-message')) {
            errorElement.className = 'error-message';
            document.querySelector('h2').after(errorElement);
        }
        errorElement.textContent = message;
    }
    </script>

    
</div>
<script src="https://accounts.google.com/gsi/client" async defer></script>
<script>
  function handleCredentialResponse(response) {
    document.getElementById("loading-message").style.display = "block";
    
    fetch("login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ id_token: response.credential })
    })
    .then(res => res.json())
    .then(data => {
      document.getElementById("loading-message").style.display = "none";
      if (data.success) {
        alert("Bienvenue " + data.name);
        window.location.href = "vide.php";
      } else {
        alert("Erreur: " + data.message);
      }
    });
  }

  function signOut() {
    google.accounts.id.disableAutoSelect();
    alert("Déconnecté de Google.");
  }
</script>

</body>
</html>