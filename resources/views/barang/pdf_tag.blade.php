<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label TnJ 108</title>
    <style>
        @page {
            size: 210mm 297mm;
            margin: 10mm 8mm 5mm 8mm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
        }

        table {
            table-layout: fixed;
            border-collapse: separate;
            border-spacing: 2mm 1.5mm;
            margin: 0;
            width: auto;
        }

        td.label-box, td.label-blank {
            width: 38mm;
            height: 18mm;
            border: 0.1pt solid #eee;
            text-align: center;
            vertical-align: middle;
            padding: 0.8mm;
            overflow: hidden;
            box-sizing: border-box;
        }

        .nama {
            font-size: 6pt;
            font-weight: bold;
            display: block;
            white-space: nowrap;
            overflow: hidden;
            line-height: 1.2;
        }

        .harga {
            font-size: 8pt;
            font-weight: 900;
            color: #000;
            display: block;
            margin-top: 1px;
            line-height: 1;
        }

        .id {
            font-size: 5.5pt;
            color: #555;
            display: block;
            margin-top: 1px;
            line-height: 1;
        }

        .barcode-wrap {
            display: block;
            text-align: center;
            margin: 1px auto 0 auto;
            line-height: 0;
        }

        .barcode-wrap img {
            width: 34mm;
            height: 6mm;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<?php
    $all_items = [];

    for ($i = 0; $i < $blank_spaces; $i++) {
        $all_items[] = ['type' => 'blank'];
    }

    foreach ($barang_terpilih as $item) {
        $all_items[] = ['type' => 'item', 'data' => $item];
    }

    $chunks = array_chunk($all_items, 5);
?>

<table>
    @foreach($chunks as $row)
    <tr>
        @foreach($row as $cell)
            @if($cell['type'] === 'blank')
                <td class="label-blank"></td>
            @else
                <td class="label-box">
                    <span class="nama">{{ strtoupper(substr($cell['data']->nama, 0, 22)) }}</span>
                    <span class="harga">Rp {{ number_format($cell['data']->harga, 0, ',', '.') }}</span>

                    <span class="barcode-wrap">
                        <img src="{{ $barcodes[$cell['data']->id_barang] }}"
                             alt="{{ $cell['data']->id_barang }}">
                    </span>

                    <span class="id">{{ $cell['data']->id_barang }}</span>
                </td>
            @endif
        @endforeach

        @for($i = count($row); $i < 5; $i++)
            <td class="label-blank"></td>
        @endfor
    </tr>
    @endforeach
</table>

</body>
</html>