<?php

include "../settings/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);


    $postId = $data['post_id'];
    $userId = $data['user_id'];
    $comment = $data['text'];

    
    $sql_query = "INSERT INTO COMMENT(POSTID, COMMENT_TEXT, userId) VALUES (?,?,?)";
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param("isi", $postId, $comment, $userId);

    $response = array();

    if ($stmt->execute()) 
    {
        $response['success'] = true;

        $comments_query = "SELECT count(ID) As c FROM COMMENT WHERE POSTID = ?";
        $stmt_comment = $conn->prepare($comments_query);
        $stmt_comment->bind_param("i", $postId);
        $stmt_comment->execute();
        $comment_result = $stmt_comment->get_result();
        $comment_row = $comment_result->fetch_assoc(); 

        $response['count'] = $comment_row['c'];
        $response['text'] = $comment;
        
        $names = $conn->prepare("SELECT first_name, last_name FROM USERS WHERE ID = ?");
        $names->bind_param("i", $userId);
        $names->execute();
        $names_result = $names->get_result()->fetch_assoc();
        $first_name = $names_result['first_name'];
        $last_name = $names_result['last_name'];
        $username = "$first_name $last_name";

        $response['username'] = $username;
    } 
    else 
    { 
        $response['success'] = false;
        $response['error'] = $conn -> error;
    }

    echo json_encode($response);
}