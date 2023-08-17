<?php

namespace Rpurinton\qi;

header('Content-Type: application/json');

require_once(__DIR__ . '/../../src/Session.php');
$session = new Session(false);
$original_file_name = explode('/', $_SERVER['REQUEST_URI'])[3];
$original_file_extension = substr($original_file_name, strrpos($original_file_name, '.') + 1);
$target_dir = __DIR__ . "/uploads/inbox/";
$random_file_name = $target_dir . bin2hex(random_bytes(16)) . '.' . $original_file_extension;
file_put_contents($random_file_name, file_get_contents('php://input')) or die(json_encode(['message' => 'Failed to save original file']));
$original_file_size = filesize($random_file_name) or die(json_encode(['message' => 'Failed to get original file size']));
$original_file_sha256 = hash_file('sha256', $random_file_name) or die(json_encode(['message' => 'Failed to hash original file']));
$file_hash_prefix1 = substr($original_file_sha256, 0, 2);
$file_hash_suffix2 = substr($original_file_sha256, 2, 2);
$resting_place = __DIR__ . "/uploads/$file_hash_prefix1/$file_hash_suffix2/";
if (!file_exists($resting_place)) mkdir($resting_place, 0777, true);
$final_resting_place = $resting_place . $original_file_sha256 . '.' . $original_file_extension;
rename($random_file_name, $final_resting_place) or die(json_encode(['message' => 'Failed to move original file to resting place']));
$final_file_size = filesize($final_resting_place) or die(json_encode(['message' => 'Failed to get final file size']));
$final_file_size == $original_file_size or die(json_encode(['message' => 'Final file size does not match original file size']));
$final_file_sha256 = hash_file('sha256', $final_resting_place) or die(json_encode(['message' => 'Failed to hash final file']));
$final_file_sha256 == $original_file_sha256 or die(json_encode(['message' => 'Final file hash does not match original file hash']));
if (in_array(strtolower($original_file_extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
    $thumbs_dir = __DIR__ . "/uploads/thumbs/$file_hash_prefix1/$file_hash_suffix2/";
    if (!file_exists($thumbs_dir)) mkdir($thumbs_dir, 0777, true);
    $thumb_file_name = $thumbs_dir . $original_file_sha256 . '.jpg';
    if (file_exists($thumb_file_name)) $thumbnail_url = "/views/new-idea/upload/thumbs/$file_hash_prefix1/$file_hash_suffix2/$original_file_sha256.jpg";
    else {
        $thumbnail_url = "/views/new-idea/upload/thumbs/$file_hash_prefix1/$file_hash_suffix2/$original_file_sha256.jpg";
        exec("convert $final_resting_place -thumbnail 80x80 $thumb_file_name") or die(json_encode(['message' => 'Failed to create thumbnail']));
        file_exists($thumb_file_name) or die(json_encode(['message' => 'Thumbnail not created']));
    }
}
$mime_type = mime_content_type($final_resting_place) or die(json_encode(['message' => 'Failed to get mime type']));
$session->sql->query("INSERT INTO `attachments` (`sha256`,`user_id`, `file_name`, `file_type`, `file_path`) VALUES  ('$original_file_sha256', '{$session->user_id}', '$original_file_name', '$mime_type', '$final_resting_place');") or die(json_encode(['message' => 'Failed to insert into DB']));
$message = [
    "message" => "File uploaded successfully",
    "attachment_id" => $original_file_sha256,
    "thumbnail" => $thumb_file_name ?? null,
];
echo json_encode($message);
