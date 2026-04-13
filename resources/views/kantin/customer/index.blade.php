@extends('layouts.app')

@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-food"></i>
        </span>
        Kantin Order
    </h3>
</div>

<div class="row">

    <div class="col-md-8">
        <div class="card shadow-sm border-0" style="border-radius:12px">
            <div class="card-body">

                <h4 class="text-primary mb-3">
                    <i class="mdi mdi-store me-2"></i>Pilih Menu
                </h4>   
                <div class="mb-4">
                    <label class="fw-bold">Pilih Vendor</label>
                    <select id="vendorSelect" class="form-control border-primary" onchange="loadMenu()">
                        <option value="">-- Pilih Vendor --</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->idvendor }}">{{ $v->nama_vendor }}</option>
                        @endforeach
                    </select>
                </div>

                <div id="menuList">
                    <p class="text-muted text-center py-4">
                        <i class="mdi mdi-arrow-up-circle mdi-36px text-primary d-block mb-2"></i>
                        Pilih vendor untuk melihat menu
                    </p>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm border-0 sticky-top" style="border-radius:12px; top:80px">
            <div class="card-body">

                <h4 class="text-primary mb-3">
                    <i class="mdi mdi-cart me-2"></i>Keranjang
                </h4>

                <div id="cartList">
                    <p class="text-muted text-center py-3">Keranjang kosong</p>
                </div>

                <hr>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <strong>Total:</strong>
                    <span class="text-success fw-bold fs-5" id="totalHarga">Rp 0</span>
                </div>

                <div class="mb-3">
                    <label class="fw-bold">Metode Bayar</label>
                    <div class="d-flex gap-2 mt-1">
                        <div class="form-check border rounded p-2 flex-fill text-center"
                             style="cursor:pointer" onclick="pilihMetode('virtual_account', this)">
                            <input class="form-check-input d-none" type="radio"
                                   name="metodeBayar" value="virtual_account" checked>
                            <i class="mdi mdi-bank text-primary d-block" style="font-size:1.5rem"></i>
                            <small class="fw-bold">Virtual Account</small>
                        </div>
                        <div class="form-check border rounded p-2 flex-fill text-center"
                             style="cursor:pointer" onclick="pilihMetode('qris', this)">
                            <input class="form-check-input d-none" type="radio"
                                   name="metodeBayar" value="qris">
                            <i class="mdi mdi-qrcode text-primary d-block" style="font-size:1.5rem"></i>
                            <small class="fw-bold">QRIS</small>
                        </div>
                    </div>
                </div>

                <div class="d-grid mt-3">
                    <button class="btn btn-gradient-success btn-lg" onclick="bayar()" id="btnBayar">
                        <i class="mdi mdi-cash me-1"></i>
                        <span id="textBayar">Bayar Sekarang</span>
                        <span id="spinnerBayar" class="spinner-border spinner-border-sm d-none ms-1"></span>
                    </button>
                </div>

            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modalSukses" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center border-0" style="border-radius:16px">
            <div class="modal-body py-5 px-4">
                <div style="font-size:4rem">✅</div>
                <h4 class="text-success fw-bold mt-2">Pembayaran Berhasil!</h4>

                <p class="text-muted mb-3">Pesanan Anda telah diterima</p>
                <div class="alert alert-light text-start">
                    <table class="table table-sm mb-0">
                        <tr>
                            <td class="text-muted">Nama</td>
                            <td>:</td>
                            <td><strong id="sukses_nama"></strong></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Kode Pesanan</td>
                            <td>:</td>
                            <td><span id="sukses_kode" class="badge badge-primary"></span></td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total</td>
                            <td>:</td>
                            <td><strong id="sukses_total" class="text-success"></strong></td>
                        </tr>
                    </table>
                </div>

                <div class="mt-3 mb-3">
                    <p class="text-muted small mb-2">
                        <i class="mdi mdi-qrcode me-1"></i>
                        Tunjukkan QR Code ini ke kasir
                    </p>
                    <img id="qrCodeImg"
                         src=""
                         alt="QR Code Pesanan"
                         style="width:200px;height:200px;border:1px solid #eee;border-radius:8px;background:#f8f8f8">
                </div>


                <button class="btn btn-gradient-primary w-100 mt-2"
                        data-bs-dismiss="modal" onclick="resetOrder()">
                    Pesan Lagi
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let allMenu       = [];
let cart          = [];
let activePesanan = null;
let metodeBayar   = 'virtual_account';

function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency', currency: 'IDR', maximumFractionDigits: 0
    }).format(angka);
}

function pilihMetode(value, el) {
    metodeBayar = value;
    document.querySelectorAll('.form-check').forEach(e => {
        e.classList.remove('border-primary', 'bg-light');
    });
    el.classList.add('border-primary', 'bg-light');
    el.querySelector('input').checked = true;
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.form-check').classList.add('border-primary', 'bg-light');
});

function loadMenu() {
    const idvendor = document.getElementById('vendorSelect').value;
    if (!idvendor) {
        document.getElementById('menuList').innerHTML =
            '<p class="text-muted text-center py-4">Pilih vendor untuk melihat menu</p>';
        return;
    }

    document.getElementById('menuList').innerHTML =
        '<p class="text-center py-4"><span class="spinner-border text-primary"></span></p>';

    axios.get('/kantin/menu/' + idvendor).then(res => {
        allMenu = res.data;
        renderMenu(allMenu);
    }).catch(() => {
        document.getElementById('menuList').innerHTML =
            '<p class="text-danger text-center">Gagal memuat menu</p>';
    });
}

function renderMenu(menus) {
    const div = document.getElementById('menuList');
    if (!menus.length) {
        div.innerHTML = '<p class="text-muted text-center py-4">Tidak ada menu tersedia</p>';
        return;
    }
    div.innerHTML = '<div class="row gy-3">' + menus.map(m => `
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius:10px; overflow:hidden">
                ${m.path_gambar
                    ? `<img src="/storage/${m.path_gambar}" class="card-img-top"
                            style="height:120px;object-fit:cover">`
                    : `<div class="bg-light d-flex align-items-center justify-content-center"
                            style="height:80px">
                           <i class="mdi mdi-food mdi-36px text-muted"></i>
                       </div>`
                }
                <div class="card-body d-flex justify-content-between align-items-center p-3">
                    <div>
                        <strong>${m.nama_menu}</strong><br>
                        <span class="text-success fw-bold">${formatRupiah(m.harga)}</span>
                    </div>
                    <button class="btn btn-gradient-primary btn-sm rounded-circle"
                            style="width:36px;height:36px;padding:0"
                            onclick="addCart(${m.idmenu}, '${m.nama_menu.replace(/'/g,"\\'")}', ${m.harga})">
                        <i class="mdi mdi-plus"></i>
                    </button>
                </div>
            </div>
        </div>`).join('') + '</div>';
}

function addCart(idmenu, nama, harga) {
    const existing = cart.find(c => c.idmenu === idmenu);
    if (existing) {
        existing.jumlah++;
    } else {
        cart.push({ idmenu, nama_menu: nama, harga, jumlah: 1 });
    }
    renderCart();
}

function ubahJumlah(index, delta) {
    cart[index].jumlah += delta;
    if (cart[index].jumlah <= 0) cart.splice(index, 1);
    renderCart();
}

function renderCart() {
    const div = document.getElementById('cartList');
    if (!cart.length) {
        div.innerHTML = '<p class="text-muted text-center py-3">Keranjang kosong</p>';
    } else {
        div.innerHTML = cart.map((c, i) => `
            <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                <div style="flex:1">
                    <small class="fw-bold d-block">${c.nama_menu}</small>
                    <small class="text-success">${formatRupiah(c.harga)}</small>
                </div>
                <div class="d-flex align-items-center gap-1">
                    <button class="btn btn-outline-secondary btn-sm px-2 py-0 lh-1"
                            onclick="ubahJumlah(${i}, -1)">−</button>
                    <span class="px-2 fw-bold">${c.jumlah}</span>
                    <button class="btn btn-outline-secondary btn-sm px-2 py-0 lh-1"
                            onclick="ubahJumlah(${i}, 1)">+</button>
                </div>
            </div>`).join('');
    }
    const total = cart.reduce((s, c) => s + c.jumlah * c.harga, 0);
    document.getElementById('totalHarga').textContent = formatRupiah(total);
}

function bayar() {
    const idvendor = document.getElementById('vendorSelect').value;
    if (!idvendor) return Swal.fire({ icon: 'warning', title: 'Pilih vendor dulu!' });
    if (!cart.length) return Swal.fire({ icon: 'warning', title: 'Keranjang kosong!' });

    Swal.fire({
        title: 'Konfirmasi Pesanan',
        text: 'Lanjutkan ke pembayaran?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya, Bayar!',
        cancelButtonText: 'Batal'
    }).then(result => {
        if (!result.isConfirmed) return;

        setBayarLoading(true);

        axios.post('/kantin/pesan', {
            idvendor,
            metode_bayar: metodeBayar,
            items: cart.map(c => ({ idmenu: c.idmenu, jumlah: c.jumlah })),
        }).then(res => {
            if (!res.data.success) throw new Error(res.data.error);
            activePesanan = res.data;

            return axios.post('/kantin/payment/token', { idpesanan: res.data.idpesanan });

        }).then(res => {
            setBayarLoading(false);

            snap.pay(res.data.token, {
                onSuccess: function(result) {
                    axios.post('/kantin/payment/update-status', {
                        idpesanan    : activePesanan.idpesanan,
                        kode_pesanan : activePesanan.kode_pesanan,
                        result       : result,
                    }).finally(() => {
                        tampilSukses(activePesanan);
                    });
                },

                onPending: function() {
                    Swal.fire({
                        icon : 'info',
                        title: 'Menunggu Pembayaran',
                        text : 'Kode pesanan: ' + activePesanan.kode_pesanan,
                    });
                },

                onError: function() {
                    Swal.fire({ icon: 'error', title: 'Pembayaran Gagal' });
                },

                onClose: function() {
                    Swal.fire({
                        icon : 'warning',
                        title: 'Belum selesai',
                        text : 'Kode pesanan Anda: ' + activePesanan.kode_pesanan +
                               '. Hubungi kasir jika sudah membayar.',
                    });
                }
            });

        }).catch(err => {
            setBayarLoading(false);
            Swal.fire({ icon: 'error', title: 'Gagal', text: err.response?.data?.error || err.message });
        });
    });
}

function setBayarLoading(loading) {
    document.getElementById('spinnerBayar').classList.toggle('d-none', !loading);
    document.getElementById('textBayar').classList.toggle('d-none', loading);
    document.getElementById('btnBayar').disabled = loading;
}

function tampilSukses(pesanan) {
    document.getElementById('sukses_nama').textContent  = pesanan.nama;
    document.getElementById('sukses_kode').textContent  = pesanan.kode_pesanan;
    document.getElementById('sukses_total').textContent = formatRupiah(pesanan.total);

    document.getElementById('qrCodeImg').src = '/kantin/qr/' + pesanan.idpesanan;

    new bootstrap.Modal(document.getElementById('modalSukses')).show();
}

function resetOrder() {
    cart          = [];
    activePesanan = null;

    document.getElementById('qrCodeImg').src = '';

    renderCart();
}
</script>

<style>
.border-primary { border: 1px solid #b66dff !important; }
.form-check { transition: all .2s; }
</style>

@endsection