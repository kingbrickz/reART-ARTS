<?php
include "../settings/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);

    $postId = $data['id'];

    $sql = "DELETE FROM POST WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $postId);

    $response = array("success" => false); 
    
    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $response["success"] = true;
        }
    } else {
        $response["error"] = "Error executing deletion query: " . $stmt->error;
    }
    echo json_encode($response);
    $stmt->close();
    $conn->close();
}