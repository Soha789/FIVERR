<?php /* Home / Explore gigs */ session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>WizardLance • Explore Gigs</title>
<style>
  :root{--parchment:#f5f1e6; --gold:#d4af37; --ink:#1e1b16; --card:#faf7ee; --bg:#0f0d0a;}
  body{margin:0; background:linear-gradient(180deg,#17130f,#0f0d0a); color:var(--parchment); font-family:Georgia,serif}
  header{position:sticky; top:0; background:rgba(15,13,10,.7); border-bottom:1px solid rgba(212,175,55,.25); backdrop-filter: blur(6px)}
  .bar{max-width:1100px; margin:0 auto; padding:14px 18px; display:flex; gap:12px; align-items:center; justify-content:space-between}
  .brand{display:flex; gap:10px; align-items:center; cursor:pointer}
  .crest{width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#7a0f0f,#1b5e20); border:1px solid rgba(212,175,55,.5)}
  nav button{background:transparent; color:var(--parchment); border:1px solid rgba(212,175,55,.4); padding:8px 12px; border-radius:10px; cursor:pointer}
  .tools{display:flex; gap:8px; align-items:center}
  input,select{padding:10px 12px; border-radius:12px; border:1px solid rgba(212,175,55,.25); background:#fffdf7; color:#2a2318}
  .grid{max-width:1100px; margin:18px auto; padding:0 18px; display:grid; gap:16px; grid-template-columns:repeat(auto-fill,minmax(240px,1fr))}
  .card{background:var(--card); color:#1f1a12; border:1px solid rgba(212,175,55,.25); border-radius:16px; overflow:hidden}
  .thumb{height:120px; background:linear-gradient(180deg,rgba(0,0,0,.25),rgba(0,0,0,.5)), url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="600" height="400"><rect width="100%" height="100%" fill="%230f2a5a"/><text x="50%" y="55%" font-size="26" text-anchor="middle" fill="%23f5f1e6" font-family="Georgia">Gig</text></svg>') center/cover no-repeat;}
  .content{padding:14px}
  .price{color:#0f2a5a; font-weight:700}
  .btn{width:100%; margin:10px 0 0; padding:10px 12px; border-radius:10px; border:0; background:linear-gradient(180deg,var(--gold),#b8922e); color:#2b251c; font-weight:800; cursor:pointer}
  .panel{max-width:1100px; margin:10px auto; padding:0 18px 14px}
  .create{background:rgba(212,175,55,.06); border:1px dashed rgba(212,175,55,.25); border-radius:14px; padding:12px}
  .row{display:grid; gap:10px; grid-template-columns:2fr 1fr 1fr 1fr}
  textarea{grid-column: 1/-1; resize: vertical}
  .toplinks{display:flex; gap:8px}
  .pill{padding:8px 10px; border:1px solid rgba(212,175,55,.3); border-radius:999px; cursor:pointer}
</style>
</head>
<body>
<header>
  <div class="bar">
    <div class="brand" onclick="go('index.php')"><div class="crest"></div><strong>WizardLance</strong></div>
    <div class="tools">
      <input id="q" placeholder="Search gigs ..."/>
      <select id="cat"><option value="">All categories</option><option>Graphic Design</option><option>Writing & Translation</option><option>Programming</option><option>Video & Animation</option></select>
      <select id="price"><option value="">Any price</option><option value="0-20">$0–$20</option><option value="20-50">$20–$50</option><option value="50-100">$50–$100</option></select>
      <button onclick="applyFilters()">Search</button>
      <nav class="toplinks">
        <button onclick="go('dashboard.php')">Dashboard</button>
        <button onclick="go('profile.php')">Profile</button>
        <button onclick="go('logout.php')">Logout</button>
      </nav>
    </div>
  </div>
</header>

<div class="panel">
  <div class="create">
    <h3 style="margin:6px 0 10px">Create a new Gig</h3>
    <div class="row">
      <input id="gtitle" placeholder="Gig title (e.g., Design a Hogwarts-style poster)"/>
      <input id="gseller" placeholder="Seller name"/>
      <input id="gprice" type="number" min="5" step="5" placeholder="Price (USD)"/>
      <select id="gcat">
        <option>Graphic Design</option><option>Writing & Translation</option><option>Programming</option><option>Video & Animation</option>
      </select>
      <textarea id="gdesc" rows="3" placeholder="Describe the service..."></textarea>
      <button class="btn" onclick="addGig()">Add Gig</button>
    </div>
  </div>
</div>

<section class="grid" id="list"></section>

<script>
  function go(p){location.href=p;}

  // Auth guard (soft)
  const auth = JSON.parse(localStorage.getItem('authUser')||'null');
  if(!auth){ /* allow browsing but suggest login */ }

  // Load filters from index quick search
  const initialQ = localStorage.getItem('searchQuery')||'';
  const initialCat = localStorage.getItem('searchCategory')||'';
  document.getElementById('q').value = initialQ;
  document.getElementById('cat').value = initialCat;

  function getGigs(){ return JSON.parse(localStorage.getItem('gigs')||'[]'); }
  function setGigs(v){ localStorage.setItem('gigs', JSON.stringify(v)); }

  function addGig(){
    const title = gtitle.value.trim(), seller=gseller.value.trim(), price=Number(gprice.value||0), cat=gcat.value, desc=gdesc.value.trim();
    if(!title||!seller||price<=0){ return alert('Please fill title, seller and price.'); }
    const gigs = getGigs();
    const id = Date.now();
    gigs.push({id,title,seller,price,cat,desc});
    setGigs(gigs);
    render();
    gtitle.value=gseller.value=gprice.value=gdesc.value='';
    alert('Gig added!');
  }

  function applyFilters(){ render(); }

  function withinPrice(p, band){
    if(!band) return true;
    const [a,b] = band.split('-').map(Number);
    return p>=a && p<=b;
  }

  function order(gid){
    const gigs = getGigs();
    const g = gigs.find(x=>x.id===gid);
    if(!auth) { alert('Please login to order.'); return go('login.php'); }
    const orders = JSON.parse(localStorage.getItem('orders')||'[]');
    const id = Date.now();
    orders.push({id,gigId:g.id, buyerId:auth.id, seller:g.seller, title:g.title, price:g.price, status:'Pending', messages:[
      {by:'system', text:'Order created. Use chat to talk with seller.'}
    ]});
    localStorage.setItem('orders', JSON.stringify(orders));
    alert('Order placed! Track it on Dashboard.');
    go('dashboard.php');
  }

  function render(){
    const q = document.getElementById('q').value.toLowerCase();
    const cat = document.getElementById('cat').value;
    const band = document.getElementById('price').value;
    const list = document.getElementById('list'); list.innerHTML='';
    getGigs().filter(g=>{
      const text = (g.title+' '+(g.desc||'')+' '+g.seller+' '+g.cat).toLowerCase();
      return (!q || text.includes(q)) && (!cat || g.cat===cat) && withinPrice(g.price, band);
    }).forEach(g=>{
      const el = document.createElement('article');
      el.className='card';
      el.innerHTML = `
        <div class="thumb"></div>
        <div class="content">
          <div style="font-weight:800">${g.title}</div>
          <div style="opacity:.7">by ${g.seller} • <em>${g.cat}</em></div>
          <div class="price">$${g.price}</div>
          <button class="btn" onclick="order(${g.id})">Order</button>
        </div>`;
      list.appendChild(el);
    });
  }

  render();
</script>
</body>
</html>
