<?php
session_start();
require('src/connexion.php');

if (!empty($_POST['pseudo']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {

    //variables
    $pseudo = $_POST['pseudo'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    //test si password = password_confirm
    if ($password != $password_confirm) {
        header('location: ../form_connection/?error=1&pass=1');
        exit();
    }

    //test si email exist déjà
    $req = $db->prepare("SELECT COUNT(*) AS numberEmail FROM users WHERE email = ?");
    $req->execute(array($email));
    while ($verif_email = $req->fetch()) {
        if ($verif_email['numberEmail'] != 0) {
            header('location: ../form_connection/?error=1&email=1');
            exit();
        }
    }
    //HASH (identifiant secret)
    $secret = sha1($email) . time();
    $secret = sha1($secret) . time() . time();

    //cryptage du password
    $password = "aq1" . sha1($password . "1254") . "25";


    //envoie de le requete en base de données
    $req = $db->prepare('INSERT INTO users(pseudo, email, password, secret) VALUES(?, ?, ?, ?)');
    $req->execute(array($pseudo, $email, $password, $secret));
    header('location: ../form_connection/?true=1');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="design/style.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <title>espace membre</title>
</head>

<body>
    <header>
        <h1>Inscription</h1>
    </header>
    <?php
    if (!isset($_SESSION['connect'])) { ?>


        <div class="container">
            <p id="info">Bienvenue, n'hésitez pas à vous inscrire, ou
                <a href="connection.php">Connectez vous</a>
            </p>
            <?php
                if (isset($_GET['error'])) {
                    if (isset($_GET['pass'])) {
                        echo '<p class="error">Espèce de connard, les mots de passe ne sont pas identiques.</p>';
                    }
                    if (isset($_GET['email'])) {
                        echo '<p class="error">Cet adresse email est déjà prise.</p>';
                    }
                } else if (isset($_GET['true'])) {
                    echo '<p class="true">Inscription validée.</p>';
                }

                ?>
            <div class="container__form">
                <form method="POST" action="index.php" id="form">
                    <table>
                        <tr>
                            <td class="pseudo">pseudo</td>
                            <td><input class="input_formulaire" type="text" name="pseudo" placeholder="Entrez votre pseudo" required></td>
                        </tr>
                        <tr>
                            <td class="email">Email</td>
                            <td><input class="input_formulaire" type="email" name="email" placeholder="Entrez votre email" required></td>
                        </tr>
                        <tr>
                            <td class="mdp">Mot de passe</td>
                            <td>
                                <input class="input_formulaire" type="password" name="password" minlength="4" placeholder="******" required></td>
                        </tr>
                        <tr>
                            <td class="confirm_mdp">Confirmation</td>
                            <td>
                                <input class="input_formulaire" type="password" name="password_confirm" minlength="4" placeholder="******" required></td>
                        </tr>
                    </table>
                    <div class="container__button">
                        <button type="submit">Inscription</button>
                    </div>
                </form>
            </div>
        </div>
    <?php } else {?>
        <div class="container">
        <p id="info">Bonjour <?= $_SESSION['pseudo']?>, tu es connecté!<br>
        <a href="disconnection.php">Déconnexion</a></p>
            

    </div>
    <?php }?>
</body>

</html>