<?php


namespace App\classes;


class Database {
    public static function dbcon() {
        if (getenv('APP_ENV') === 'production') {
            $host = "localhost";
            $user = "dtopnotc_event_mng_user";
            $pass = "mZLY@vx-krGz";
            $db = "dtopnotc_event_mng_system";
        } else {
            $host = 'localhost';
            $user = 'root';
            $pass = '';
            $db = 'event_managment_system';
        }

        return mysqli_connect($host, $user, $pass, $db);
    }
}
