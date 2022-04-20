<?php
// Get the acutal document and webroot path for virtual directories
$direx = explode('/', getcwd());
define('DOCROOT', "/$direx[1]/$direx[2]/"); // /home/username/
define('WEBROOT', "/$direx[1]/$direx[2]/$direx[3]/"); //home/username/public_html

/*############################################################
Function for connecting to the database
##############################################################*/

function connectDB()
{
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

function checkAndMoveFile($filekey, $sizelimit, $newname) //checking for image files has been removed, need to figure out how to ensure .txt
{
    try
    {
        if(!isset($_FILES[$filekey]['error']) || is_array($_FILES[$filekey]['error'])) 
        {
            throw new RuntimeException('Invalid parameters.');
        }

        switch ($_FILES[$filekey]['error']) 
        {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        if ($_FILES[$filekey]['size'] > $sizelimit) 
        {
            throw new RuntimeException('Exceeded filesize limit.');
        }

        if (!move_uploaded_file($_FILES[$filekey]['tmp_name'], $newname))
        {
            throw new RuntimeException('Failed to move uploaded file.');
        }
        //echo 'File is uploaded successfully.'; this doesn't need to be echoed to the user in this context
    }
    catch (RuntimeException $e) 
    {
        echo $e->getMessage();
    }
}
