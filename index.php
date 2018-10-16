<?php

function ChargerClasse($classe) {
  require $classe . '.php';
}

spl_autoload_register('ChargerClasse');

session_start(); // On appelle session_start() APRÈS avoir enregistré l'autoload.

if (isset($_GET['deconnexion']))
{
  session_destroy();
  header('Location: .');
  exit();
}

$db = new PDO('mysql:host=localhost;dbname=test', 'root', 'Younes0802');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); // On émet une alerte à chaque fois qu'une requête a échoué.

$manager = new PersonnagesManager($db);

if (isset($_SESSION['perso'])) {
$perso = $_SESSION['perso'];
}

if (isset($_POST['creer']) && isset($_POST['nom'])) {

  $perso = new Personnage(['nom' => $_POST['nom']]);

  if(!$perso->nomValide()) {
    $message ='LE nom choisi est invalide';
    unset($perso);

  } elseif ($manager->exists($perso->nom())) {
    $message = 'Le nom du persnnage est déjà pris';
    unset($perso);

  } else {
    $manager->add($perso);
  }
} elseif (isset($_POST['utiliser']) && isset($_POST['nom'])) {
  if ($manager->exists($_POST['nom'])) {
    $perso = $manager->get($_POST['nom']);
  } else {
    $message = "Ce personnage n'existe pas";
  }
}

elseif(isset($_GET['frapper'])) {
  if(!isset($perso)) {
    $message = 'Merci de créer un personnage ou de vous identifier';
  } else {

    if(!$manager->exists((int) $_GET['frapper'])) {

      $message = 'Le perso que vous voulez frapper n\'existe pas !';
    } else {

      $cible = $manager->get((int) $_GET['frapper']);

      $retour = $perso->frapper($cible);

      switch($retour) {
        case Personnage::CEST_MOI :
        $message = 'WTF te frappe pas tout seul man !';
        break;

        case Personnage::PERSONNAGE_FRAPPE :
        $message = "Le personnage a bien été frappé !";

        $manager->update($perso);
        $manager->update($cible);

        break;

        case Personnage::PERSONNAGE_TUE :
        $message = 'Vous avez tué ce personnage !';

        $manager->update($perso);
        $manager->delete($cible);

        break;

        }
      }
    }
  }



?>
<!DOCTYPE html>
<html>
  <head>
    <title>TP : Mini jeu de combat</title>

    <meta charset="utf-8" />
  </head>
  <body>

    <p>Nom de personnages créés : <?php echo $manager->count() ?></p>
    <?php

      if (isset($message)) {
        echo '<p>' .$message .'</p>';
      }

      if(isset($perso)) {
        ?>

        <a href="?deconnexion=1">Deconnexion</a>

        <fieldset>
          <legend>Mes informations</legend>
          <p>Nom : <?php echo htmlspecialchars($perso->nom()); ?><br>
            Degats : <?php echo $perso->degats() ?></p>
        </fieldset>

<br>

        <fieldset>
          <legend>Qui frapper ?</legend>
          <p>

            <?php
            $persos = $manager->getList($perso->nom());

if (empty($persos))
{
  echo 'Personne à frapper !';
}

else
{
  foreach ($persos as $unPerso)
  {
    echo '<a href="?frapper=', $unPerso->id(), '">', htmlspecialchars($unPerso->nom()), '</a> (dégâts : ', $unPerso->degats(), ')<br />';
  }
}
            ?>
          </p>
        </fieldset>
        <?php

      } else {
        ?>
        <form action="" method="post">
          <p>
            Nom : <input type="text" name="nom" maxlength="50" />
            <input type="submit" value="Créer ce personnage" name="creer" />
            <input type="submit" value="Utiliser ce personnage" name="utiliser" />
          </p>
        </form>
        <?
      }

     ?>

  </body>
</html>

<?
if(isset($perso)) {
  $_SESSION['perso'] = $perso;
}

?>
