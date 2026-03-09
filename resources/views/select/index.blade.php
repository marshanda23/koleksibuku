@extends('layouts.app')

@section('content')

<div class="page-header">
<h3 class="page-title">
<span class="page-title-icon bg-gradient-primary text-white me-2">
<i class="mdi mdi-map-marker"></i>
</span>
<span class="text-muted" style="font-size:0.8rem;">Form /</span>
Select Kota
</h3>
</div>


<div class="row">

{{-- CARD SELECT --}}
<div class="col-md-6 grid-margin stretch-card">
<div class="card shadow-sm border-0">
<div class="card-header bg-white fw-bold text-primary">
Select
</div>

<div class="card-body p-4">

<div class="mb-3">
<label class="fw-bold">Kota</label>
<input type="text" id="kotaInput1" class="form-control custom-input">
</div>

<div class="text-end mb-4">
<button class="btn btn-purple px-4" id="btnTambah1">Tambahkan</button>
</div>

<div class="mb-3">
<label class="fw-bold">Select Kota</label>
<select id="selectKota1" class="form-control custom-input">
<option value="">-- Pilih Kota --</option>
</select>
</div>

<div class="mt-3">
<b>Kota Terpilih :</b>
<span id="hasilKota1" class="text-primary"></span>
</div>

</div>
</div>
</div>


{{-- CARD SELECT2 --}}
<div class="col-md-6 grid-margin stretch-card">
<div class="card shadow-sm border-0">
<div class="card-header bg-white fw-bold text-primary">
Select 2
</div>

<div class="card-body p-4">

<div class="mb-3">
<label class="fw-bold">Kota</label>
<input type="text" id="kotaInput2" class="form-control custom-input">
</div>

<div class="text-end mb-4">
<button class="btn btn-purple px-4" id="btnTambah2">Tambahkan</button>
</div>

<div class="mb-3">
<label class="fw-bold">Select Kota</label>
<select id="selectKota2" class="form-control select2-purple">
<option value="">-- Pilih Kota --</option>
</select>
</div>

<div class="mt-3">
<b>Kota Terpilih :</b>
<span id="hasilKota2" class="text-primary"></span>
</div>

</div>
</div>
</div>

</div>


<style>

.custom-input{
border:1px solid #b66dff !important;
border-radius:4px;
padding:0.6rem 1rem;
}

.btn-purple{
background:#b66dff;
border-color:#b66dff;
color:white;
}

.btn-purple:hover{
background:#9f4dff;
border-color:#9f4dff;
color:white;
}

.select2-container--default .select2-selection--single{
border:1px solid #b66dff;
height:38px;
padding:4px;
}

.select2-container--default .select2-results__option--highlighted{
background:#b66dff;
color:white;
}

.btn-loading{
background:#bfbfbf !important;
border-color:#bfbfbf !important;
cursor:not-allowed;
}

</style>

@endsection


@push('script')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>

$(document).ready(function(){

$('#selectKota2').select2({
width:'100%'
});


/* CARD SELECT */

$('#btnTambah1').click(function(){

let btn = $(this);
let kota = $('#kotaInput1').val();

if(kota === '') return;

btn.addClass('btn-loading').prop('disabled', true);

setTimeout(() => {

$('#selectKota1').append(
`<option value="${kota}">${kota}</option>`
);

$('#kotaInput1').val('');

btn.removeClass('btn-loading').prop('disabled', false);

},400);

});


$('#selectKota1').change(function(){

$('#hasilKota1').text($(this).val());

});


/* CARD SELECT2 */

$('#btnTambah2').click(function(){

let btn = $(this);
let kota = $('#kotaInput2').val();

if(kota === '') return;

btn.addClass('btn-loading').prop('disabled', true);

setTimeout(() => {

$('#selectKota2').append(
`<option value="${kota}">${kota}</option>`
).trigger('change');

$('#kotaInput2').val('');

btn.removeClass('btn-loading').prop('disabled', false);

},400);

});


$('#selectKota2').change(function(){

$('#hasilKota2').text($(this).val());

});

});

</script>

@endpush