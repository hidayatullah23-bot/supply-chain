<!DOCTYPE html>
<html>
<head>
    <title>Edit Negara</title>

    <style>
        body{
            font-family: Arial;
            background:#f4f4f4;
            margin:40px;
        }

        .container{
            width:600px;
            margin:auto;
            background:white;
            padding:20px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,.2);
        }

        input{
            width:100%;
            padding:10px;
            margin-bottom:15px;
            box-sizing:border-box;
        }

        button{
            padding:10px 20px;
            background:#e67e22;
            color:white;
            border:none;
            cursor:pointer;
        }

        a{
            text-decoration:none;
        }
    </style>

</head>
<body>

<div class="container">

<h2>Edit Negara</h2>

@if ($errors->any())

<div style="color:red">

<ul>

@foreach($errors->all() as $error)

<li>{{ $error }}</li>

@endforeach

</ul>

</div>

@endif

<form action="{{ route('countries.update', $country->id) }}" method="POST">

    @csrf
    @method('PUT')

    <label>Nama Negara</label>
    <input type="text" name="country_name" value="{{ $country->country_name }}"> <!-- SUDAH DIPERBAIKI -->

    <label>Kode Negara</label>
    <input type="text" name="country_code" value="{{ $country->country_code }}"> <!-- SUDAH DIPERBAIKI -->

    <label>Ibu Kota</label>
    <input type="text" name="capital" value="{{ $country->capital }}">

    <label>Mata Uang</label>
    <input type="text" name="currency" value="{{ $country->currency }}">

    <label>Region</label>
    <input type="text" name="region" value="{{ $country->region }}">

    <label>Populasi</label>
    <input type="number" name="population" value="{{ $country->population }}">

    <button type="submit">
        Simpan Perubahan
    </button>

    <a href="{{ route('countries.index') }}">
        Kembali
    </a>

</form>

</div>

</body>
</html>