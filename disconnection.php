<?php
session_start(); /*initialise la session*/
session_unset(); /*desactive la session*/
session_destroy(); /* détruire la session*/
//détruire le cookie (-1 = chiffre négatif pour détruire le cookie)
setcookie('log', '', time()-1, '/', null, false, true);

header('location: ../form_connection');
        exit();