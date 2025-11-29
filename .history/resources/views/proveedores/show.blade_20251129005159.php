@extends('layouts.app')

@section('content')
{{-- Importamos SweetAlert para las confirmaciones --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-7xl mx-auto">
    <!-- Encabezado con acciones principales -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <div class="flex items-center gap-3">
                <h1 class="text-3xl font-bold text-gray-800">{{ $proveedor->razon_social }}</h1>
                @if($proveedor->user->activo)
                    <span class="px-3 py-1 text-sm font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                        Activo
                    </span>
                @else
                    <span class="px-3 py-1 text-sm font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                        Inactivo
                    </span>
                @endif
            </div>
            <p class="text-gray-600 mt-1 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                RUC: <span class="font-mono font-semibold ml-1">{{ $proveedor->ruc }}</span>
            </p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('proveedores.index') }}" class="btn-secondary">Volver</a>
            <a href="{{ route('proveedores.edit', $proveedor) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 font-medium transition-colors">
                Editar Datos
            </a>
            
            {{-- Botón para Activar/Desactivar Acceso --}}
            <form action="{{ route('proveedores.toggle-activo', $proveedor) }}" method="POST">
                @csrf
                <button type="submit" class="px-4 py-2 {{ $proveedor->user->activo ? 'bg-gray-600 hover:bg-gray-700' : 'bg-green-600 hover:bg-green-700' }} text-white rounded-lg font-medium transition-colors"
                    title="{{ $proveedor->user->activo ? 'Bloquear acceso al portal' : 'Permitir acceso al portal' }}">
                    {{ $proveedor->user->activo ? 'Bloquear Acceso' : 'Activar Acceso' }}
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Columna Izquierda: Información Detallada -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Tarjeta de Datos de Contacto -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
                    <h2 class="text-lg font-semibold text-gray-800">Información de Contacto</h2>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm font-medium text-gray-500 block mb-1">Teléfono</label>
                        <p class="text-gray-900 font-medium">{{ $proveedor->telefono ?? 'No registrado' }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-500 block mb-1">Email de Contacto / Acceso</label>
                        <p class="text-gray-900 font-medium">{{ $proveedor->user->email }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500 block mb-1">Dirección</label>
                        <p class="text-gray-900">{{ $proveedor->direccion ?? 'No registrada' }}</p>
                        @if($proveedor->ciudad)
                            <p class="text-sm text-gray-500 mt-1">{{ $proveedor->ciudad }}</p>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label class="text-sm font-medium text-gray-500 block mb-1">Rubros / Categorías</label>
                        @if($proveedor->rubros)
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach(explode(',', $proveedor->rubros) as $rubro)
                                    <span class="px-2 py-1 bg-blue-50 text-blue-700 text-xs rounded border border-blue-100">
                                        {{ trim($rubro) }}
                                    </span>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-400 italic">Sin especificar</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tarjeta de Historial de Solicitudes -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-100">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-800">Últimas Solicitudes de Presupuesto</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Acción</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($proveedor->solicitudesPresupuesto as $solicitud)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                        <a href="{{ route('solicitudes-presupuesto.show', $solicitud) }}" class="hover:underline">
                                            {{ $solicitud->numero }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $solicitud->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $solicitud->estado === 'COTIZADA' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ str_replace('_', ' ', $solicitud->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('solicitudes-presupuesto.show', $solicitud) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                                        No hay historial de solicitudes para este proveedor.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Columna Derecha: Resumen y Zona de Peligro -->
        <div class="space-y-6">
            
            <!-- Resumen Rápido -->
            <div class="bg-white rounded-lg shadow-md p-6 border border-gray-100">
                <h3 class="text-gray-800 font-bold mb-4">Estadísticas</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                        <span class="text-gray-600 text-sm">Total Solicitudes</span>
                        <span class="font-bold text-gray-800">{{ $proveedor->solicitudesPresupuesto->count() }}</span>
                    </div>
                    <!-- Aquí podrías agregar más estadísticas futuras como "Total Comprado" -->
                </div>
            </div>

            <!-- Zona de Peligro (Eliminar) -->
            <div class="bg-red-50 rounded-lg shadow-md p-6 border border-red-100">
                <h3 class="text-red-800 font-bold mb-2">Zona de Peligro</h3>
                <p class="text-red-600 text-sm mb-4">
                    Eliminar este proveedor borrará también su usuario de acceso y todo su historial de datos. Esta acción no se puede deshacer.
                </p>
                
                <form action="{{ route('proveedores.destroy', $proveedor) }}" method="POST" id="formEliminarProveedor">
                    @csrf
                    @method('DELETE')
                    <button type="button" onclick="confirmarEliminacion()" class="w-full bg-white border border-red-300 text-red-600 hover:bg-red-600 hover:text-white py-2 rounded-lg font-medium transition-colors">
                        Eliminar Proveedor
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
    function confirmarEliminacion() {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se eliminará el proveedor, su usuario y todo su historial. ¡No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar definitivamente',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('formEliminarProveedor').submit();
            }
        })
    }
</script>
@endsection