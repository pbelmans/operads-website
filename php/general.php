<?php

function href($path) {
  global $config;
  return $config["directory"] . "/" . $path;
}

function getOperads() {
  global $database;

  $sql = $database->prepare('SELECT tag, label, file, chapter_page, book_page, book_id, value, name, type, position FROM tags WHERE tag = :tag');
  $sql->bindParam(':tag', $tag);

  if ($sql->execute()) {
    // return first (= only) row of the result
    while ($row = $sql->fetch()) return $row;
  }
  return null;

}
