@extends('errors::layout')

@section('code', '401')

@section('icon', 'fas fa-user-lock')

@section('title', 'No Autorizado')

@section('message')
    Necesitas iniciar sesión para acceder a este recurso. La página que intentas ver requiere autenticación y actualmente no has iniciado sesión en el sistema.
@endsection

@section('additional-info')
    <strong>¿Qué necesitas hacer?</strong>
    <ul class="list-disc list-inside mt-2 space-y-1">
        <li>Inicia sesión con tus credenciales para acceder al contenido</li>
        <li>Si no tienes una cuenta, regístrate primero</li>
        <li>Verifica que tus credenciales sean correctas</li>
        <li>Tu sesión puede haber expirado, intenta iniciar sesión nuevamente</li>
    </ul>
    <p class="mt-3 text-sm">
        <i class="fas fa-shield-alt mr-1"></i>
        Esta página está protegida para mantener la seguridad de tu información.
    </p>
@endsection

@section('actions')
    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver Atrás
    </a>
@endsection
