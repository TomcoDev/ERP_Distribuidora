@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Editar Proveedor</h1>
            <p class="text-gray-600 mt-1">Modifica los datos comerciales de {{ $proveedor->razon_social }}</p>
        </div>
        <a href="{{ route('proveedores.index') }}" class="btn-secondary">Volver</a>
    </div>

    <form action="{{ route('proveedores.update', $proveedor) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="form-section">
            <h2 class="form-section-title">Datos del Proveedor</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-group md:col-span-2">
                    <label class="form-label form-label-required">Razón Social</label>
                    <input type="text" name="razon_social" value="{{ old('razon_social', $proveedor->razon_social) }}" required
                           class="form-input" placeholder="Nombre de la empresa">
                    @error('razon_social')<p class="form-error-message">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label form-label-required">RUC</label>
                    <input type="text" name="ruc" value="{{ old('ruc', $proveedor->ruc) }}" required
                           class="form-input" placeholder="Ej: 80012345-6">
                    @error('ruc')<p class="form-error-message">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Teléfono</label>
                    <input type="text" name="telefono" value="{{ old('telefono', $proveedor->telefono) }}"
                           class="form-input" placeholder="Ej: 021 123 456">
                </div>
                <div class="form-group">
                    <label class="form-label">Ciudad</label>
                    <input type="text" name="ciudad" value="{{ old('ciudad', $proveedor->ciudad) }}"
                           class="form-input" placeholder="Ej: Asunción">
                </div>
                <div class="form-group md:col-span-2">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" value="{{ old('direccion', $proveedor->direccion) }}"
                           class="form-input" placeholder="Dirección comercial">
                </div>
                <div class="form-group md:col-span-2">
                    <label class="form-label">Rubros/Productos que maneja</label>
                    <input type="text" name="rubros" value="{{ old('rubros', $proveedor->rubros) }}"
                           placeholder="Ej: Ferretería, Herramientas eléctricas, Pinturas..."
                           class="form-input">
                </div>
            </div>
        </div>

        <div class="form-section bg-gray-50 border border-gray-200">
            <h2 class="form-section-title text-gray-600">Credenciales de Acceso</h2>
            <div class="p-4">
                <p class="text-sm text-gray-600 mb-2">
                    El email de acceso está vinculado a la cuenta de usuario y no se puede editar desde aquí por seguridad.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-group">
                        <label class="form-label">Email de Acceso (Solo lectura)</label>
                        <input type="email" value="{{ $proveedor->user->email }}" disabled
                               class="form-input bg-gray-100 text-gray-500 cursor-not-allowed">
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('proveedores.index') }}" class="btn-secondary">Cancelar</a>
            <button type="submit" class="btn-primary">Actualizar Proveedor</button>
        </div>
    </form>
</div>
@endsection