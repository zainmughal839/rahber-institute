<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Multiple Challan Print</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
        }

        
    </style>
</head>
<body>

@foreach($challans as $challan)
    <div class="page">
        @include('challans.print', ['challan' => $challan])
    </div>
@endforeach

<script>
    window.onload = () => window.print();
</script>

</body>
</html>
