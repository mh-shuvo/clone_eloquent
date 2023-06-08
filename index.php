<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once "./vendor/autoload.php";
use App\Model\Post;
echo "<pre>";


//$post = new App\Model\Post();
//$post->post_title = "Fun with ML";
//$post->post_description = "To develop machine learning algorithm";
//$data = $post->save();


//$data = Post::where('flag','=',1)->get();
$data = Post::all();
print_r($data);

