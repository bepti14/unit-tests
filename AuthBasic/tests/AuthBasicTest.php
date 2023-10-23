<?php
// załącz plik testowanej klasy - dopasuj ścieżkę do pliku zgodną z własną strukturą katalogów
require_once("app/AuthBasic.php");
// użycie wbudowanych testów

use PHPUnit\Framework\TestCase;
// nazwanie i rozszerzenie własnej klasy klasą `TestCase` zawierającą Asercje do testów 
class AuthBasicTest extends TestCase
{
    private $instance;
    
    public function setUp(): void
    {
        $this->instance = new AuthBasic();
    }
    public function tearDown(): void
    {
        unset($this->instance);
    }

    public function testCreateCode()
    {
        $out = $this->instance->createCode();

        // jezeli potrzeba wyświetlić cokolwiek w widoku testu, należy użyć:
        fwrite(STDERR, print_r($out, true));
        $len = strlen($out);
        $this->assertIsNumeric($out, 'Wylosowano: ' . $out);
        $this->assertEquals(6, $len, 'Długość: ' . $len);

        $out = $this->instance->createCode(4);
        $len = strlen($out);
        $this->assertIsNumeric($out, 'Wylosowano: ' . $out);
        $this->assertEquals(4, $len, 'Długość: ' . $len);

        // symulowanie wylosowania liczby o mniejszej niż oczekiwana długość, którą należy uzupełnić zerami
        // nie można liczyć, że podczas testu zawsze wygenerujemy taką liczbą, stąd skopiowanie implementacji metody
        $out = str_pad(1111, 6, '0', STR_PAD_LEFT);
        $len = strlen($out);
        $this->assertIsNumeric($out, 'Wylosowano: ' . $out);
        $this->assertEquals(6, $len, 'Długość: ' . $len);
    
    }

    public function testCreateAuthToken()                                                           
    {
        // oczekiwana struktura tokenu z następującymi informacjami
        $exp = array(
            'emlAuth' => 'filipkk@gmail.com', 'authCode' => '123456',
            'authDate' => "2023-10-23", 'addrIp' => '',
            'reqOs' => '', 'reqBrw' => ''
        );

        // wywołanie testowanej metody z przykładowymi danymi użytkownika: email i jego IDentyfikator
        $out = $this->instance->createAuthToken('filipkk@gmail.com', 24);

        // ponieważ generowany Token jest wartością losową - musimy go napisać wartością stałą - inaczej nie ma możliwości wykonania pomyślnie testu
        // $out['authCode'] = '131313';

        // wywołanie testu właściwego - Asercji (założenia)
        $this->assertEqualsCanonicalizing($exp, $out, "Tablice są różne");
    }

    public function testVerifyQuickRegCode() {
        // na potrzeby testów kod jest wartością stałą; docelowo ma być generowany losowo
        $code = "123333";
        $out = $this->instance->verifyQuickRegCode($code);

        $this->assertFalse($out, "Kod się nie zgadza");

        $out = $this->instance->verifyQuickRegCode();

        $this->assertTrue($out, "Kod się nie zgadza");
    }

    // public function testVerifyQuickRegCodeValid()
    // {
    //     $auth = new AuthBasic();
    //     // Zakładamy, że taki kod istnieje w bazie danych
    //     $codeNo = '123456'; // Przykładowy poprawny kod autoryzacyjny

    //     $result = $auth->verifyQuickRegCode($codeNo);

    //     $this->assertTrue($result);
    // }

    // public function testVerifyQuickRegCodeExpired()
    // {
    //     $auth = new AuthBasic();
    //     // Zakładamy, że taki kod istnieje w bazie danych, ale jest przeterminowany
    //     $codeNo = '654321'; // Przykładowy przeterminowany kod autoryzacyjny

    //     $result = $auth->verifyQuickRegCode($codeNo);

    //     $this->assertFalse($result);
    // }

    // public function testVerifyQuickRegCodeNonExistent()
    // {
    //     $auth = new AuthBasic();
    //     // Zakładamy, że taki kod nie istnieje w bazie danych
    //     $codeNo = '999999'; // Przykładowy nieistniejący kod autoryzacyjny

    //     $result = $auth->verifyQuickRegCode($codeNo);

    //     $this->assertFalse($result);
    // }
}
