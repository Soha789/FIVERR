<?php /* Login */ session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>WizardLance • Login</title>
<style>
  :root{--parchment:#f5f1e6; --gold:#d4af37; --bg:#14110d; --card:#faf7ee;}
  body{margin:0; background:radial-gradient(900px 300px at 50% -10%,rgba(212,175,55,.08),transparent 60%), #0f0d0a; color:var(--parchment); font-family:Georgia,serif}
  .wrap{max-width:420px; margin:60px auto; padding:18px}
  .card{background:var(--card); color:#251d12; border:1px solid rgba(212,175,55,.35); border-radius:16px; padding:20px 18px; box-shadow:0 10px 24px rgba(0,0,0,.35)}
  h2{margin:0 0 12px}
  label{display:block; font-weight:700; margin:12px 0 6px}
  input{width:100%; padding:12px; border-radius:12px; border:1px solid rgba(212,175,55,.3); background:#fffdf7}
  .btn{width:100%; margin-top:14px; padding:12px; border-radius:12px; border:0; background:linear-gradient(180deg,var(--gold),#b8922e); font-weight:800; cursor:pointer}
  .link{margin-top:10px; text-align:center}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h2>Welcome back</h2>a
    <label>Email</label><input id="email" placeholder="you@owlpost.com"/>
    <label>Password</label><input id="pass" type="password" placeholder="••••••"/>
    <button class="btn" onclick="login()">Login</button>
    <div class="link">New here? <a href="#" onclick="go('signup.php')">Create an account</a></div>
  </div>
</div>
<script>
  function go(p){location.href=p;}
  function toast(m){alert(m);}
  function login(){
    const email = emailEl.value.trim(), pass = passEl.value;
    const users = JSON.parse(localStorage.getItem('users')||'[]');
    const u = users.find(x=>x.email===email && x.pass===pass);
    if(!u) return toast('Invalid email or password.');
    localStorage.setItem('authUser', JSON.stringify({id:u.id,email:u.email}));
    toast('Logged in!');
    go('home.php');
  }
  const emailEl=document.getElementById('email'), passEl=document.getElementById('pass');
</script>
</body>
</html>
