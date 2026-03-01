<!DOCTYPE html>
<html>
<head>
    <style>
        body { 
            font-family: 'Times New Roman', Times, serif; 
            padding: 20px 40px; 
            line-height: 1.5; 
            color: #000;
        }
        .kop { 
            border-bottom: 3px solid #000; 
            text-align: center; 
            padding-bottom: 10px; 
            margin-bottom: 20px; 
        }
        .kop h2 { margin: 0; font-size: 20px; text-transform: uppercase; }
        .kop h3 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .kop p { margin: 2px 0; font-size: 12px; }
        
        .nomor-surat { margin-bottom: 20px; }
        .nomor-surat table { width: 100%; }
        
        .isi-surat { text-align: justify; }
        .identitas-acara { margin: 20px 50px; }
        .identitas-acara td { vertical-align: top; }

        .ttd { 
            margin-top: 50px; 
            float: right; 
            text-align: center; 
            width: 250px; 
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>UNIVERSITAS AIRLANGGA</h2>
        <h3>FAKULTAS VOKASI</h3>
        <p>Kampus B Jl. Dharmawangsa Dalam No.38, Surabaya 60286</p>
        <p>Telepon (031) 5033869, 5014606 Fax (031) 5011029</p>
        <p>Laman: https://vokasi.unair.ac.id, Surel: info@vokasi.unair.ac.id</p>
    </div>

    <div class="nomor-surat">
        <table>
            <tr>
                <td width="15%">Nomor</td>
                <td width="2%">:</td>
                <td width="43%">{{ $nomor_surat }}</td>
                <td width="40%" align="right">Surabaya, {{ $tanggal }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>1 (Satu) Berkas</td>
            </tr>
            <tr>
                <td>Hal</td>
                <td>:</td>
                <td><strong>{{ $perihal }}</strong></td>
            </tr>
        </table>
    </div>

    <div class="isi-surat">
        <p>Yth. <strong>{{ $penerima }}</strong><br>
        Marshanda Hadi S<br>
        Surabaya</p>

        <p>Dengan hormat,</p>
        <p>Sehubungan dengan berakhirnya kegiatan <strong>penelitian</strong> bagi mahasiswa semester 6, maka kami bermaksud mengundang Bapak/Ibu/Saudara untuk hadir dalam rapat koordinasi dan evaluasi teknis yang akan dilaksanakan pada:</p>

        <table class="identitas-acara">
            <tr>
                <td width="100">Hari, Tanggal</td>
                <td width="15">:</td>
                <td>Senin, 23 Februari 2026</td>
            </tr>
            <tr>
                <td>Waktu</td>
                <td>:</td>
                <td>09.00 WIB s.d. Selesai</td>
            </tr>
            <tr>
                <td>Tempat</td>
                <td>:</td>
                <td>Ruang Rapat Dekanat, Gedung Fakultas Vokasi Kampus B</td>
            </tr>
            <tr>
                <td>Agenda</td>
                <td>:</td>
                <td>Finalisasi Nilai dan Penyerahan Sertifikat Kelulusan</td>
            </tr>
        </table>

        <p>Mengingat pentingnya agenda tersebut, kami sangat mengharapkan kehadiran Saudara tepat pada waktunya. Apabila berhalangan hadir, mohon dapat menginformasikan kepada panitia penyelenggara terlebih dahulu.</p>
        
        <p>Demikian undangan ini kami sampaikan. Atas perhatian dan kerja sama yang baik, kami ucapkan terima kasih.</p>
    </div>
    <div class="ttd">
        <p>a.n. Dekan,<br>
        Wakil Dekan I,</p>
    </div>
    <div class="clear"></div>
</body>
</html>