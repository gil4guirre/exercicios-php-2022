<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay;

use Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface;

/**
 * A manager that will roll the dice and compute the winners of a battle.
 */
class Battlefield implements BattlefieldInterface {

    /**
   * Rolls the dice for a country.
   *
   * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface $country
   *   The country that is rolling the dice.
   * @param bool $isAtacking
   *   TRUE if the dice is being rolled by the attacker, FALSE if by the
   *   defender.
   *
   * @return int[]
   *   An array with values from 1-to-6. The array must have as many items as:
   *     - the number of troops of the country, when the defender is rolling
   *       the dice.
   *     - the number of troops of the country MINUS ONE, when the attacker is
   *       the one rolling the dice.
   */
    public function rollDice(CountryInterface $country, bool $isAtacking) : array {
        $diceResult=[];
        
        if($isAtacking){
            $diceTroops = $country->getNumberOfTroops() -1;
        }else{
            $diceTroops = $country->getNumberOfTroops();
        }

        for($j = 1; $j <= $diceTroops; $j++ ){
            array_push($diceResult, rand(1,6));
        }

        rsort($diceResult);

        return $diceResult;
    }

  /**
   * Computes the winners and losers of a battle.
   *
   * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface $attackingCountry
   *   The country that is attacking.
   * @param int[] $attackingDice
   *   The number
   * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface $defendingCountry
   *   The country that is defending from the attack.
   */
    public function computeBattle(CountryInterface $attackingCountry, array $attackingDice, CountryInterface $defendingCountry, array $defendingDice): void {
        if( count($attackingDice) <= count($defendingDice) ){
            $maxSets=count($attackingDice);
        }else{
            $maxSets=count($defendingDice);
        }
        for( $k = 0 ; $k < $maxSets ; $k++ ){
            if( $attackingDice[$k] > $defendingDice[$k] ){
                $defendingCountry->killTroops(1);
            }else{
                $attackingCountry->killTroops(1);
            }
        }
    }
}