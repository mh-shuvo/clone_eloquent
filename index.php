<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "./vendor/autoload.php";
use App\Model\Post;

// $post = new App\Model\Post();

// $post->name = "posts";

// $data = $post->save();


$data = Post::find(1);
$isDelete = $data->delete();
echo "<pre>";
print_r($data);
echo $isDelete;

