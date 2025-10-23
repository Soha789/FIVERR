<?php /* Dashboard */ session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>WizardLance • Dashboard</title>
<style>
  :root{--parchment:#f5f1e6; --gold:#d4af37; --card:#faf7ee; --ink:#1f1a12;}
  body{margin:0; background:#0f0d0a; color:var(--parchment); font-family:Georgia,serif}
  header{position:sticky; top:0; background:rgba(15,13,10,.7); border-bottom:1px solid rgba(212,175,55,.25); backdrop-filter: blur(6px)}
  .bar{max-width:1100px; margin:0 auto; padding:14px 18px; display:flex; gap:10px; align-items:center; justify-content:space-between}
  .crest{width:28px;height:28px;border-radius:8px;background:linear-gradient(135deg,#7a0f0f,#1b5e20); border:1px solid rgba(212,175,55,.5)}
  .wrap{max-width:1100px; margin:18px auto; padding:0 18px; display:grid; gap:16px; grid-template-columns: 1fr 2fr}
  .card{background:var(--card); color:var(--ink); border:1px solid rgba(212,175,55,.25); border-radius:16px; padding:14px}
  .stat{display:grid; grid-template-columns:1fr 1fr; gap:8px}
  .order{border:1px solid rgba(212,175,55,.25); border-radius:12px; padding:10px; margin-bottom:10px}
  .tag{display:inline-block; padding:4px 8px; border-radius:999px; border:1px solid rgba(212,175,55,.35); font-size:12px}
  .chat{border-top:1px dashed rgba(0,0,0,.2); margin-top:8px; padding-top:8px}
  .msg{padding:6px 8px; border-radius:10px; background:#fffdf7; margin:6px 0}
  .me{background:#e9e2cf}
  input,select,textarea{width:100%; padding:10px; border-radius:10px; border:1px solid rgba(212,175,55,.25); background:#fffdf7}
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
      <button class="btn" onclick="go('profile.php')">Profile</button>
      <button class="btn" onclick="go('logout.php')">Logout</button>
    </div>
  </div>
</header>

<div class="wrap">
  <section class="card">
    <h3>My Stats</h3>
    <div class="stat">
      <div><strong>Total Orders</strong><div id="statOrders">0</div></div>
      <div><strong>Earnings</strong><div id="statEarnings">$0</div></div>
    </div>
    <hr/>
    <h4>Add Funds (Demo Wallet)</h4>
    <p style="opacity:.8">Add demo money to your buyer wallet to place orders.</p>
    <div style="display:flex; gap:8px">
      <input id="addAmount" type="number" min="5" step="5" placeholder="Amount (USD)"/>
      <button class="btn" onclick="addFunds()">Add</button>
    </div>
    <div style="margin-top:8px">Wallet Balance: <strong id="wallet">$0</strong></div>
  </section>

  <section class="card">
    <h3>Orders</h3>
    <div id="orders"></div>
  </section>
</div>

<script>
  function go(p){location.href=p;}
  const auth = JSON.parse(localStorage.getItem('authUser')||'null');
  const users = JSON.parse(localStorage.getItem('users')||'[]');
  function getUser(){ return auth ? users.find(u=>u.id===auth.id) : null; }
  function saveUser(u){
    const idx = users.findIndex(x=>x.id===u.id);
    if(idx>-1){ users[idx]=u; localStorage.setItem('users', JSON.stringify(users)); }
  }
  function getOrders(){ return JSON.parse(localStorage.getItem('orders')||'[]'); }
  function setOrders(v){ localStorage.setItem('orders', JSON.stringify(v)); }

  function addFunds(){
    const amt = Number(document.getElementById('addAmount').value||0);
    if(amt<=0) return alert('Enter amount.');
    const u = getUser(); if(!u) return alert('Login required.');
    u.balance = (u.balance||0) + amt;
    saveUser(u); render();
    alert('Funds added to wallet!');
  }

  function updateStatus(id, status){
    const orders = getOrders();
    const o = orders.find(x=>x.id===id);
    if(!o) return;
    o.status = status;
    setOrders(orders); render();
  }

  function sendMsg(id){
    const inp = document.getElementById('msg-'+id);
    const text = inp.value.trim(); if(!text) return;
    const orders = getOrders(); const o = orders.find(x=>x.id===id);
    o.messages.push({by:'me', text});
    setOrders(orders); inp.value=''; render();
  }

  function render(){
    const ordersBox = document.getElementById('orders'); ordersBox.innerHTML='';
    const my = getUser();
    const all = getOrders();
    // In this demo, all orders belong to buyer (auth user) who placed them.
    const mine = my ? all.filter(o=>o.buyerId===my.id) : [];
    // Earnings calc: pretend platform fee 10%
    const earnings = mine.filter(o=>o.status==='Completed').reduce((s,o)=>s + o.price*0.9, 0);
    document.getElementById('statOrders').textContent = mine.length;
    document.getElementById('statEarnings').textContent = '$'+earnings.toFixed(2);
    document.getElementById('wallet').textContent = '$'+(my ? (my.balance||0).toFixed(2) : '0.00');

    mine.forEach(o=>{
      const div = document.createElement('div'); div.className='order';
      const msgs = o.messages.map(m=>`<div class="msg ${m.by==='me'?'me':''}"><strong>${m.by==='me'?'You':(m.by||'System')}:</strong> ${m.text}</div>`).join('');
      div.innerHTML = `
        <div><strong>${o.title}</strong> — <span class="tag">${o.status}</span></div>
        <div style="opacity:.7">Seller: ${o.seller} • $${o.price}</div>
        <div class="chat">
          <div style="font-weight:700;margin:6px 0">Messages</div>
          ${msgs}
          <div style="display:flex; gap:6px; margin-top:6px">
            <input id="msg-${o.id}" placeholder="Write a message to the seller..."/>
            <button class="btn" onclick="sendMsg(${o.id})">Send</button>
          </div>
        </div>
        <div style="display:flex; gap:8px; margin-top:8px">
          <select id="st-${o.id}">
            <option ${o.status==='Pending'?'selected':''}>Pending</option>
            <option ${o.status==='In Progress'?'selected':''}>In Progress</option>
            <option ${o.status==='Delivered'?'selected':''}>Delivered</option>
            <option ${o.status==='Completed'?'selected':''}>Completed</option>
            <option ${o.status==='Cancelled'?'selected':''}>Cancelled</option>
          </select>
          <button class="btn" onclick="updateStatus(${o.id}, document.getElementById('st-${o.id}').value)">Update Status</button>
        </div>
      `;
      ordersBox.appendChild(div);
    });
  }
  render();
</script>
</body>
</html>
