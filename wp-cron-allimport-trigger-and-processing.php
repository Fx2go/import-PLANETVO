<?php 
// On lance l'import des données brutes vers le catalogue en ligne grace au plugin WP All Import
// Comme OVH ne supporte pas les URL de tache CRON avec parametres, OVH lance chaque heure ce fichier qui lance lui-même les 2 taches cron du module WP All Import

$file = fopen ("http://monNDD.com/wp-cron.php?import_key=XXX&import_id=n&action=trigger", "r");
sleep(5);
fclose($file); 
$file = fopen ("http://monNDD.com/wp-cron.php?import_key=XXX&import_id=n&action=processing", "r");

?>