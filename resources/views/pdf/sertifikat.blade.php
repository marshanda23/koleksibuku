<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Times-Roman', serif; margin: 0; padding: 0; }
        .border { border: 10px double #4b2a6d; padding: 50px; text-align: center; height: 500px; }
        .title { font-size: 50px; color: #4b2a6d; margin-bottom: 20px; }
        .name { font-size: 40px; font-weight: bold; text-decoration: underline; margin: 30px 0; }
    </style>
</head>
<body>
    <div class="border">
        <h2>Sertifikat Penghargaan</h2>
        <div class="title">Certificate of Achievement</div>
        <p>Diberikan kepada:</p>
        <div class="name">{{ $nama }}</div>
        <p>Atas partisipasinya dalam menyelesaikan penelitian ilmiah</p>
        <p>Tanggal: {{ $tanggal }} <br> No: {{ $nomor }}</p>
    </div>
</body>
</html>