<?

class Personnage {

  private $_id;
  private $_nom;
  private $_degats;
  private $_xp;
  private $_level;

  const CEST_MOI = 1;
  const PERSONNAGE_TUE = 2;
  const PERSONNAGE_FRAPPE = 3;



  public function __construct(array $donnees) {

    $this->hydrate($donnees);
  }

  public function frapper (Personnage $perso) {

    if($perso->id() == $this->_id) {

      return self::CEST_MOI;
    }

      return $perso->getDegats();
      return $perso->getXp();

  }

  public function hydrate(array $donnees) {
    foreach ($donnees as $key => $value) {
      $method = 'set'.ucfirst($key);

      if (method_exists($this, $method)) {
        $this->$method($value);
      }
    }
  }

  public function getDegats() {

    $this->_degats += 5;

// Si on a 100 de dégâts ou plus, on dit que le personnage a été tué.
if ($this->_degats >= 100)
{
  return self::PERSONNAGE_TUE;
}
// Sinon, on se contente de dire que le personnage a bien été frappé.
return self::PERSONNAGE_FRAPPE;

  }

  public function getXp() {

    $this->_xp +=5;

    if($this->_xp >= 100) {
      $this->setLevel($this->level() + 1);
      $this->setXp(0);
    }

  }

  //lISTE DES GETTERS

  public function id() { return $this->_id;}

  public function nom() { return $this->_nom;}

  public function degats() { return $this->_degats;}

  public function xp() { return $this->_xp;}

  public function level() { return $this->_level;}

  public function setId($id) {

    $id = (int) $id;

    if($id > 0) {
      $this->_id = $id;
    }

  }

  //lISTE DES SETTERS

  public function setNom($nom) {

    if(is_string($nom)) {
      $this->_nom = $nom;
    }

  }

  public function setDegats($degats) {

    $degats = (int) $degats;

    if($degats >= 0 && $degats <= 100) {
      $this->_degats = $degats;
    }

  }

  public function setXp($xp) {
    $xp = (int) $xp;
    if($xp >= 0 && $xp <= 100) {
      $this->_xp = $xp;
    }
  }

  public function setLevel($level) {
    $level = (int) $level;
    if($level>= 0 && $level <=100) {
    $this->_level = $level;
  }
  }

  public function nomValide() {

  return !empty($this->_nom);
    }
}
