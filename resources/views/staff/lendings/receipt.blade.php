<style>
    body { font-family: sans-serif; }
    .header { text-align: center; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; }
    th, td { border: 1px solid #000; padding: 8px; text-align: left; }
    .signature-area { margin-top: 50px; }
    .sign { float: left; width: 45%; text-align: center; }
</style>

<div class="header">
    <h2>Bukti Peminjaman Barang</h2>
    <p>Sistem Inventaris</p>
</div>

<table>
    <tr><th>Peminjam</th><td>{{ $lending->name }}</td></tr>
    <tr><th>Barang</th><td>{{ $lending->item->name }}</td></tr>
    <tr><th>Jumlah</th><td>{{ $lending->qty }}</td></tr>
    <tr><th>Tanggal</th><td>{{ $lending->date_lending }}</td></tr>
</table>

<div class="signature-area">
    <div class="sign">
        <p>Petugas</p>
        <br><br><br>
        <p>( {{ Auth::user()->name }} )</p>
    </div>
    <div class="sign">
        <p>Peminjam</p>
        <br><br><br>
        <p>( {{ $lending->name }} )</p>
    </div>
</div>
