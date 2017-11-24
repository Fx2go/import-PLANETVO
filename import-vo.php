<?php


// Si blocage ovh => SITE CHMOD 705 .
echo __LINE__." :SERVER_NAME=".$_SERVER['SERVER_NAME']."<br>";
echo __LINE__." :SCRIPT_FILENAME=".$_SERVER['SCRIPT_FILENAME']."<br>";
echo __LINE__." : DIR =". __DIR__;



$ftp_server = 'ftp.publicationvo.com';
$ftp_user_name = 'monUsernameFTP';
$ftp_user_pass = 'XXXXXXXXX';


$now = date("Y-m-d");

echo "</br>";

// Mise en place d'une connexion basique
$conn_id = ftp_connect($ftp_server);

// Identification avec un nom d'utilisateur et un mot de passe


if ($login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)) {
    echo __LINE__." : Identification avec succès avec le nom d'utilisateur et le mot de passe.<br>";
} else {
    echo __LINE__." : Il y a un problème d'identification <br>";
}



// importer fichier csv

// Tentative de téléchargement du fichier $server_file et sauvegarde dans le fichier $local_file
$local_file = __DIR__.'/import/import-VO.csv';
    echo __LINE__." : $local_file <br>";
$server_file = '/datas/au25m1.csv';
    echo __LINE__." : $server_file <br>";

 // Activation du mode passif
ftp_pasv($conn_id, true);    

if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
    echo __LINE__." : Le fichier $local_file a été écris avec succès.<br>";
} else {
    echo __LINE__." : Il y a un problème de téléchargement<br>";

}

// importer fichier zip
$local_file = __DIR__.'/import/photos.txt.zip';
$server_file = '/datas/photos.txt.zip';
if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
    echo __LINE__." : Le fichier $local_file a été écris avec succès.<br>";
} else {
    echo __LINE__." : Il y a un problème de téléchargement<br>";

}



// decompresser zip dans dossier Upload

echo __LINE__." : local_file=$local_file <br>";


 //extraction du zip ok,std by
 
$zip = new ZipArchive;
$res = $zip->open("$local_file");
if ($res === TRUE) {
    $zip->extractTo(__DIR__ .'/import/');
    $zip->close();
    echo __LINE__." : Extraction de $local_file réussie avec succès !";
    //return true;
} else {
    echo __LINE__." : échec pour extraction de $local_file";
}

//stocker chaque ligne dans un tableau 

$photos_ref = file('/home/ftpUser/www/wp-content/uploads/VO-imports/import/photos.txt');

//(ok)extraire le chemin des images 
$qte_photos = count($photos_ref);
for ($i=0; $i <$qte_photos ; $i++) { 
    list($photo_name[$i], $photo_path[$i], $photo_hash[$i]) = explode("\t", $photos_ref[$i]);
}
/**/
$dir_ftp_photo="/photos/pvo/jupiter/photos/au25m1/";


//extraire le nom de fichier $photo_name_temp du chemin $photo_path
for ($i=0; $i <$qte_photos ; $i++) { 
    $photo_name_temp[$i]=substr($photo_path[$i], 34);
}

//Visualiser le contenu des tableaux
//echo __LINE__." :Contenu de photo_path =</br>";
//var_dump($photo_path);
//echo __LINE__." :Contenu de photo_name =</br>";
//var_dump($photo_name);
echo "</br>";
//echo __LINE__." :Contenu de photo_temp =</br>";
//var_dump($photo_name_temp);


//se déplacer dans le dossier cible

    if(ftp_chdir($conn_id, $dir_ftp_photo))
        {
        echo __LINE__." : Déplacement dans le dossier $dir_ftp_photo réussi !</br>";
        }
    else
    {
    echo __LINE__." : Déplacement dans le dossier $dir_ftp_photo <b>échoué</b> !";
    }


//Situer où l'on est : ftp_pwd
 $pwd = ftp_pwd($conn_id);

if(!$pwd) 
    {
   echo 'Erreur !';
    }
    else echo __LINE__." : Dossier FTP actuel =$pwd</br>";  
    


//Récupérer les photos uniquement si elles sont nouvelles!

    //on se place dans le dossier parent
    chdir('/home/ftpUser/www/wp-content/uploads/wpallimport/files/');
    echo __LINE__." : Dossier local actuel = ". getcwd()."</br>";

// photo existante?

for ($i=0; $i <$qte_photos ; $i++) { //replace by foreach  !!
    
    if (file_exists($photo_name[$i])) {
       $message=__LINE__." : Le fichier $photo_name[$i] est présent.</br>";
       echo $message;
       $messageAdmin= $message;
    } else {
    
    $message=__LINE__." : Le fichier $photo_name[$i] n'est pas présent...
    alors on doit l'importer!</br>";
       echo $message;
       $messageAdmin .= $message;

// Importer les photos ...dans le dossier upload..en les renommant
  /**/     
        $local_file = "/home/ftpUser/www/wp-content/uploads/wpallimport/files/$photo_name[$i]";
        $server_file = "$photo_name_temp[$i]";



        if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
        $message=__LINE__." : Le fichier $local_file a été écris avec succès.</br></br>";
       echo $message;
       $messageAdmin .= $message;
        } else {
        echo __LINE__." : Il y a un problème de téléchargement</br>";
        }

}
}

?>



