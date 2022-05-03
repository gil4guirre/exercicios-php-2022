<?php

namespace Galoa\ExerciciosPhp2022\War\GamePlay\Country;

/**
 * Defines a country, that is also a player.
 */
class BaseCountry implements CountryInterface
{

    /**
     * The name of the country.
     *
     * @var string
     */
    protected string $name;
    
    /*
    * Vizinhança do país. Quais páises ele pode atacar.
    */
    protected array $neighbors;

    /*
    * Número de tropas atuais de um país. Cada país começa com 3.
    * Se começasse com 0 e usasse a função addTroops na primeira rodada, 
    * cada país começaria com isConquered como TRUE.
    */
    protected int $numberOfTroops = 3;

    /*
    * Diz por qual outro país esse país (this) foi conquistado. 
    * Serve para outros países saberem quem atacar depois do país ser conquistado.
    */
    protected BaseCountry $conqueredBy;
    
    protected array $fullTerritory;
    
    /**
     * Builder.
     *
     * @param string $name
     *   The name of the country.
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->addToFullTerritory([$this]);
    }

    /**
     * Get the name of the country.
     *
     * @return  string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Sets the neighbors of this country.
     *
     * This method is run ONLY ONCE on the game creation. You must handle the
     * addition of additional neighbors in the conquer() method.
     *
     * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface[] $neighbors
     *   An array of countries that neighbor this country, indexed by their names.
     */
    public function setNeighbors(array $neighbors): void
    {
        $this->neighbors = $neighbors;
    }
    
    public function addToFullTerritory(array $fullTerritory): void
    {
        $this->fullTerritory = $fullTerritory;
    }
    /**
     * Lists the neighbors of a country.
     *
     * When the country is initialized, it receives an array of neighbours by the
     * game manager. Before any round, this array is exactly what getNeighbors()
     * should return.
     *
     * When a country conquers another, it should add this country neighbors to
     * its own. You should make sure, however, that you do NOT duplicated
     * countries in the array, nor return the current country as itself.
     *
     * @return \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface[]
     *   The country's neighbors.
     */
    public function getNeighbors(): array
    {
        return $this->neighbors;
    }

    /**
     * Returns how many troops there currently are in this country.
     *
     * @return int
     *   The number of troops this country has.
     */
    public function getNumberOfTroops(): int
    {
        return $this->numberOfTroops;
    }

    /**
     * Determines whether the player has been conquered.
     *
     * When a country is conquered, its object is not destroyed but it will be
     * flagged as "conquered", so that the game manager knows it will no longer be
     * playing. Your code should handle this flag and return the information
     * properly.
     *
     * @return bool
     *   If this country has been conquered by someone else, this method will
     *   return TRUE.
     */
    public function isConquered(): bool
    {
        if ($this->numberOfTroops <= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getConqueredBy() {
        return $this->conqueredBy;
    }
    /**
     * Called when, after a battle, the defending country end up with 0 troops.
     *
     * Here, you must register the neighbors of the conquered country as your own.
     *
     * @param \Galoa\ExerciciosPhp2022\War\GamePlay\Country\CountryInterface $conqueredCountry
     *   The country that has just been conquered.
     */
    public function conquer(CountryInterface $conqueredCountry): void
    {
        /*
        * Pega os vizinhos do país conquistado e coloca como seus.
        */
        $this->neighbors = array_merge($this->neighbors, $conqueredCountry->neighbors);
        
        /*
        * Pega o todo o território do conquistado e coloca como seu.
        */
        $this->fullTerritory = array_merge($this->fullTerritory, $conqueredCountry->fullTerritory);
        
        /*
        * Remove vizinhos repetidos.
        */
        $this->neighbors = array_unique($this->neighbors, SORT_REGULAR);
        
        /*
        * Pega os nomes dos países do seu território e remove da sua vizinhança.
        */
        $conqueredNames = $this->getConqueredNames($this->fullTerritory);
        $this->unsetNeighbors($conqueredNames);
        
        /*
        * Dá um valor ao conqueredBy do país conquistado.
        */
        $conqueredCountry->conqueredBy = $this;
    }
    
    /*
    *Remove países do array da vizinhança passando um array com os nomes do países. 
    *Função para auxiliar a função conquer.
    */
    public function unsetNeighbors(array $names)
    {
        $array = $this->neighbors;

        foreach ($array as $key => $value) {
            if (in_array($value->name, $names)) {
                unset($this->neighbors[$key]);
            }
        }
    }

    /*
    * Pega os nomes dos países para serem retirados na função unsetNeighbors.
    */
    public function getConqueredNames($baseCountryList)
    {
        $names = [];
        
        foreach ($baseCountryList as $key => $value) {
            $names[] = $value->name;

        }
        return $names;
    }
    /**
     * Decreases the number of troops in this country by a given number.
     *
     * @param int $killedTroops
     *   The number of troops killed in battle.
     */
    public function killTroops(int $killedTroops): void
    {
        $this->numberOfTroops = $this->numberOfTroops - $killedTroops;
    }

}
