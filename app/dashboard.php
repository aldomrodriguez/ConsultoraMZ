<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Crear Post</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
</head>
<body class="bg-light">
  <div class="container py-5">
    <h2 class="mb-4">Nuevo Post</h2>
    <form action="upload.php" method="POST" enctype="multipart/form-data">
      <div class="mb-3">
        <label class="form-label">Título</label>
        <input type="text" name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Descripción</label>
        <input type="text" name="description" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Imagen</label>
        <input type="file" name="image" class="form-control" accept="image/*" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Fecha</label>
        <input type="date" name="date" class="form-control" value="<?= date('Y-m-d'); ?>" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Contenido (Markdown)</label>
        <textarea name="content" rows="10" class="form-control" required></textarea>
      </div>
      <script>
        CKEDITOR.replace('content');
      </script>
      <button type="submit" class="btn btn-primary">Crear Post</button>
    </form>
  </div>
</body>
</html>