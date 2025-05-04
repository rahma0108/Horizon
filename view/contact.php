<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Contact - GreenMove</title>
    <link rel="stylesheet" href="../css/index.css">
</head>
<body>
    <div class="form-container">
        <h2>Formulaire de Contact</h2>
        <form action="../controller/ContactController.php" method="POST">
            <div class="form-group">
                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" placeholder="Votre e-mail" autocomplete="off" required>
            </div>
            <div class="form-group">
                <label for="message">Votre message :</label>
                <input type="text" id="message" name="message" placeholder="Écrivez votre message" autocomplete="off" required>
            </div>
            <button type="submit" name="envoyer" value="envoyer" class="submit-btn">Envoyer</button>
        </form>

        <?php if (isset($_GET['success'])): ?>
            <div class="success-message">Message envoyé avec succès !</div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="error-message"><?= htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>