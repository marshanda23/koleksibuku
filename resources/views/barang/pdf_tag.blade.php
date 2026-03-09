<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label TnJ 108</title>
    <style>
        @page {
            size: portrait;
           margin: 5mm 2mm; 
        }
        
        body { font-family: 'Helvetica', sans-serif; margin: 0; padding: 0; }

        table {
            table-layout: fixed;
            border-collapse: separate;
         border-spacing: 2mm 1mm; 
        margin: 2mm;
        margin-left: 0; 
            margin-top: 0;
            width: auto;
    }

        td.label-box, td.label-blank {
            width: 38mm;
        height: 19mm;
        border: 0.1pt solid #eee;
        text-align: center;
        vertical-align: middle;
        padding: 1mm;
        overflow: hidden;
        box-sizing: border-box;
        }

        .nama {
            font-size: 9pt;
            font-weight: bold;
            display: block;
            margin-bottom: 1px;
            white-space: nowrap;
            overflow: hidden;
        }

        .harga {
            font-size: 10pt;
            font-weight: 900;
            color: #000;
            display: block;
            margin-top: 3px;
        }

        .id {
            font-size: 7pt;
            color: #777;
            display: block;
            margin-top: 8px;
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
                    <span class="nama">{{ strtoupper(substr($cell['data']->nama, 0, 18)) }}</span>
                    <span class="harga">Rp {{ number_format($cell['data']->harga, 0, ',', '.') }}</span>
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