<?php
session_start();
include "../settings/connection.php";

function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    
    if (!empty($_FILES["image"]["name"])) {
        $file_name = generateUUID() . "_" . $_FILES["image"]["name"];
        $target_path = "../assets/" . $file_name;
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_path)) {
            $image_path = $target_path;
            echo "Image moved successfully<br>";
        } 
        else 
        {
            echo "Error uploading image";
        }
    } 
    else 
    {
        $image_path = "N/A";
    }

    $sql = "UPDATE USERS SET picture_path = ? WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) 
    {
        $stmt->bind_param("si", $image_path, $_SESSION["id"]);
        if ($stmt->execute()) 
        {
            header("Location: ../actions/setSessionPicture.php?picturePath=$image_path");
            exit();
        } 
        else 
        {
            echo "Error: " . $conn->error;
        }
        $stmt->close();
    } 
    $conn->close();
}