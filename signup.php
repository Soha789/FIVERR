<?php /* Sign Up */ session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>WizardLance • Sign Up</title>
<style>
  :root{--parchment:#f5f1e6; --gold:#d4af37; --bg:#14110d; --card:#faf7ee; --maroon:#7a0f0f;}
  body{margin:0; background:linear-gradient(180deg,#191510,#0f0d0a); color:var(--parchment); font-family:Georgia,serif}
  .wrap{max-width:420px; margin:40px auto; padding:18px}
  .card{background:var(--card); color:#251d12; border:1px solid rgba(212,175,55,.35); border-radius:16px; padding:20px 18px; box-shadow:0 10px 24px rgba(0,0,0,.35)}
  h2{margin:0 0 12px}
  label{display:block; font-weight:700; margin:12px 0 6px}
  input,textarea{width:100%; padding:12px; border-radius:12px; border:1px solid rgba(212,175,55,.3); background:#fffdf7}
  .btn{width:100%; margin-top:14px; padding:12px; border-radius:12px; border:0; background:linear-gradient(180deg,var(--gold),#b8922e); font-weight:800; cursor:pointer}
  .link{margin-top:10px; text-align:center}
  .crest{width:42px; height:42px; border-radius:8px; background:linear-gradient(135deg,var(--maroon),#1b5e20); border:1px solid rgba(212,175,55,.5); margin:0 auto 12px}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <div class="crest"></div>
    <h2>Join WizardLance</h2>
    <p style="margin:0 0 10px; opacity:.8">Create your account to buy & sell magical services.</p>
    <label>Name</label><input id="name" placeholder="E.g., Luna Lovegood"/>
    <label>Email</label><input id="email" placeholder="you@owlpost.com"/>
    <label>Password</label><input id="pass" type="password" placeholder="Min 6 chars"/>
    <label>Short Bio</label><textarea id="bio" rows="3" placeholder="I brew delightful code potions..."></textarea>
    <button class="btn" onclick="signup()">Create Account</button>
    <div class="link">Already have an account? <a href="#" onclick="go('login.php')">Login</a></div>
  </div>
</div>
<script>
  function go(p){location.href=p;}
  function toast(msg){alert(msg);}
  function signup(){
    const name = nameEl.value.trim(), email=emailEl.value.trim(), pass=passEl.value, bio=bioEl.value.trim();
    if(!name||!email||pass.length<6){ return toast('Please fill all fields (password ≥ 6).'); }
    const users = JSON.parse(localStorage.getItem('users')||'[]');
    if(users.some(u=>u.email===email)) return toast('Email already registered.');
    const user = {id: Date.now(), name, email, pass, bio, balance: 0};
    users.push(user);
    localStorage.setItem('users', JSON.stringify(users));
    localStorage.setItem('authUser', JSON.stringify({id:user.id,email:user.email}));
    toast('Welcome to WizardLance!');
    go('home.php');
  }
  const nameEl=document.getElementById('name'), emailEl=document.getElementById('email'), passEl=document.getElementById('pass'), bioEl=document.getElementById('bio');
</script>
</body>
</html>
