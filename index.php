<?php /* Harry Potter–themed Fiverr-like homepage */ ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>WizardLance • Marketplace for Magical Gigs</title>
<style>
  :root{
    --parchment:#f5f1e6; --ink:#1e1b16; --gold:#d4af37; --maroon:#7a0f0f;
    --green:#1b5e20; --blue:#0f2a5a; --smoke:#29251f; --soft:#fffdf7; --card:#faf7ee;
  }
  *{box-sizing:border-box}
  body{
    margin:0; background:linear-gradient(180deg,var(--smoke),#14110d 40%,var(--blue) 100%);
    color:var(--parchment); font-family: "Georgia", "Times New Roman", serif;
  }
  header{
    position:sticky; top:0; z-index:3; backdrop-filter: blur(6px);
    background:rgba(20,17,13,.6); border-bottom:1px solid rgba(212,175,55,.25);
  }
  .wrap{max-width:1100px; margin:0 auto; padding:18px}
  .nav{display:flex; align-items:center; gap:16px; justify-content:space-between}
  .brand{display:flex; align-items:center; gap:10px; cursor:pointer}
  .crest{
    width:38px; height:38px; border-radius:8px; background:
      radial-gradient(circle at 30% 30%,var(--gold),transparent 60%),
      linear-gradient(135deg,var(--maroon),var(--green));
    border:1px solid rgba(212,175,55,.6); box-shadow: 0 0 18px rgba(212,175,55,.25) inset;
  }
  h1.logo{font-size:22px; margin:0; letter-spacing:.5px}
  .nav .actions button{
    background:transparent; color:var(--parchment); border:1px solid var(--gold);
    padding:10px 14px; border-radius:10px; cursor:pointer; transition:.25s; font-weight:600;
  }
  .nav .actions button:hover{background:rgba(212,175,55,.12)}
  .hero{padding:64px 18px 28px; text-align:center; background:
      radial-gradient(1000px 400px at 50% -10%,rgba(212,175,55,.07),transparent 60%);
  }
  .hero h2{font-size:36px; margin:0 0 10px; color:var(--soft); text-shadow:0 1px 0 #000}
  .hero p{opacity:.9; margin:0 auto 18px; max-width:760px}
  .searchbar{display:flex; gap:8px; justify-content:center; margin:16px auto 0; max-width:720px}
  .searchbar input{
    flex:1; padding:12px 14px; border-radius:12px; border:1px solid rgba(212,175,55,.25);
    background:var(--card); color:#2b251c; font-family:serif;
  }
  .searchbar button{
    padding:12px 16px; border-radius:12px; border:0; background:var(--gold); color:#241e13;
    font-weight:700; cursor:pointer;
  }
  .chips{display:flex; flex-wrap:wrap; gap:10px; justify-content:center; margin-top:14px}
  .chip{
    padding:8px 12px; border:1px solid rgba(212,175,55,.3); border-radius:999px; background:rgba(245,241,230,.1);
    cursor:pointer; transition:.2s;
  }
  .chip:hover{background:rgba(245,241,230,.18)}
  .grid{max-width:1100px; margin:30px auto; padding:0 18px; display:grid; gap:16px;
    grid-template-columns: repeat(auto-fill,minmax(230px,1fr));
  }
  .card{
    background:var(--card); color:#1f1a12; border:1px solid rgba(212,175,55,.25);
    border-radius:16px; overflow:hidden; box-shadow:0 8px 20px rgba(0,0,0,.25);
  }
  .thumb{height:140px; background:
      linear-gradient(180deg,rgba(0,0,0,.15),rgba(0,0,0,.45)),
      url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400"><rect width="100%" height="100%" fill="%237a0f0f"/><text x="50%" y="55%" font-size="28" text-anchor="middle" fill="%23f5f1e6" font-family="Georgia">Featured Gig</text></svg>')
      center/cover no-repeat;}
  .card .content{padding:14px}
  .price{color:#0f2a5a; font-weight:700}
  .btn{
    appearance:none; width:100%; margin:10px 0 0; padding:10px 12px; border-radius:10px; border:0;
    background:linear-gradient(180deg,var(--gold),#b8922e); color:#2b251c; font-weight:800; cursor:pointer;
  }
  footer{border-top:1px solid rgba(212,175,55,.2); margin-top:32px; padding:20px 18px; text-align:center; opacity:.85}
  a.inline{color:var(--gold); cursor:pointer; text-decoration:underline dotted}
</style>
</head>
<body>
<header>
  <div class="wrap nav">
    <div class="brand" onclick="go('index.php')">
      <div class="crest" aria-hidden="true"></div>
      <h1 class="logo">WizardLance</h1>
    </div>
    <div class="actions">
      <button onclick="go('login.php')">Login</button>
      <button onclick="go('signup.php')">Sign Up</button>
    </div>
  </div>
</header>

<section class="hero">
  <h2>Hire Wizards of Every Craft</h2>
  <p>Discover enchanted design, spellbinding writing, and code brewed to perfection. A Fiverr-style marketplace in a Harry Potter aesthetic.</p>
  <div class="searchbar">
    <input id="q" placeholder="Try: logo design, essay editing, PHP website..." />
    <button onclick="search()">Search</button>
  </div>
  <div class="chips">
    <div class="chip" onclick="quick('Graphic Design')">Graphic Design</div>
    <div class="chip" onclick="quick('Writing & Translation')">Writing & Translation</div>
    <div class="chip" onclick="quick('Programming')">Programming</div>
    <div class="chip" onclick="quick('Video & Animation')">Video & Animation</div>
  </div>
</section>

<section class="grid" id="featured"></section>

<footer>
  <small>By entering, you accept our <a class="inline" onclick="alert('House Rules: Be kind, deliver on time, no dark magic.')">House Rules</a>.</small>
</footer>

<script>
  // JS-only navigation
  function go(path){ window.location.href = path; }
  function search(){
    const q = document.getElementById('q').value.trim();
    localStorage.setItem('searchQuery', q);
    go('home.php');
  }
  function quick(cat){
    localStorage.setItem('searchCategory', cat);
    go('home.php');
  }

  // Seed sample gigs if empty
  const seedGigs = [
    {id:1,title:"Design a Hogwarts-style crest logo", seller:"RavenQuill", price:25, cat:"Graphic Design"},
    {id:2,title:"Proofread your OWL / NEWT essay", seller:"HermioneWrites", price:15, cat:"Writing & Translation"},
    {id:3,title:"Build a PHP site with wizard UI", seller:"CoderWizard", price:70, cat:"Programming"},
    {id:4,title:"Create a magical intro animation", seller:"SpellMotion", price:40, cat:"Video & Animation"}
  ];
  if(!localStorage.getItem('gigs')){
    localStorage.setItem('gigs', JSON.stringify(seedGigs));
  }

  // Render featured
  const grid = document.getElementById('featured');
  JSON.parse(localStorage.getItem('gigs')||'[]').forEach(g=>{
    const el = document.createElement('article');
    el.className='card';
    el.innerHTML = `
      <div class="thumb"></div>
      <div class="content">
        <div style="font-weight:800">${g.title}</div>
        <div style="opacity:.75">by ${g.seller} • <em>${g.cat}</em></div>
        <div class="price">Starting at $${g.price}</div>
        <button class="btn" onclick="view(${g.id})">View Gig</button>
      </div>`;
    grid.appendChild(el);
  });
  function view(id){
    localStorage.setItem('viewGigId', id);
    go('home.php');
  }
</script>
</body>
</html>
