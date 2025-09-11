<?php
// includes/functions.php
function e($str) { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect($path) { header("Location: $path"); exit; }
function now() { return date('Y-m-d H:i:s'); }
