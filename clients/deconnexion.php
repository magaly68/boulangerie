<?php
session_start();
session_destroy();
header('Location: connexion.php');
exit;

<p><a href="modifier_profil.php">Modifier mon profil</a></p>
