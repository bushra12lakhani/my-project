<?php

$hash = '$2y$10$ZCaKpz2gHyugo7Uv1iq/fegt/YwuJT2XZNXa9Q4hbdl7hPtZw.0W6';

if (password_verify('admin123', $hash)) {
    echo "✔ MATCH — Password sahi hai!";
} else {
    echo "❌ MISMATCH — Password galat hai!";
}

?>
