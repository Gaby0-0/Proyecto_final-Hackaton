@extends('errors::layout')

@section('code', '403')

@section('icon', 'fas fa-lock')

@section('title', 'Acceso Prohibido')

@section('message')
    No tienes los permisos necesarios para acceder a este recurso. Esta sección está restringida y requiere privilegios especiales que tu cuenta actual no posee.
@endsection

@section('additional-info')
    <strong>¿Por qué veo este mensaje?</strong>
    <ul class="list-disc list-inside mt-2 space-y-1">
        <li>Tu cuenta no tiene los permisos necesarios para esta acción</li>
        <li>Estás intentando acceder a una sección de administrador sin ser administrador</li>
        <li>Tu sesión puede haber expirado o cambiado de permisos</li>
        <li>Estás intentando acceder a recursos de otro usuario</li>
    </ul>
    <p class="mt-3 text-sm">
        <i class="fas fa-lightbulb mr-1"></i>
        Si crees que deberías tener acceso, contacta al administrador del sistema.
    </p>
@endsection

@section('actions')
    <a href="javascript:history.back()" class="inline-flex items-center px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg hover:bg-gray-700 shadow-lg">
        <i class="fas fa-arrow-left mr-2"></i>
        Volver Atrás
    </a>
@endsection
