<?php
// auteur: AkramD
// functie: unitests class insertVerkoopOrder

use PHPUnit\Framework\TestCase;
use Bas\classes\VerkoopOrder;

class InsertTestVerkoopOrder extends TestCase{

    protected $verkoopOrder;

    protected function setUp(): void {
        $this->verkoopOrder = new VerkoopOrder();
    }

    public function testInsertVerkoopOrderTrue() {
        
        $testData = [
            'klantId' => 1,
            'artId' => 1,
            'verkOrdDatum' => '2024-06-06',
            'verkOrdBestAantal' => 10,
            'verkOrdStatus' => 1
        ];

        $result = $this->verkoopOrder->insertVerkooporder($testData);
        $this->assertTrue($result);
    }
}
?>
