<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/includes/db/dbConfig.php');

    class ConexionDb
    {
        public static function connect()
        {
            global $db_servername, $db_username, $db_password, $db_name, $conn;
            try{
                if (!isset($conn)) {
                    $conn = new PDO("mysql:host=" . $db_servername . ";dbname=" . $db_name . ";charset=utf8", $db_username, $db_password);
                    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                }
            } catch (PDOException $e) {
                throw new Exception("Error en la conexión a la BD: " . $e);
            }
            
        }

        public static function disconnect()
        {
            global $conn;
            $conn = null;
        }

        public static function consult($query, $params = NULL)
        {
            global $conn;
            try{
                $smtm = $conn->prepare($query);

                if ($params !== NULL) {
                    foreach ($params as $param){
                        $clave = $param["clave"];
                        $valor = $param["valor"];

                        $smtm->bindValue($clave, $valor);
                    }
                }
                
                $smtm->execute();

                if (stripos(trim($query), 'SELECT') === 0) {
                    $response = $smtm->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    $response = $smtm->rowCount(); 
                }

                return $response;
                
            } catch (Exception $e) {
                throw new Exception("Error al consultar a la BD: (" . $query . "): " . $e);
            }
        }
    }

?>