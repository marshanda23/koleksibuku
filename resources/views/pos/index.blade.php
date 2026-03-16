@extends('layouts.app')

@section('content')

<div class="page-header">
<h3 class="page-title">
<span class="page-title-icon bg-gradient-primary text-white me-2">
<i class="mdi mdi-cart"></i>
</span>
<span class="text-muted" style="font-size:0.8rem;">Transaksi /</span>
Transaksi Penjualan
</h3>
</div>

<div class="row">
<div class="col-md-10 mx-auto">

<div class="card shadow-sm border-0" style="border-radius:12px">
<div class="card-body">

<h4 class="text-primary mb-4">
<i class="mdi mdi-cart-outline me-2"></i>
Transaksi Penjualan
</h4>

<div class="row gy-3">

<div class="col-md-12">
<label>Kode Barang</label>
<input type="text"
id="kode_barang"
class="form-control border-primary"
placeholder="Scan / Ketik kode barang">
</div>

<div class="col-md-12">
<label>Nama Barang</label>
<input type="text"
id="nama_barang"
class="form-control border-primary"
readonly>
</div>

<div class="col-md-6">
<label>Harga</label>
<input type="text"
id="harga_barang"
class="form-control border-primary"
readonly>
</div>

<div class="col-md-6">
<label>Jumlah</label>
<input type="number"
id="jumlah"
class="form-control border-primary"
min="1">
</div>

<div class="col-md-12 text-end mt-3">

<button id="btnTambah"
class="btn btn-gradient-primary px-4"
disabled>

<span id="textTambah">
<i class="mdi mdi-plus"></i> Tambahkan Barang
</span>

<span id="spinnerTambah"
class="spinner-border spinner-border-sm d-none"></span>

</button>

</div>

</div>

<hr class="my-4">

<div class="table-responsive">

<table class="table table-hover text-center align-middle">

<thead>
<tr>
<th style="width:15%">Kode</th>
<th style="width:30%">Nama</th>
<th style="width:15%">Harga</th>
<th style="width:15%">Jumlah</th>
<th style="width:15%">Subtotal</th>
<th style="width:10%">Aksi</th>
</tr>
</thead>

<tbody id="tableBody"></tbody>

</table>

</div>

<div class="d-flex justify-content-between align-items-center mt-4">

<h4>
Total :
<span class="badge badge-success" id="total">Rp 0</span>
</h4>

<button id="btnBayar"
class="btn btn-gradient-success">

<span id="textBayar">
<i class="mdi mdi-cash"></i> Bayar
</span>

<span id="spinnerBayar"
class="spinner-border spinner-border-sm d-none"></span>

</button>

</div>

</div>
</div>
</div>
</div>


<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

axios.defaults.headers.common['X-CSRF-TOKEN'] =
document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let keranjang = []

function formatRupiah(angka){
return new Intl.NumberFormat('id-ID',{
style:'currency',
currency:'IDR'
}).format(angka)
}

function renderTable(){

let tbody=document.getElementById('tableBody')
tbody.innerHTML=''

keranjang.forEach((item,index)=>{

tbody.innerHTML+=`

<tr>

<td>${item.kode}</td>

<td class="text-start">${item.nama}</td>

<td>${formatRupiah(item.harga)}</td>

<td>

<input type="number"
value="${item.jumlah}"
min="1"
class="form-control text-center"
style="width:80px;margin:auto"
onchange="ubahJumlah(${index},this.value)">

</td>

<td>${formatRupiah(item.subtotal)}</td>

<td>

<button class="btn btn-danger btn-sm"
onclick="hapusItem(${index})">
<i class="mdi mdi-delete"></i>
</button>

</td>

</tr>

`

})

updateTotal()

}

function ubahJumlah(index,val){

val=parseInt(val)

if(val<=0){
Swal.fire({
icon:'warning',
title:'Jumlah harus lebih dari 0'
})
renderTable()
return
}

keranjang[index].jumlah=val
keranjang[index].subtotal=val*keranjang[index].harga

renderTable()

}

function hapusItem(index){

keranjang.splice(index,1)
renderTable()

}

function updateTotal(){

let total=0

keranjang.forEach(item=>{
total+=item.subtotal
})

document.getElementById('total').innerText=formatRupiah(total)

}

document.getElementById('kode_barang')
.addEventListener('keydown',function(e){

if(e.key==='Enter'){

e.preventDefault()

axios.post('/pos/cari-barang',{
kode:this.value
})

.then(res=>{

if(res.data.status==='success'){

let data=res.data.data

document.getElementById('nama_barang').value=data.nama
document.getElementById('harga_barang').value=data.harga
document.getElementById('jumlah').value=1

document.getElementById('btnTambah').disabled=false

}else{

Swal.fire({
icon:'error',
title:'Barang tidak ditemukan'
})

}

})

.catch(()=>{
Swal.fire({
icon:'error',
title:'Gagal mengambil data barang'
})
})

}

})

document.getElementById('btnTambah').onclick=function(){

let kode=document.getElementById('kode_barang').value
let nama=document.getElementById('nama_barang').value
let harga=parseInt(document.getElementById('harga_barang').value)
let jumlah=parseInt(document.getElementById('jumlah').value)

if(jumlah<=0){

Swal.fire({
icon:'warning',
title:'Jumlah harus lebih dari 0'
})

return
}

document.getElementById('spinnerTambah').classList.remove('d-none')
document.getElementById('textTambah').classList.add('d-none')

setTimeout(()=>{

let exist=keranjang.find(x=>x.kode==kode)

if(exist){

exist.jumlah+=jumlah
exist.subtotal=exist.jumlah*exist.harga

}else{

keranjang.push({
kode:kode,
nama:nama,
harga:harga,
jumlah:jumlah,
subtotal:harga*jumlah
})

}

renderTable()

Swal.fire({
icon:'success',
title:'Berhasil',
text:'Barang berhasil ditambahkan',
timer:1200,
showConfirmButton:false
})

document.getElementById('kode_barang').value=''
document.getElementById('nama_barang').value=''
document.getElementById('harga_barang').value=''
document.getElementById('jumlah').value=''

document.getElementById('btnTambah').disabled=true

document.getElementById('spinnerTambah').classList.add('d-none')
document.getElementById('textTambah').classList.remove('d-none')

document.getElementById('kode_barang').focus()

},400)

}

document.getElementById('btnBayar').onclick=function(){

if(keranjang.length==0){

Swal.fire({
icon:'warning',
title:'Keranjang kosong'
})

return
}

Swal.fire({
title:'Proses pembayaran?',
text:'Pastikan data sudah benar',
icon:'question',
showCancelButton:true,
confirmButtonText:'Ya, Bayar',
cancelButtonText:'Batal'
}).then((result)=>{

if(result.isConfirmed){

prosesBayar()

}

})

}

function prosesBayar(){

document.getElementById('spinnerBayar').classList.remove('d-none')
document.getElementById('textBayar').classList.add('d-none')

axios.post('/pos/bayar',{
items:keranjang,
total:keranjang.reduce((a,b)=>a+b.subtotal,0)
})

.then(res=>{

if(res.data.status==='success'){

Swal.fire({
icon:'success',
title:'Pembayaran Berhasil',
text:'Transaksi berhasil disimpan'
}).then(()=>{

keranjang=[]
renderTable()

document.getElementById('kode_barang').value=''
document.getElementById('nama_barang').value=''
document.getElementById('harga_barang').value=''
document.getElementById('jumlah').value=''

document.getElementById('total').innerText='Rp 0'

document.getElementById('btnTambah').disabled=true

document.getElementById('kode_barang').focus()

})

}else{

Swal.fire({
icon:'error',
title:'Transaksi gagal disimpan'
})

}

})

.catch(()=>{

Swal.fire({
icon:'error',
title:'Gagal menyimpan transaksi'
})

})

.finally(()=>{

document.getElementById('spinnerBayar').classList.add('d-none')
document.getElementById('textBayar').classList.remove('d-none')

})

}

</script>

<style>

.border-primary{
border:1px solid #b66dff !important;
}

.table thead th{
border:none !important;
font-weight:bold;
color:#7b4bb7;
}
#btnTambah{
min-width:170px;
}

#btnBayar{
min-width:170px;
}

table td{
vertical-align:middle !important;
}

input[type=number]{
text-align:center;
}

</style>

@endsection