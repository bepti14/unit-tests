<?php

require_once("libs/DataBaseConn.php");
require_once("libs/Sensor.php");

/**
 * Klasa do autoryzacji jednorazowego dostępu do fragmentu serwisu
 * @author Grzegorz Petri
 * @since 0.2
 */

class AuthBasic
{
    public function genFingerprint($algo)
    {
    }

    /**
     * @desc Generuje kod wymagany do podania podczas autoryzacji dostępu, wg. podanych parametrów
     * @param int $length Długość kodu - liczba znaków
     * @param int $min Minimalna wartość dla generowanego numeru
     * @param int $max Maksymalna wartość dla generowanego numeru
     * @return int Zwraca wygenerowaną na podstawie parametrów liczbę, która musi zostać uzupełniana zerami, jeżeli trzeba spełnić długość
     */
    public function createCode($length = 6, $min = 1, $max = 999999)
    {
        $max = substr($max, 0, $length);
        return str_pad(mt_rand($min, $max), $length, '0', STR_PAD_LEFT); // losowanie 1-999999
    }


    public function compAuthCode($emlAuth, $idzAuth, $authCode)
    {
    }
    public function doAuthByEmail($person, $email)
    {
    }
    public function checkIfValidReqest($person, $email)
    {
    }
    private function checkIfValidReqest2f($emlAuth, $idzAuth)
    {
    }

    /**
     * @desc Funkcja porównująca kod z bazy danych do kodu podanego przez użytkownika
     * @param string $codeNo Kod uwierzytelniający podany przez użytkownika
     * @return true|false Kody zgadzają się LUB nie
     */
    public function verifyQuickRegCode($codeNo = "123456")
    {
        // kod z bazy danych, funkcja ma zostać dodana; na potrzeby testów kod jest wartością stałą
        $dbCode = "123456";

        if ($dbCode == $codeNo) return true;
        else return false; 
    }

    /**
     * @desc Tworzy wpis w BD z numerem pozwalającym na uwierzytelnienie Requesta
     * Tworzony Token do uwierzytelnienia zapisując adres Email oraz ID użytkownika
     * Token musi zostać wysłany na pocztę użytkownika, stąd zwracany jest Obiekt informacyjny
     * @param string $email Adres email użytkownika do uwierzytelnienia
     * @param int $id	Numer ID użytkownika do uwierzytelnienia
     * @return array|false	Wygenerowany Token LUB Fałsz
     */
    public function createAuthToken($email, $id)
    {
        $sensor = new Sensor();

        $authCode = $this->createCode();
        $authDate = date("Y-m-d H:i:s");
        $addrIp = $sensor->addrIp();
        $fingerprint = bin2hex($sensor->genFingerprint());
        $opSys = $sensor->system();
        $browser = $sensor->browser();
        $session_id = 4321;

        $tbl = 'cmswebsiteauth';
        $cols = array(
            'session_id', 'usrId', 'addrIp', 'fingerprint', 'dateTime', 'email', 'authCode', 'opSystem', 'browser'
        );

        $vals = array(
            $session_id, $id, $addrIp, $fingerprint, $authDate , $email, $authCode, $opSys, $browser
        );

        $dbc = new DataBaseConn("localhost", "root", "", "authentication");
        $dbc->connect();

        $dbc->put($tbl, $cols, $vals);

        $data = $dbc->get($tbl, array(), array('where' => 'session_id = 69701'));

        $dbc->disconnect();

        $row = $data[0];

        $cont = array(
            'emlAuth' => $row[5], 'authCode' => "123456",
            'authDate' => $row[4], 'addrIp' => $row[2],
            'reqOs' => $row[7], 'reqBrw' => $row[8]
        );

        return $row;

        // if(count($data) === 1 && $data[0] == $cont) {
        //     return $cont;
        // }else{
        //     return false;
        // }

        // $file = dirname(__FILE__) . '/db.txt';
        // file_put_contents($file, serialize($cont));
        // $fData = file_get_contents($file);
        #var_dump(unserialize($fData));
        #STOP >> db->put()
        // $tok = (unserialize($fData) == $cont) ? 0 : 'err:1045';
        // $resp = ($tok === 0) ? $cont : false;
        // return $resp;
    }
    /*
 * */
}
