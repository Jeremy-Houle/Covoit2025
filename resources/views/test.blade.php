<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
@extends('layouts.app')
<body>
    <h1>Hello, World!</h1>
</body>
</html>

@section('title', 'Test')

@section('content')
    <h1>Page de test</h1>
    <p>Contenu spécifique à la page test.</p>
@endsection