<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay\Country;

/**
 * Defines a country that is managed by the Computer.
 */
class ComputerPlayerCountry extends BaseCountry {

  /**
   * Choose one country to attack, or none.
   *
   * The computer may choose to attack or not. If it chooses not to attack,
   * return NULL. If it chooses to attack, return a neighbor to attack.
   *
   * It must NOT be a conquered country.
   *
   * @return \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface|null
   *   The country that will be attacked, NULL if none will be.
   */
  public function chooseToAttack(): ?CountryInterface {
    $attackedID = array_rand($this->neighbors, 1);
    $attacked = $this->neighbors[$attackedID];
    
    return $this->unconqueredTarget($attacked);
  }

  /*
  * Função de apoio à função chooseToAttack.
  * Passa um país e checa se ele foi conquistado. 
  * Caso tenha sido conquistado, ele retorna seu conquistador.
  * Caso seu conquistador também tenha sido conquistado, ele retorna o conquistador do conquistador.
  */
  public function unconqueredTarget($target) {
    if($target->isConquered()){
      $targetConqueror = $target->getConqueredBy();
      return $this->unconqueredTarget($targetConqueror);
    }else{
      return $target;
    }

  }
}
