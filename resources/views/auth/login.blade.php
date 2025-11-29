<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Ankhor ERP</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Animated background */
        .animated-bg {
            background: linear-gradient(-45deg, #1e3a8a, #3b82f6, #1e40af, #2563eb);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        /* Floating shapes */
        .shape {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 20s infinite;
        }

        .shape:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 20%;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 120px;
            height: 120px;
            top: 60%;
            left: 80%;
            animation-delay: 2s;
        }

        .shape:nth-child(3) {
            width: 60px;
            height: 60px;
            top: 80%;
            left: 10%;
            animation-delay: 4s;
        }

        .shape:nth-child(4) {
            width: 100px;
            height: 100px;
            top: 30%;
            left: 70%;
            animation-delay: 6s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) translateX(0);
            }
            25% {
                transform: translateY(-30px) translateX(30px);
            }
            50% {
                transform: translateY(-60px) translateX(-30px);
            }
            75% {
                transform: translateY(-30px) translateX(60px);
            }
        }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <div class="flex h-full">
        <!-- Left Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
            <div class="w-full max-w-md">
                <!-- Logo and Brand -->
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center mb-4">
                        <span class="text-6xl">⚓</span>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">Ankhor</h1>
                    <p class="text-gray-600">Sistema de Gestión Empresarial</p>
                </div>

                <!-- Login Form -->
                <div class="bg-white rounded-2xl shadow-xl p-8 border-2 border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Iniciar Sesión</h2>

                    @if ($errors->any())
                        <div class="mb-4 bg-red-50 border-2 border-red-200 text-red-700 px-4 py-3 rounded-lg">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Usuario / Email
                            </label>
                            <input
                                type="text"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                                placeholder="Ingresa tu usuario o email"
                                required
                                autofocus
                            >
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Contraseña
                            </label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-blue-500 transition"
                                placeholder="Ingresa tu contraseña"
                                required
                            >
                        </div>

                        <!-- Remember Me -->
                        <div class="flex items-center justify-between mb-6">
                            <label class="flex items-center">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                >
                                <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold py-3 rounded-lg hover:from-blue-700 hover:to-blue-800 transition duration-200 shadow-lg"
                        >
                            Iniciar Sesión
                        </button>
                    </form>

                    <!-- Register Link -->
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-600">
                            ¿No tienes una cuenta?
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                Regístrate aquí
                            </a>
                        </p>
                    </div>
                </div>

                <!-- Footer Note -->
                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>Credenciales por defecto: admin123 / admin123</p>
                </div>
            </div>
        </div>

        <!-- Right Side - Animated Background -->
        <div class="hidden lg:flex lg:w-1/2 animated-bg relative items-center justify-center overflow-hidden">
            <!-- Floating Shapes -->
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>
            <div class="shape"></div>

            <!-- Content -->
            <div class="relative z-10 text-center text-white px-12">
                <h2 class="text-5xl font-bold mb-6">Bienvenido a Ankhor</h2>
                <p class="text-xl text-blue-100 mb-8">
                    Gestiona tu empresa de manera eficiente con nuestro sistema integral de ERP
                </p>
                <div class="grid grid-cols-2 gap-6 mt-12">
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <div class="text-4xl mb-3">📦</div>
                        <h3 class="font-semibold text-lg mb-2">Inventario</h3>
                        <p class="text-sm text-blue-100">Control total de productos</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <div class="text-4xl mb-3">💼</div>
                        <h3 class="font-semibold text-lg mb-2">Presupuestos</h3>
                        <p class="text-sm text-blue-100">Gestión de compra y venta</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <div class="text-4xl mb-3">📋</div>
                        <h3 class="font-semibold text-lg mb-2">Notas de Remisión</h3>
                        <p class="text-sm text-blue-100">Seguimiento de entregas</p>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 border border-white/20">
                        <div class="text-4xl mb-3">📊</div>
                        <h3 class="font-semibold text-lg mb-2">Reportes</h3>
                        <p class="text-sm text-blue-100">Análisis en tiempo real</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
