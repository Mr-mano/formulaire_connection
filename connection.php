<?php
//utilisation de la session
session_start();
if (isset($_SESSION['connect'])) {
    header('location: ../form_connection');
    exit();
}

require('src/connexion.php');

if (!empty($_POST['email']) && !empty($_POST['password'])) {

    //variable
    $email = $_POST['email'];
    $password = $_POST['password'];
    $error = 1;

    //cryptage du password
    $password = "aq1" . sha1($password . "1254") . "25";
    echo $password;
    //sélectionner tous les éléments users
    $req = $db->prepare('SELECT * FROM users WHERE email = ?');
    $req->execute(array($email));

    //connection
    while ($user = $req->fetch()) {

        if ($password == $user['password']) {
            $error = 0;
            $_SESSION['connect'] = 1;
            $_SESSION['pseudo'] = $user['pseudo'];

            //gestion connexion automatique(checkbox)
            if(isset($_POST['connect'])){
                //création du cookie(valable 1 an)
                setcookie('log', $user['secret'], time() + 365 * 24 * 3600, 
                '/', null, false, true);
            }


            header('location: ../form_connection/connection.php?success=1');
            exit();
        }
    }
    if ($error == 1) {
        header('location: ../form_connection/connection.php?error=1');
        exit();
    }
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
    <title>connection</title>
</head>

<body>
    <header>
        <h1>Connection</h1>
    </header>


    <div class="container">
        <p id="info">Bienvenue, si vous n'êtes pas encore inscript,
            <a href="index.php">Inscrivez vous</a>
        </p>
        <?php
        if (isset($_GET['error'])) {
            echo '<p class="error">Email ou mot de passe incorrect.</p>';
        } else if (isset($_GET['success'])) {
            echo '<p class="true">Vous êtes connecté.</p>';
        }
        ?>
        <div class="container__form">

            <form id="form" method="post" action="connection.php">
                <table>

                    <tr>
                        <td class="pseudo">Email</td>
                        <td><input class="input_formulaire" type="email" name="email" placeholder="Entrez votre email" required></td>
                    </tr>
                    <tr>
                        <td class="mdp">Mot de passe</td>
                        <td>
                            <input class="input_formulaire" type="password" name="password" minlength="4" placeholder="******" required></td>
                    </tr>

                </table>
                <p><label class="connect__label"><input type="checkbox" name="connect" checked>Connexion automatique</label></p>
                <div class="container__button">
                    <button type="submit">Connection</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>