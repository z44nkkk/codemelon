<?php
Class Admin extends Connect {
    public function getUsers($data_array){
        $connect = parent::Conection();
        $sql = "SELECT * FROM users JOIN users_data ON users.id = users_data.user_id 
        ORDER BY users.id DESC LIMIT ${data_array['limit']} OFFSET ${data_array['offset']}"; 
        $stmt = $connect->prepare($sql);
        
        try{
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = [
                "success" => true,
                "data" => $data
            ];
            return $response;
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
    }

    public function getUsersLike($like) {
        $connect = parent::Conection(); // Asume que Conection() devuelve una instancia válida de PDO
        $sql = "SELECT name,email FROM users WHERE name LIKE :like OR email LIKE :like";
        $stmt = $connect->prepare($sql);
    
        try {
            // Bindear el valor con comodines para el LIKE
            $stmt->bindValue(':like', '%' . $like . '%', PDO::PARAM_STR);
            
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = [
                "success" => true,
                "data" => $data
            ];
            return $response;
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
    }

    public function  getAllUsersEmails($batchSize = 1000, $offset = 0) {

        try {
            $connect = parent::Conection();  // Conectar a la base de datos
    
            // Consulta SQL para obtener los correos electrónicos en lotes
            $sql = 'SELECT email FROM users LIMIT :batchSize OFFSET :offset';
            $stmt = $connect->prepare($sql);
            $stmt->bindParam(':batchSize', $batchSize, PDO::PARAM_INT);  // Vincular el tamaño del lote
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);  // Vincular el desplazamiento (OFFSET)
            $stmt->execute();
            
            // Recuperar los correos electrónicos
            $emails = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $emails[] = $row['email'];  // Almacenar cada correo electrónico en el arreglo
            }
    
            return $emails;  // Devolver los correos electrónicos
        } catch (PDOException $e) {
            return $e;  // Devolver un arreglo vacío en caso de error
        }
    }
    
    


    public function getPageAccess($data_array){
        $connect = parent::Conection();
        $sql = "SELECT * FROM user_page_access 
            JOIN users ON user_page_access.user_id = users.id 
            JOIN users_data ON users.id = users_data.user_id 
            ORDER BY user_page_access.id DESC 
            LIMIT ${data_array['limit']} OFFSET ${data_array['offset']}"; 
        $stmt = $connect->prepare($sql);
        
        try{
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = [
                "success" => true,
                "data" => $data
            ];
            return $response;
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
    }
    public function getSuggestions($data_array){
        $connect = parent::Conection();
        $sql = "SELECT * FROM suggestions 
            JOIN users ON suggestions.user_id = users.id 
            JOIN users_data ON users.id = users_data.user_id 
            ORDER BY suggestions.id DESC 
            LIMIT ${data_array['limit']} OFFSET ${data_array['offset']}"; 
        $stmt = $connect->prepare($sql);
        
        try{
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response = [
                "success" => true,
                "data" => $data
            ];
            return $response;
        } catch (Exception $e) {
            $response = [
                'success' => false,
                'message' => $e->getMessage()
            ];
            return $response;
        }
    }



    public function getTotalRows($tableName){
        $connect = parent::Conection();
        $sql = "SELECT COUNT(*) as total_rows FROM $tableName"; 
        $stmt = $connect->prepare($sql);
        
        try{
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            return $data["total_rows"];
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

}