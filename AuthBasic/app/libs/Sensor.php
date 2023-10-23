<?php

// require('/xampp/htdocs/AuthBasic/app/libs/whichBrowser/vendor/autoload.php');
require('./app/libs/whichBrowser/vendor/autoload.php');

class Sensor {
    private $result;

    /**
     * Konstruktor klasy.
     * Tworzy nowy obiekt Parser
     */
    public function __construct() {
        $this->result = new WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
    }

    /**
     * Funkcja sprawdzająca czy użytkownik ma włączoną aplikację lokalnie.
     * Funkcja porównuje czy adres IP klienta jest na liście lokalnych adresów
     * @return true|false Użytkownik jest na localhost'cie LUB nie jest
     */
    public function isLocal() {
        $localAddresses = ['localhost', 'local', '127.0.0.1', '::1', '192.168.'];
        $clientIp = $this->addrIp();

        foreach ($localAddresses as $address) {
            if (strpos($clientIp, $address) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Funkcja zwracająca adres IP użytkownika
     * @param $getProxy Określa czy chcemy otrzymać w wyniku też adresy IP serwerów proxy czy nie
     * @return string Zwracany jest adres IP w zależności od opcji jakie wybraliśmy
     */
    public function addrIp($getProxy = null) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $getProxy == false) {
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $clientIp = $ips[count($ips) - 1];

            return $clientIp;
        } elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $getProxy == true) {
            $ips = explode(', ', $_SERVER['HTTP_X_FORWARDED_FOR']);

            return $ips;
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    /** Funkcja zwraca model przeglądarki używanej przez użytkownika */
    public function browser() {
        return $this->result->browser->toString();
    }

    /** Funkcja zwraca system operacyjny używany przez użytkownika */
    public function system() {
        return $this->result->os->toString();
    }

    /**
     * Funkcja tworzy unikatowy "odcisk palca" użytkownika w celu weryfikacji
     * @param string $algo Określa typ szyfrowania danych
     * @return string Zaszyfrowany "odcisk palca" 
     */
    public function genFingerprint($algo = "sha512") {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $ip = $this->addrIp();
        $hash = hash_hmac($algo, $userAgent, $ip, true);
        return $hash;
    }
}
