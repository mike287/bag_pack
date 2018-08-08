<?php

class BagTask
{
    private $arr;
    private $count;
    private $finalArr = [];
    private $weight;
    private $bagArr;

    public function __construct($arr, $weight)
    {
        $this->arr = file($arr);
        $this->weight = $weight;

        $this->countOfItemsInArray();
        $this->prepareArray();
        $this->changeKeyNames();
        $this->sort();
        $this->toBag();
        $this->summary();
        $this->showBag();

//        var_dump($this->bagArr);
    }

/**
 * Metoda countOfItemsInArray zwraca ilosc wierszy w tablyc arr (tam są przedtrzymywane dane z pliku csv)
 */

    public function countOfItemsInArray()
    {
        $this->count = count($this->arr);
        return $this->count;
    }

/**
 * Metoda prepareArray tworzy tablice finalArr, wyrzuca "," między elementami i tworzy nowy wspólczynnik waga/wartość na 3 pozycji.
 */
    public function prepareArray()
    {

        $partArr = [];
        $finalArr = [];

        for($i = 1; $i < $this->countOfItemsInArray(); $i++)
        {
            array_push($partArr, explode(",", $this->arr[$i]));
            array_push($finalArr, $partArr[$i-1]);

            array_push($finalArr[$i-1], $partArr[$i-1][2] / $partArr[$i-1][1]);

        }
        return $this->finalArr = $finalArr;
    }

/**
 * Metoda changeKeyNames nadaje kluczom nazwy, zamiast liczb.
 */
    public function changeKeyNames(){

        $this->finalArr = array_map(function($arr) {

            return array(
                'id' => $arr[0],
                'weight' => $arr[1],
                'value' => $arr[2],
                'W/V' => $arr[3]
            );
        }, $this->finalArr);

    }

/**
 * Metoda sort sortuje tablice tablic po współczynniku W/V od największego.
 */
    public function sort(){

        usort($this->finalArr, function ($item1, $item2) {
            if ($item1['W/V'] == $item2['W/V']) return 0;
            return $item1['W/V'] > $item2['W/V'] ? -1 : 1;
        });

    }

/**
 * Metoda toBag uzupełnia tablice plecaka pobierając z posegregowanej tablicy finalArr elementy
 * na wejściu sprawdzając wagę elementu i zestawia ją z limitem jaki pozostał.
 */
    public function toBag()
    {
        $bagLimit = 0;
        $bagArr = [];
        for ($i = 0; $i < $this->countOfItemsInArray()-1; $i++)
        {
            if($this->finalArr[$i]['weight'] < $this->weight)
            {
               $bagLimit += $this->finalArr[$i]['weight'];

                    if ($bagLimit <= $this->weight)
                    {
                        array_push($bagArr, $this->finalArr[$i]);
                    }
            }
        }

        return $this->bagArr = $bagArr;
    }

/**
 * Metoda countOfItemsInTheBag, podaje ilość elementów wsadzonych do plecaka
 */

    public function countOfItemsInTheBag()
    {
        $countOfItemsInTheBag = count($this->bagArr);

        return $countOfItemsInTheBag;
    }

/**
 * Metoda weightOfBag, zwraca wagę plecaka
 */

    public function weightOfBag()
    {
        $weight = 0;
        foreach ($this->bagArr as $key)
        {
            $weight += $key['weight'];
        }
            return $weight;
    }

/**
 * Metoda valueOfBag, zwraca wartość plecaka
 */
    public function valueOfBag()
    {
        $value = 0;
        foreach ($this->bagArr as $key)
        {
            $value += $key['value'];
        }
        return $value;
    }

/**
 * Metoda showBag, zwraca informacje wyjściowe na temat plecaka
 */
    public function showBag()
    {
        $i = 0;
        foreach ($this->bagArr as $value)
        {   $i++;

              echo "przedmiot ".$i." o id ".$value['id']." jego waga - ".$value['weight']." wartość to ".$value['value']."<br>";

        }
    }

/**
 * Metoda summary, wyświetla informacje na temat przedmiotów w plecaku
 */
    public function summary()
    {
        echo "liczba elementów znajdujących się w plecaku to ".$this->countOfItemsInTheBag()."<br>";
        echo "limit plecaka = ".$this->weight."<br>";
        echo "wykorzystana waga plecaka = ".$this->weightOfBag()."<br>";
        echo "całkowita wartość plecaka = ".$this->valueOfBag()."<br>";

    }

}

$bag = new BagTask('csvArray.csv', 100);

















