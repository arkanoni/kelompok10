@php $c = $category ?? null; @endphp
<form method="POST" action="{{ $c ? '/categories/'.$c->id : '/categories' }}">@csrf @if($c) @method('PUT') @endif
<label>Nama kategori</label><input name="name" value="{{ $c->name ?? '' }}" required>
<label>Deskripsi</label><textarea name="description" rows="3">{{ $c->description ?? '' }}</textarea>
<button class="btn">Simpan kategori</button> <a class="btn gray" href="/categories">Batal</a></form>
