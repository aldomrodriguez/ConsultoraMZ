<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = __DIR__ . "/../public/images/";
    $image = $_FILES['image'];

    if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

    $targetFile = $targetDir . basename($image['name']);

    if (move_uploaded_file($image['tmp_name'], $targetFile)) {
        echo "✅ Imagen subida con éxito.";
    } else {
        echo "❌ Error al subir imagen.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="image">
    <button type="submit">Subir</button>
</form>
