<?php

class Magicien extends Personnage {

  private $_magie

  public function lancerUnSort() {
    $perso->getDegats($this->_magie);
  }

  public function getXp() {
    parent::getXp();

    if ($this->_magie < 100) {
      $this->magie += 10;
    }
  }
}



 ?>
