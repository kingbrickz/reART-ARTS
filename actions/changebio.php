<?php
session_start();
include "../settings/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    $bio = $data['bio'];

    $sql = "UPDATE USERS SET bio = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $bio, $_SESSION['id']); 
    
    if ($stmt->execute())
    {
        $response = array(
            "success" => true,
            "newbio" => $bio,
            "id" => $_SESSION['id']
        );

        echo json_encode($response);
    } else {
        $response = array(
            "success" => false,
            "error" => "Failed to update bio"
        );

        echo json_encode($response);
    }

    $stmt->close();
}
$conn->close();