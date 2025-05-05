<?php
require_once 'includes/db.php';
require_once 'includes/functions.php';

$boulangeries = getAllBoulangeries($pdo);
?>

<h1>Nos boulangeries</h1>
<ul>
<?php 
foreach ($boulangeries as $boulangerie) { ?>
    <li>
        <a href="boulangeries/voir_boulangerie.php?id=<?= $boulangerie['id'] ?>">
            <?= htmlspecialchars($boulangerie['nom']) 
            ?>
        </a>
    </li>
<?php } 
?>
</ul>