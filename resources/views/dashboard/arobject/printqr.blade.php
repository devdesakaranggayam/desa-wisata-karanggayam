<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR {{ $arobject->judul }}</title>
    <style>
        body { text-align: center; margin-top: 50px; font-family: sans-serif; }
        img { width: 250px; height: 250px; }
        h3 { margin-bottom: 10px; }
        @media print { body { margin: 0; } }
    </style>
</head>
<body>
    <img src="{{ $qrPng }}" alt="QR Code">

    <script>
        window.onload = () => {
            window.print();
        };
    </script>
</body>
</html>
