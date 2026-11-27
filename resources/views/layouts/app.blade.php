@php $role = $role ?? (auth()->user()->role ?? request('role', 'admin')); @endphp
<!DOCTYPE html><html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title') - SiTOKO</title>
<style>
*{box-sizing:border-box}body{margin:0;display:flex;min-height:100vh;font-family:system-ui,sans-serif;background:#f3f6f6;color:#1d2b2a}
nav{width:210px;flex-shrink:0;background:#12332f;padding:18px}nav b{display:block;color:#fff;font-size:22px;margin-bottom:14px}
nav a{display:block;padding:7px 0;color:#bcd4d1;text-decoration:none}nav a:hover{color:#fff}nav form{margin-top:14px}
main{flex:1;min-width:0;padding:24px}h1{margin:0 0 16px;font-size:22px}
.card{background:#fff;border:1px solid #d9e2e1;border-radius:8px;padding:16px;margin-bottom:16px;overflow-x:auto}
table{width:100%;border-collapse:collapse}th,td{text-align:left;padding:8px;border-bottom:1px solid #e6eceb}
label{font-size:14px}input,select,textarea{width:100%;padding:8px;margin:4px 0 12px;border:1px solid #b8c4c3;border-radius:6px;font:inherit}
.btn{display:inline-block;padding:6px 12px;background:#0f766e;color:#fff;border:0;border-radius:6px;font-size:14px;text-decoration:none;cursor:pointer}
.red{background:#b91c1c}.gray{background:#64748b}.tag{padding:2px 8px;background:#dcebe9;border-radius:10px;font-size:12px}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:12px}.grid .card{margin:0}
@media(max-width:700px){body{display:block}nav{width:auto}}
@media print{nav,.noprint{display:none}}
</style></head><body>
<nav><b>SiTOKO</b>
<a href="/dashboard">Dashboard</a>
@if($role != 'user')<a href="/categories">Kategori</a><a href="/products">Produk</a><a href="/products/stock">Stok</a><a href="/orders">Pesanan</a><a href="/reports/inventory">Laporan</a>@endif
@if($role == 'admin')<a href="/users">Akun pengguna</a>@endif
@if($role == 'user')<a href="/shop">Produk</a><a href="/shop/orders">Pesanan saya</a>@endif
<a href="/profile">Profil</a>
<form method="POST" action="/logout">@csrf<button class="btn gray">Logout</button></form></nav>
<main>@if(session('success'))<div class="card">{{ session('success') }}</div>@endif
@yield('content')</main></body></html>
