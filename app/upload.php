<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}

// Rutas
$imagesDir = __DIR__ . '/../public/images/';
$blogDir = __DIR__ . '/../src/content/blog/';

// Validar datos recibidos
if (
    empty($_POST['title']) ||
    empty($_POST['description']) ||
    empty($_POST['date']) ||
    empty($_POST['content']) ||
    empty($_FILES['image'])
) {
    die('Todos los campos son obligatorios.');
}

// Verificar si el directorio de imágenes existe y tiene permisos
if (!is_dir($imagesDir)) {
    if (!mkdir($imagesDir, 0777, true)) {
        die("No se pudo crear el directorio de imágenes: $imagesDir");
    }
}
if (!is_writable($imagesDir)) {
    die("El directorio de imágenes no tiene permisos de escritura: $imagesDir");
}

// Obtener datos del formulario
$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
$description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');
$date = $_POST['date'] . "T05:00:00Z";
$content = htmlspecialchars($_POST['content'], ENT_QUOTES, 'UTF-8');
$image = $_FILES['image'];

// Generar nombre único para la imagen
$imgExt = pathinfo($image['name'], PATHINFO_EXTENSION);
$imgName = uniqid('img_', true) . '.' . strtolower($imgExt);
$imgPath = $imagesDir . $imgName;
$imgUrl = '/images/' . $imgName;

// Subir imagen
if (!move_uploaded_file($image['tmp_name'], $imgPath)) {
    die("❌ Error al subir imagen. Verificá los permisos en: $imagesDir");
}

// Generar slug para el archivo markdown
$slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', pathinfo($imgName, PATHINFO_FILENAME)));
$filename = $blogDir . 'blog-' . $slug . '.md';

// Frontmatter para el .md
$frontmatter = <<<EOD
---
title: "$title"
description: "$description"
image: "$imgUrl"
date: $date
draft: false
---

$content
EOD;

// Verificar directorio del blog
if (!is_dir($blogDir)) {
    if (!mkdir($blogDir, 0777, true)) {
        die("No se pudo crear el directorio del blog: $blogDir");
    }
}
if (!is_writable($blogDir)) {
    die("El directorio del blog no tiene permisos de escritura: $blogDir");
}

// Guardar archivo .md
if (!file_put_contents($filename, $frontmatter)) {
    die("❌ Error al guardar el archivo. Verificá los permisos en: $blogDir");
}

echo "<p>✅ Post creado correctamente. Archivo generado: <strong>$filename</strong>. <a href='dashboard.php'>Volver</a></p>";
