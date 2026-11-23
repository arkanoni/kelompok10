<form method="POST" action="{{ $action }}" style="display:inline" onsubmit="return confirm('Yakin hapus data ini?')">@csrf @method('DELETE')<button class="btn red">Hapus</button></form>
