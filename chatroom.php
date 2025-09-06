<?php
// --- CONFIG ---
$file = 'messages.txt';
if (!file_exists($file)) file_put_contents($file, '');

// --- HANDLE POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['message'])) {
    $user = htmlspecialchars($_POST['username']);
    $msg = htmlspecialchars($_POST['message']);
    $time = date("Y-m-d H:i:s");
    file_put_contents($file, "$time|$user|$msg\n", FILE_APPEND | LOCK_EX);
    exit('ok');
}

// --- HANDLE FETCH ---
if (isset($_GET['action']) && $_GET['action'] === 'fetch') {
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $messages = [];
    foreach ($lines as $line) {
        list($time, $user, $msg) = explode('|', $line);
        $messages[] = ['timestamp'=>$time,'username'=>$user,'message'=>$msg];
    }
    header('Content-Type: application/json');
    echo json_encode($messages);
    exit;
}
// --- FRONTEND HTML ---
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>🐾 Chat Room. 🐾</title>
<style>
body { font-family: Arial, sans-serif; background:#f0f0f0; padding:20px; }
#chat { border:1px solid #ccc; padding:10px; height:300px; overflow:auto; background:white; }
input, button { padding:8px; margin:5px; }
button { cursor:pointer; }
</style>
</head>
<body>
<h2> 🐱 Chat Room 🐱</h2>
<input id="username" placeholder="Your name">
<input id="msg" placeholder="Type a message">
<button onclick="sendMessage()">Send</button>
<div id="chat"></div>

<script>
async function sendMessage(){
  const user = document.getElementById('username').value || 'Anonymous';
  const msg = document.getElementById('msg').value;
  if(!msg) return;
  await fetch('', {
    method:'POST',
    body: new URLSearchParams({username: user, message: msg})
  });
  document.getElementById('msg').value = '';
  fetchMessages();
}

async function fetchMessages(){
  const res = await fetch('?action=fetch');
  const data = await res.json();
  document.getElementById('chat').innerHTML = data.map(m => `<b>${m.username}</b> [${m.timestamp}]: ${m.message}`).join('<br>');
  document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;
}

setInterval(fetchMessages, 1000);
fetchMessages();
</script>
</body>
</html>
