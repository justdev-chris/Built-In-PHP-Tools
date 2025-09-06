<?php
$uploadDir = "uploads/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fileUrl = "";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $fileName = basename($file['name']);
    $targetFile = $uploadDir . $fileName;

    // Max file size (100 MB in bytes)
    $maxFileSize = 100 * 1024 * 1024; 

    // Allowed types
    $allowedTypes = [
        "jpg", "jpeg", "png", "gif", "webp",
        "pdf", "txt", "doc", "docx", "xls", "xlsx", 
        "zip", "rar", "7z", "mp4", "mp3", "wav", "exe"
    ];

    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if ($file['size'] > $maxFileSize) {
        $message = "<p style='color:red;'>❌ File too big! Max size is 100 MB.</p>";
    } elseif (!in_array($fileExt, $allowedTypes)) {
        $message = "<p style='color:red;'>❌ File type not allowed.</p>";
    } elseif (move_uploaded_file($file['tmp_name'], $targetFile)) {
        $fileUrl = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . "" . $targetFile;
        $message = "<p style='color:green;'>✅ File uploaded successfully!</p>";
    } else {
        $message = "<p style='color:red;'>❌ Error uploading file.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TransferNest 🐱</title>
  <style>
    body { 
      font-family: Arial, sans-serif; 
      background: #1e1e2e; 
      color: #fff; 
      text-align: center; 
      padding: 50px;
    }
    .upload-box {
      border: 2px dashed #6c63ff;
      padding: 40px;
      border-radius: 15px;
      background: #2a2a40;
      max-width: 600px;
      margin: auto;
      position: relative;
    }
    .upload-box.dragover {
      border-color: #00ffcc;
      background: #202030;
    }
    input[type=file] {
      display: none;
    }
    label {
      padding: 10px 20px;
      background: #6c63ff;
      border-radius: 5px;
      cursor: pointer;
    }
    label:hover {
      background: #574bff;
    }
    button {
      margin-top: 10px;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      background: #00ffcc;
      color: #000;
      cursor: pointer;
    }
    button:hover {
      background: #00d9aa;
    }
    .cat-img {
      position: absolute;
      top: -20px;
      right: 120px;
      width: 120px;
    }
    .link-box {
      margin-top: 20px;
      padding: 10px;
      background: #333;
      border-radius: 8px;
      display: inline-block;
    }
    .copy-btn {
      margin-left: 10px;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      background: #6c63ff;
      color: #fff;
      cursor: pointer;
    }
    .copy-btn:hover {
      background: #574bff;
    }
  </style>
</head>
<body>
  <h1>🚀 TransferNest 🐾</h1>
  <div class="upload-box" id="drop-area">
    <form method="POST" enctype="multipart/form-data">
      <input type="file" name="file" id="fileInput">
      <label for="fileInput">Choose a file 🐱</label>
      <p>...or drag & drop here 🐾</p>
      <button type="submit">Upload</button>
      <img src="bk3.png" alt="Cat Mascot" class="cat-img">
    </form>
    <?php 
      if (!empty($message)) echo $message; 
      if (!empty($fileUrl)) {
          echo "<div class='link-box'>📂 <a href='$fileUrl' target='_blank'>$fileUrl</a> 
          <button class='copy-btn' onclick='copyLink(\"$fileUrl\")'>Copy Link</button></div>";
      }
    ?>
  </div>

  <script>
    const dropArea = document.getElementById("drop-area");
    const fileInput = document.getElementById("fileInput");

    dropArea.addEventListener("dragover", (e) => {
      e.preventDefault();
      dropArea.classList.add("dragover");
    });

    dropArea.addEventListener("dragleave", () => {
      dropArea.classList.remove("dragover");
    });

    dropArea.addEventListener("drop", (e) => {
      e.preventDefault();
      dropArea.classList.remove("dragover");
      fileInput.files = e.dataTransfer.files;
    });

    function copyLink(link) {
      navigator.clipboard.writeText(link).then(() => {
        alert("✅ Link copied to clipboard!");
      });
    }
  </script>
</body>
</html>
