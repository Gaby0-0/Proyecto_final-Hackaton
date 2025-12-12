@extends('errors::layout')

@section('code', '404')

@section('icon', 'fas fa-search')

@section('title', 'Página No Encontrada')

@section('message')
    Lo sentimos, la página que estás buscando no existe o ha sido movida. Es posible que hayas seguido un enlace roto o que la URL haya sido ingresada incorrectamente.
@endsection

@section('additional-info')
    <strong>Posibles causas:</strong>
    <ul class="list-disc list-inside mt-2 space-y-1">
        <li>La URL fue escrita incorrectamente</li>
        <li>El enlace que seguiste está desactualizado</li>
        <li>La página fue eliminada o movida a otra ubicación</li>
        <li>No tienes permisos para acceder a este recurso</li>
    </ul>
@endsection

@section('actions')
    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver Atrás
    </a>
@endsection
