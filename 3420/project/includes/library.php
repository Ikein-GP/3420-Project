<?php
// Get the acutal document and webroot path for virtual directories
$direx = explode('/', getcwd());

if(isset($direx[1])){
    define('DOCROOT', "/$direx[1]/$direx[2]/"); // /home/username/
    define('WEBROOT', "/$direx[1]/$direx[2]/$direx[3]/"); //home/username/public_html
}


/*############################################################
Function for connecting to the database
##############################################################*/

function connectDB()
{
    if(isset($direx[1])){
        // Load configuration as an array.
        $config = parse_ini_file(DOCROOT . "pwd/config.ini");
        $dsn = "mysql:host=$config[domain];dbname=$config[dbname];charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }

        return $pdo;
    }
    else{
        // Load configuration as an array.
        $dsn = "mysql:host=localhost;dbname=test;charset=utf8mb4";

        try {
            $pdo = new PDO($dsn, "root", "", [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int) $e->getCode());
        }

        return $pdo;
    }
   
}
