@extends('layouts.app')

@section('content')
<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-account-edit"></i>
        </span>
        <span class="text-muted" style="font-size:0.8rem;">Customer /</span> Edit Customer
    </h3>
</div>

<div class="row">
    <div class="col-md-8 offset-md-2 grid-margin stretch-card">
        <div class="card shadow-sm border-0" style="border-radius:15px;">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0 text-primary">
                        <i class="mdi mdi-pencil me-2"></i>Edit Data Customer
                    </h4>
                    <a href="{{ route('customer.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="mdi mdi-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <div class="text-center mb-4">
                    <div style="position:relative; display:inline-block; cursor:pointer;"
                         onclick="document.getElementById('inputFoto').click()">
                        <img id="fotoPreview"
                             src="{{ $customer->foto_blob
                                    ?? ($customer->foto_path
                                        ? Storage::url($customer->foto_path)
                                        : 'https://ui-avatars.com/api/?name='.urlencode($customer->nama).'&background=b66dff&color=fff&size=120') }}"
                             style="width:110px;height:110px;object-fit:cover;border-radius:50%;border:3px solid #b66dff;"
                             title="Klik untuk ganti foto">
                        <span style="position:absolute;bottom:4px;right:4px;background:#b66dff;border-radius:50%;
                                     width:30px;height:30px;display:flex;align-items:center;justify-content:center;">
                            <i class="mdi mdi-camera text-white" style="font-size:15px;"></i>
                        </span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Klik foto untuk mengganti</small>
                        @if($customer->foto_blob)
                            <span class="badge badge-gradient-warning ms-2">BLOB</span>
                        @elseif($customer->foto_path)
                            <span class="badge badge-gradient-success ms-2">FILE</span>
                        @endif
                    </div>
                    <input type="file" id="inputFoto" accept="image/*" class="d-none">
                    <input type="hidden" name="foto_baru" id="fotoBaru">
                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('customer.update', $customer->id) }}" id="editForm">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="foto_baru" id="fotoBaruInput">

                    {{-- NAMA --}}
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Nama <span class="text-danger">*</span></label>
                        <input type="text" name="nama"
                               class="form-control @error('nama') is-invalid @enderror"
                               value="{{ old('nama', $customer->nama) }}"
                               placeholder="Nama lengkap customer" required>
                        @error('nama')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- ALAMAT --}}
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <textarea name="alamat" rows="3"
                                  class="form-control @error('alamat') is-invalid @enderror"
                                  placeholder="Alamat lengkap">{{ old('alamat', $customer->alamat) }}</textarea>
                        @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- PROVINSI --}}
                    <div class="form-group mb-3">
                        <label class="form-label fw-bold">Provinsi</label>
                        <select name="provinsi" id="provinsi" class="form-control">
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov->id }}"
                                    {{ old('provinsi', $customer->provinsi) == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- KOTA & KECAMATAN --}}
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kota / Kabupaten</label>
                            <select name="kota" id="kota" class="form-control">
                                <option value="">-- Pilih Kota --</option>
                                @foreach($regencies as $reg)
                                    <option value="{{ $reg->id }}"
                                        {{ old('kota', $customer->kota) == $reg->id ? 'selected' : '' }}>
                                        {{ $reg->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kecamatan</label>
                            <select name="kecamatan" id="kecamatan" class="form-control">
                                <option value="">-- Pilih Kecamatan --</option>
                                @foreach($districts as $dis)
                                    <option value="{{ $dis->id }}"
                                        {{ old('kecamatan', $customer->kecamatan) == $dis->id ? 'selected' : '' }}>
                                        {{ $dis->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- KODEPOS --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kodepos Kelurahan</label>
                            <input type="text" name="kodepos_kelurahan" class="form-control"
                                   value="{{ old('kodepos_kelurahan', $customer->kodepos_kelurahan) }}"
                                   placeholder="Kodepos kelurahan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Kodepos</label>
                            <input type="text" name="kodepos" class="form-control"
                                   value="{{ old('kodepos', $customer->kodepos) }}"
                                   placeholder="Kodepos">
                        </div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-gradient-primary p-3" id="submitBtn">
                            <span id="btnNormal" style="display:flex;align-items:center;justify-content:center;gap:8px;">
                                <i class="mdi mdi-content-save"></i> Simpan Perubahan
                            </span>
                            <span id="btnLoading" style="display:none;align-items:center;justify-content:center;gap:10px;">
                                <svg id="spiderSpinner" width="20" height="20" viewBox="0 0 50 50"
                                     style="animation:spinnerRotate 1s linear infinite;">
                                    <circle cx="25" cy="25" r="20" fill="none"
                                            stroke="rgba(255,255,255,0.3)" stroke-width="5"/>
                                    <path d="M25 5 a20 20 0 0 1 20 20" fill="none"
                                          stroke="white" stroke-width="5" stroke-linecap="round"/>
                                </svg>
                                Menyimpan...
                            </span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<style>
    @keyframes spinnerRotate {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }
    #spiderSpinner {
        transform-origin: center center;
    }
</style>
@endpush

@push('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('inputFoto').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function (ev) {
            document.getElementById('fotoPreview').src = ev.target.result;
            document.getElementById('fotoBaruInput').value = ev.target.result;
        };
        reader.readAsDataURL(file);
    });

    document.getElementById('provinsi').addEventListener('change', function () {
        const provId = this.value;
        const kotaSel = document.getElementById('kota');
        const kecSel  = document.getElementById('kecamatan');

        kotaSel.innerHTML = '<option value="">-- Pilih Kota --</option>';
        kecSel.innerHTML  = '<option value="">-- Pilih Kecamatan --</option>';

        if (!provId) return;

        fetch(`/api/regencies/${provId}`)
            .then(r => r.json())
            .then(data => {
                data.forEach(item => {
                    kotaSel.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });
            });
    });

    document.getElementById('kota').addEventListener('change', function () {
        const kotaId = this.value;
        const kecSel = document.getElementById('kecamatan');

        kecSel.innerHTML = '<option value="">-- Pilih Kecamatan --</option>';

        if (!kotaId) return;

        fetch(`/api/districts/${kotaId}`)
            .then(r => r.json())
            .then(data => {
                data.forEach(item => {
                    kecSel.innerHTML += `<option value="${item.id}">${item.name}</option>`;
                });
            });
    });

    document.getElementById('editForm').addEventListener('submit', function () {
        const btn       = document.getElementById('submitBtn');
        const btnNormal  = document.getElementById('btnNormal');
        const btnLoading = document.getElementById('btnLoading');

        btn.disabled = true;
        btnNormal.style.display  = 'none';
        btnLoading.style.display = 'flex';
    });

});
</script>
@endpush