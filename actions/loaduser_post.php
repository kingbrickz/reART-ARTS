<?php
session_start();
include "../settings/connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') 
{
    $sql = "SELECT * FROM POST WHERE user_id = ? ORDER BY created_at DESC";
    $result = $conn->prepare($sql);
    $result -> bind_param("i", $_SESSION['id']);
    $result -> execute();
    $getresult = $result -> get_result();
    $response = array();

    if ($getresult->num_rows > 0) 
    {
        while ($row = $getresult->fetch_assoc()) 
        {
            $user_id = $row['user_id']; 
            $post_id = $row['id'];
    
            $user_query = "SELECT * FROM USERS WHERE ID = ?";
            $vibes_query = "SELECT count(id) AS vibes FROM LIKES WHERE postId = ?";
            $comments_query = "SELECT count(ID) AS comments FROM COMMENT WHERE POSTID = ?";
            $most_recent = "SELECT * FROM COMMENT WHERE POSTID = ? ORDER BY CREATED_AT DESC LIMIT 1";
    
            $stmt_mostrecent = $conn ->prepare($most_recent);
            $stmt_mostrecent-> bind_param("i", $post_id);
            $stmt_mostrecent-> execute();
            $stmt_mostrecent_result = $stmt_mostrecent->get_result();
    
            $most_recent_text = null;
            $most_recent_username = null;
    
            if($stmt_mostrecent_result-> num_rows > 0)
            {
                $most_recent_row = $stmt_mostrecent_result->fetch_assoc();
                $most_recent_text = $most_recent_row['COMMENT_TEXT'];
                $most_recent_userId = $most_recent_row['userId'];
    
                $most_recent_names = $conn->prepare("SELECT first_name, last_name FROM USERS WHERE ID = ?");
                $most_recent_names->bind_param("i", $most_recent_userId);
                $most_recent_names->execute();
                $most_recent_names_result = $most_recent_names->get_result()->fetch_assoc();
                $most_recent_first_name = $most_recent_names_result !== null ? $most_recent_names_result['first_name'] : '';
                $most_recent_last_name = $most_recent_names_result !== null ? $most_recent_names_result['last_name'] : '';
                $most_recent_username = "$most_recent_first_name $most_recent_last_name";
            }
    
            $sql_query = "SELECT id FROM LIKES WHERE postId = ? AND userId = ?";
            $stmt = $conn->prepare($sql_query);
            $stmt->bind_param("ii", $post_id, $user_id);
            $stmt->execute();
            $result_src = $stmt->get_result();
    
            $image_src = '';
    
            if ($result_src->num_rows > 0) 
            {
                $image_src = "../images/vibed.jpeg";
            }
            else
            {
                $image_src = "../images/vibe.png";
            }
    
            $stmt_vibes = $conn->prepare($vibes_query);
            $stmt_vibes->bind_param("i", $post_id);
            $stmt_vibes->execute();
            $vibes_result = $stmt_vibes ->get_result();
    
            $stmt_comment = $conn->prepare($comments_query);
            $stmt_comment->bind_param("i", $post_id);
            $stmt_comment->execute();
            $comment_result = $stmt_comment->get_result();
    
            $stmt = $conn->prepare($user_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $user_result = $stmt->get_result();

            if (($user_result->num_rows > 0) && ($vibes_result->num_rows > 0) && ($comment_result->num_rows > 0)) 
            {
                $user_row = $user_result->fetch_assoc();
                $vibes_row = $vibes_result->fetch_assoc();
                $comment_row = $comment_result->fetch_assoc();
    
                $vibes = $vibes_row["vibes"];
                $comments = $comment_row["comments"];
                
    
                $username = $user_row["first_name"] . " " . $user_row["last_name"];
                $userPicture = $user_row['picture_path'];
                $year_group = "C'" . substr($user_row['year_group'], 2);
                $initials = strtoupper(preg_replace('/\b(\w)\w*\s*/', '$1', $user_row['major']));
                $formatted_date = date("h:i d F", strtotime($row['created_at']));

                $post_response = array(
                    "major" => $initials,
                    "class" => $year_group,
                    "time" => $formatted_date,
                    "username" => $username,
                    "vibes" => $vibes,
                    "comments" => $comments,
                    "src" => $image_src,
                    "most_recent_text" => $most_recent_text,
                    "most_recent_username" => $most_recent_username,
                    "userId" => $user_id,
                    "postId" => $post_id,
                    "currentUser" => $_SESSION['id'],
                    'content' => $row['content'],
                    'picturepath' => $row['picture_path'],
                    'userPicture' => $userPicture
                );

                $response[] = $post_response;
            }
        }
        echo json_encode($response);
    }
    else
    {
        echo json_encode($response);
    }
}