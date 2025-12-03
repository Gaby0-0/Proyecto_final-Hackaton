@extends('layouts.appe')

@section('title', 'Crear Evento')

@section('content')

<div class="card">

    <div class="header">
        <h2 class="title">Crear evento</h2>
        <button class="btn">Enviar</button>
    </div>

    <div class="grid grid-3">
        <div>
            <label>Sucursal</label>
            <select>
                <option>Hermosillo</option>
            </select>
        </div>

        <div>
            <label>Fecha del evento</label>
            <input type="date">
        </div>

        <div>
            <label>Hora del evento</label>
            <input type="time">
        </div>
    </div>

    <div class="grid grid-2">
        <div>
            <label>Nombre del evento</label>
            <input type="text" placeholder="Escribe el nombre del evento">
        </div>

        <div>
            <label>Cliente</label>
            <input type="text" placeholder="Nombre del cliente">
        </div>
    </div>

    <div>
        <label>Descripción</label>
        <input class="input-large" type="text" placeholder="Describe el evento...">
    </div>

</div>

@endsection
