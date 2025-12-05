<?php
    global $dirBaseFile;
    require_once($dirBaseFile . '/conexiones/conectorMySQL.php');

    class Camas
    {
        public static function actualizarEstadoCama($idCama, $estado) {
            try{
                

                if (is_null($idCama)) {
                    throw new Exception("El campo idCama no puede estar vacío");
                }else if (is_null($estado)) {
                    throw new Exception("El campo estado no puede estar vacío");
                }

                ConexionDb::connect();

                $sql = "UPDATE camas 
                        SET EstadoCama = :estado, 
                        Habilitada = 1
                        WHERE IdCama = :cama";

                $params = [
                    ["clave" => ":cama", "valor" => $idCama],
                    ["clave" => ":estado", "valor" => $estado]
                ];

                $result = ConexionDb::consult($sql, $params);

                ConexionDb::disconnect();
                
                return $result;
            }catch(Exception $e){
                throw new Exception("Problemas al editar estado de cama: " . $e);
            }
        }

    }

?>