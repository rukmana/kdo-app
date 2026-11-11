@props(['action', 'label' => 'Hapus', 'confirm' => 'Yakin ingin menghapus data ini?'])
<form method="POST" action="{{ $action }}" onsubmit="return confirm(@js($confirm))" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="{{ $attributes->get('class', 'text-red-600 hover:text-red-800') }}">{{ $label }}</button>
</form>
