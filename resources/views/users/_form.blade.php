@php $u = $user ?? null; @endphp
<form method="POST" action="{{ $u ? '/users/'.$u->id : '/users' }}">@csrf @if($u) @method('PUT') @endif
<label>Nama</label><input name="name" value="{{ $u->name ?? '' }}" required>
<label>Email</label><input type="email" name="email" value="{{ $u->email ?? '' }}" required>
<label>Role</label><select name="role">@foreach(['admin','pegawai','user'] as $r)<option value="{{ $r }}" @selected(($u->role ?? '') == $r)>{{ $r }}</option>@endforeach</select>
<label>Password @if($u)(kosongkan bila tidak diganti)@endif</label><input type="password" name="password">
<button class="btn">Simpan akun</button> <a class="btn gray" href="/users">Batal</a></form>
