<?php /* Profile edit */ session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>WizardLance • Profile</title>
<style>
  :root{--parchment:#f5f1e6; --gold:#d4af37; --card:#faf7ee; --ink:#1f1a12;}
  body{margin:0; background:linear-gradient(180deg,#17130f,#0f0d0a); color:var(--parchment); font-family:Georgia,serif}
  header{position:sticky; top:0; background:rgba(15,13,10,.7); border-bottom:1px solid rgba(212,175,55,.25); backdrop-filter: blur(6px)}
  .bar{max-width:900px; margin:0 auto; padding:14px 18px; display:flex; gap:10px; align-items:center; justify-content:space-between}
  .crest{width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#7a0f0f,#1b5e20); border:1px solid rgba(212,175,55,.5)}
  .wrap{max-width:900px; margin:18px auto; padding:0 18px}
  .card{background:var(--card); color:var(--ink); border:1px solid rgba(212,175,55,.25); border-radius:16px; padding:14px}
  label{display:block; margin:10px 0 6px; font-weight:700}
  input,textarea{width:100%; padding:10px; border-radius:10px; border:1px solid rgba(212,175,55,.25); background:#fffdf7}
  .btn{padding:10px 12px; border-radius:10px; border:0; background:linear-gradient(180deg,var(--gold),#b8922e); font-weight:800; cursor:pointer}
</style>
</head>
<body>
<header>
  <div class="bar">
    <div style="display:flex;align-items:center;gap:10px;cursor:pointer" onclick="go('index.php')">
      <div class="crest"></div><strong>WizardLance</strong>
    </div>
    <div>
      <button class="btn" onclick="go('home.php')">Explore</button>
      <button class="btn" onclick="go('dashboard.php')">Dashboard</button>
      <button class="btn" onclick="go('logout.php')">Logout</button>
    </div>
  </div>
</header>

<div class="wrap">
  <section class="card">
    <h3>Profile</h3>
    <label>Name</label><input id="name"/>
    <label>Email (read-only)</label><input id="email" disabled/>
    <label>Bio</label><textarea id="bio" rows="4"></textarea>
    <div style="display:flex; gap:8px; margin-top:10px">
      <button class="btn" onclick="save()">Save</button>
      <button class="btn" onclick="go('home.php')">Back</button>
    </div>
  </section>
</div>

<script>
  function go(p){location.href=p;}
  const auth = JSON.parse(localStorage.getItem('authUser')||'null');
  const users = JSON.parse(localStorage.getItem('users')||'[]');
  if(!auth){ alert('Please login.'); go('login.php'); }
  const u = users.find(x=>x.id===auth.id);
  if(u){
    nameEl.value = u.name; emailEl.value = u.email; bioEl.value = u.bio||'';
  }
  function save(){
    u.name = nameEl.value.trim();
    u.bio = bioEl.value.trim();
    const idx = users.findIndex(x=>x.id===u.id); users[idx]=u;
    localStorage.setItem('users', JSON.stringify(users));
    alert('Profile saved!');
  }
  const nameEl=document.getElementById('name'), emailEl=document.getElementById('email'), bioEl=document.getElementById('bio');
</script>
</body>
</html>
