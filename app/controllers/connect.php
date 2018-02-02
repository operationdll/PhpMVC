<?php
class Connect
{
    public function getcon()
    {
        DEFINE ('DB_HOST','127.0.0.1');
        DEFINE ('DB_USER','root');
        DEFINE ('DB_PSWD','@llc0m');
        DEFINE ('DB_NAME','techonsi_bsme');
        $dbcon = mysqli_connect(DB_HOST,DB_USER,DB_PSWD,DB_NAME);
        return $dbcon;
    }
}

