<?php

include "../settings/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    $json_data = file_get_contents('php://input');
    $data = json_decode($json_data, true);
    $postId = $data['post_id'];
    
    $sql_query = "SELECT * FROM COMMENT WHERE POSTID = ? ORDER BY CREATED_AT DESC";
    $stmt = $conn->prepare($sql_query);
    $stmt->bind_param("i", $postId);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = array();

    if ($result -> num_rows > 0) 
    {
        $response['success'] = true;
        $comments = array();
        while($row = $result->fetch_assoc())
        {
            $userId = $row['userId'];
            $names = $conn->prepare("SELECT * FROM USERS WHERE ID = ?");
            $names->bind_param("i", $userId);
            $names->execute();
            $names_result = $names->get_result()->fetch_assoc();
            $first_name = $names_result['first_name'];
            $last_name = $names_result['last_name'];
            $username = "$first_name $last_name";

            $comment = array(
                "username" => $username,
                "text" => $row['COMMENT_TEXT'],
                "time" => date("h:i d F", strtotime($row['CREATED_AT'])),
                "picture" => "https://api.slingacademy.com/public/sample-photos/2.jpeg",
                "class" => "C'" . substr($names_result['year_group'], 2),
                "major" => strtoupper(preg_replace('/\b(\w)\w*\s*/', '$1', $names_result['major'])) 
            );

            $comments[] = $comment;

        }
        $response['comments'] = $comments;
    } 
    else 
    { 
        $response['success'] = false;
        $response['error'] = $conn -> error;
    }

    echo json_encode($response);
}
