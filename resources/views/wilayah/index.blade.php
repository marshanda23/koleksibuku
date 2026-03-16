@extends('layouts.app')

@section('content')

<div class="page-header">
<h3 class="page-title">

<span class="page-title-icon bg-gradient-primary text-white me-2">
<i class="mdi mdi-map-marker"></i>
</span>

<span class="text-muted" style="font-size:0.8rem;">
Form /
</span>Select Wilayah</h3>
</div>
<div class="row">

<div class="col-md-12 grid-margin stretch-card">

<div class="card shadow-sm border-0">

<div class="card-header bg-white fw-bold text-primary">
AJAX JQuery
</div>

<div class="card-body p-4">

<div class="mb-3">
<label class="fw-bold">Provinsi</label>

<select id="provinsi" class="form-control custom-input">

<option value="0">Pilih Provinsi</option>

@foreach($provinces as $p)

<option value="{{ $p->id }}">
{{ $p->name }}
</option>

@endforeach

</select>
</div>

<div class="mb-3">
<label class="fw-bold">Kota</label>

<select id="kota" class="form-control custom-input">

<option value="0">Pilih Kota</option>

</select>
</div>

<div class="mb-3">
<label class="fw-bold">Kecamatan</label>

<select id="kecamatan" class="form-control custom-input">

<option value="0">Pilih Kecamatan</option>

</select>
</div>

<div class="mb-3">
<label class="fw-bold">Kelurahan</label>

<select id="kelurahan" class="form-control custom-input">

<option value="0">Pilih Kelurahan</option>

</select>
</div>

</div>
</div>
</div>

<div class="col-md-12 grid-margin stretch-card">

<div class="card shadow-sm border-0">

<div class="card-header bg-white fw-bold text-primary">
Axios
</div>

<div class="card-body p-4">

<div class="mb-3">
<label class="fw-bold">Provinsi</label>

<select id="provinsi2" class="form-control custom-input">

<option value="0">Pilih Provinsi</option>

@foreach($provinces as $p)

<option value="{{ $p->id }}">
{{ $p->name }}
</option>

@endforeach

</select>
</div>

<div class="mb-3">
<label class="fw-bold">Kota</label>

<select id="kota2" class="form-control custom-input">

<option value="0">Pilih Kota</option>

</select>
</div>

<div class="mb-3">
<label class="fw-bold">Kecamatan</label>

<select id="kecamatan2" class="form-control custom-input">

<option value="0">Pilih Kecamatan</option>

</select>
</div>

<div class="mb-3">
<label class="fw-bold">Kelurahan</label>

<select id="kelurahan2" class="form-control custom-input">

<option value="0">Pilih Kelurahan</option>

</select>
</div>

</div>
</div>
</div>

</div>

<style>

.custom-input{
border:1px solid #b66dff !important;
border-radius:4px;
padding:0.9rem 1rem;
height:50px;
width:100%;
appearance:none;
-webkit-appearance:none;
-moz-appearance:none;
background-image:url("data:image/svg+xml;utf8,<svg fill='black' height='24' viewBox='0 0 24 24' width='24' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>");
background-repeat:no-repeat;
background-position:right 10px center;
background-size:18px;

}

.custom-input:focus{
border-color:#9f4dff;
box-shadow:0 0 0 0.1rem rgba(182,109,255,0.3);
}

.card-header{
border-bottom:1px solid #6b3b68;
}

</style>

@endsection

@push('script')

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>

$(document).ready(function(){

/* AJAX JQUERY*/

$('#provinsi').change(function(){

let id = $(this).val()

$('#kecamatan').html('<option value="0">Pilih Kecamatan</option>')
$('#kelurahan').html('<option value="0">Pilih Kelurahan</option>')

/* REQUEST DATA */
$.get('/wilayah/kota/'+id,function(data){

let html = '<option value="0">Pilih Kota</option>'

data.forEach(function(item){

html += `<option value="${item.id}">${item.name}</option>`

})

$('#kota').html(html)

})

})

$('#kota').change(function(){

let id = $(this).val()

$('#kelurahan').html('<option value="0">Pilih Kelurahan</option>')

$.get('/wilayah/kecamatan/'+id,function(data){

let html = '<option value="0">Pilih Kecamatan</option>'

data.forEach(function(item){

html += `<option value="${item.id}">${item.name}</option>`

})

$('#kecamatan').html(html)

})

})

$('#kecamatan').change(function(){

let id = $(this).val()

$.get('/wilayah/kelurahan/'+id,function(data){

let html = '<option value="0">Pilih Kelurahan</option>'

data.forEach(function(item){

html += `<option value="${item.id}">${item.name}</option>`

})

$('#kelurahan').html(html)

})

})



/* AXIOS */

document.getElementById('provinsi2').addEventListener('change',function(){

let id = this.value

axios.get('/wilayah/kota/'+id)
.then(res => {

let html = '<option value="0">Pilih Kota</option>'

res.data.forEach(item => {

html += `<option value="${item.id}">${item.name}</option>`

})

document.getElementById('kota2').innerHTML = html

})

})

document.getElementById('kota2').addEventListener('change',function(){

let id = this.value

axios.get('/wilayah/kecamatan/'+id)
.then(res => {

let html = '<option value="0">Pilih Kecamatan</option>'

res.data.forEach(item => {

html += `<option value="${item.id}">${item.name}</option>`

})

document.getElementById('kecamatan2').innerHTML = html

})

})

document.getElementById('kecamatan2').addEventListener('change',function(){

let id = this.value

axios.get('/wilayah/kelurahan/'+id)
.then(res => {

let html = '<option value="0">Pilih Kelurahan</option>'

res.data.forEach(item => {

html += `<option value="${item.id}">${item.name}</option>`

})

document.getElementById('kelurahan2').innerHTML = html

})

})

})

</script>

@endpush