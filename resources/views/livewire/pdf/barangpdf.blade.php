<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
</head>

<body>
    {{ $title }}

    <table style="border-collapse: collapse; width: 100%;">
        <thead>
            <tr>
                <th style="border: 1px solid black;">#</th>
                <th style="border: 1px solid black;">Nama</th>
                <th style="border: 1px solid black;">Kode S.Sheet</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $dbdata)
            <tr>
                <td style="border: 1px solid black;">{{ $loop->iteration }}</td>
                <td style="border: 1px solid black;">{{ $dbdata->nama }}</td>
                <td style="border: 1px solid black;">{{ $dbdata->kode }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- <img src="{{ asset('storage/' . auth()->user()->image) }}" class="img-fluid rounded" style="object-fit: cover; width: 100%; height: 50%;" alt="..."> -->
</body>

</html>