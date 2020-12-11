<?php
//Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Post.php';


//Instantiate DB & connect
$database = new Database();
$db = $database->connect();

//Instantiate Blog post object
$post = new Post($db);

//Get the id from URL
$post->id = isset($_GET['id']) ? $_GET['id'] : die();

//Get Post
$post->read_single();

//create array
$post_arr = array(
    'id' => $post->id,
    'title' => $post->title,
    'body' => $post->body,
    'author' => $post->author,
    'category_id' => $post->category_id,
    'cateogry_name' => $post->category_name
);

//Make Json
print_r(json_encode($post_arr));