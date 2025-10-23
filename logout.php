<?php /* JS redirect logout */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Logging out...</title>
<style>
  body{margin:0; display:grid; place-items:center; min-height:100dvh; background:#0f0d0a; color:#f5f1e6; font-family:Georgia,serif}
  .box{padding:24px; border:1px solid rgba(212,175,55,.35); border-radius:14px; background:#1b1814}
</style>
</head>
<body>
  <div class="box">
    <h3>Logging you out</h3>
    <p>May your next spell be strong! Redirecting to homepage...</p>
  </div>
<script>
  localStorage.removeItem('authUser');
  setTimeout(()=>location.href='index.php', 900);
</script>
</body>
</html>
