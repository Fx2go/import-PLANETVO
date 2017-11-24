# import-PLANETVO
Cet "Import PLanetVO" consiste à mettre à jour à intervalles réguliers le catalogue d'une boutique en ligne de véhicules d'occasion.
>Le catalogue de véhicules et géré par le responsable du parc automobile depuis le logiciel SAAS PLanet-VO (groupe Argus)
>Le fichier CRON "import-vo.php" importe à interval régulier le contenu du catalogue de véhicules sur l'hébergment de la boutique en ligne (WP+Woocommerce+WP All import)
  Seules les nouvelles photos sont importées pour économiser les ressources.
> Le fichier CRON "wp-cron-allimport-trigger-and-processing.php" ordonne au module "WP All Import" d'intégrer les données dans la boutique en ligne

Démo : http://carestencheres.com
