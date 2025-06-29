<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Warung Kita</title>
    {{-- Memuat aset CSS dari Vite, termasuk Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Font Awesome dari CDN --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Dihapus: CSS untuk .auth-container, .form-card, .form-card.flipped, .form-side, .form-back */
        /* CSS yang tidak lagi diperlukan untuk animasi flip telah dihapus */

        .floating-shapes {
            animation: float 8s ease-in-out infinite;
        }

        .floating-shapes:nth-child(2) {
            animation-delay: -2s;
        }

        .floating-shapes:nth-child(3) {
            animation-delay: -4s;
        }

        .floating-shapes:nth-child(4) {
            animation-delay: -6s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px) translateX(0px) rotate(0deg);
                opacity: 0.7;
            }
            25% {
                transform: translateY(-20px) translateX(10px) rotate(90deg);
                opacity: 0.8;
            }
            50% {
                transform: translateY(-10px) translateX(-10px) rotate(180deg);
                opacity: 0.6;
            }
            75% {
                transform: translateY(-30px) translateX(5px) rotate(270deg);
                opacity: 0.9;
            }
        }

        .particle {
            animation: particleFloat 6s linear infinite;
        }

        @keyframes particleFloat {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100vh) rotate(360deg);
                opacity: 0;
            }
        }

        /* OVERRIDE DAN KUSTOMISASI UNTUK INPUT GROUP (Tetap digunakan) */
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        /* ... (CSS lain yang tidak berhubungan dengan flip tetap sama) ... */
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .gradient-border {
            background: linear-gradient(45deg, #8b5cf6, #06b6d4, #10b981, #f59e0b);
            padding: 2px;
            border-radius: 16px;
        }

        .gradient-border-inner {
            background: rgba(17, 24, 39, 0.95);
            border-radius: 14px;
        }
        
        .social-btn {
            transition: all 0.3s ease;
            transform: translateY(0);
        }

        .social-btn:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .ripple-effect {
            position: relative;
            overflow: hidden;
        }

        .ripple-effect:before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .ripple-effect:active:before {
            width: 300px;
            height: 300px;
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(139, 92, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0); }
        }

        .slide-in {
            animation: slideIn 0.6s ease-out forwards;
        }

        @keyframes slideIn {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }

        .glow-effect {
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.3);
        }
        
        .loading {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        /* Style untuk pesan sukses/error Laravel dari session */
        .session-status {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
            color: white;
            animation: slideIn 0.6s ease-out forwards;
        }
        .session-status.success {
            background-color: #10b981; /* green-600 */
        }
        .session-status.error {
            background-color: #ef4444; /* red-600 */
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-violet-900 min-h-screen overflow-hidden relative">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="floating-shapes absolute top-20 left-10 w-20 h-20 bg-gradient-to-r from-purple-500/30 to-pink-500/30 rounded-full blur-xl"></div>
        <div class="floating-shapes absolute top-40 right-20 w-32 h-32 bg-gradient-to-r from-blue-500/30 to-cyan-500/30 rounded-lg blur-xl"></div>
        <div class="floating-shapes absolute bottom-32 left-1/4 w-24 h-24 bg-gradient-to-r from-green-500/30 to-emerald-500/30 rounded-full blur-xl"></div>
        <div class="floating-shapes absolute top-1/2 right-10 w-16 h-16 bg-gradient-to-r from-yellow-500/30 to-orange-500/30 rounded-lg blur-xl"></div>
        
        <div id="particles-container" class="absolute inset-0"></div>
    </div>

    <div class="min-h-screen p-4 flex items-center justify-center">
        <div class="w-full max-w-md">
            <div class="gradient-border glow-effect">
                <div class="gradient-border-inner p-8">
                    <div class="text-center mb-8 slide-in">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-4 pulse">
                            <i class="fas fa-lock text-white text-2xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-white mb-2">Welcome</h2>
                        <p class="text-gray-400">Sign in to your account</p>
                    </div>

                    {{-- Pop-up Notifikasi Error Login dari Laravel akan muncul di sini --}}
                    @if (session('status'))
                        <div class="session-status success slide-in" style="animation-delay: 0.05s;">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="session-status error slide-in" style="animation-delay: 0.05s;">
                            {{ __('Whoops! Something went wrong.') }}
                            <ul class="mt-3 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form id="loginForm" method="POST" action="{{ route('login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="password" :value="__('Password')" />
                            <x-text-input id="password" class="block mt-1 w-full"
                                          type="password"
                                          name="password"
                                          required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-between slide-in remember-me-group" style="animation-delay: 0.4s;">
                            <label class="flex items-center text-sm text-gray-400">
                                <input id="remember_me" type="checkbox" name="remember" class="mr-2 rounded border-gray-600 bg-gray-800 text-purple-500 focus:ring-purple-500" {{ old('remember') ? 'checked' : '' }}>
                                Remember me
                            </label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-sm text-purple-400 hover:text-purple-300 transition-colors">Forgot password?</a>
                            @endif
                        </div>

                        <button type="submit" class="ripple-effect w-full py-3 bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 slide-in" style="animation-delay: 0.5s;">
                            <span class="flex items-center justify-center">
                                Sign In
                            </span>
                        </button>
                    </form>
                    
                    </div>
            </div>
        </div>
    </div>

    <div id="messageContainer" class="fixed top-4 right-4 z-50"></div>

    <script>
        // JavaScript telah disederhanakan secara signifikan
        class AuthForm {
            constructor() {
                this.createParticles();
            }

            // Fungsi ini tidak lagi diperlukan karena notifikasi error sudah ditangani oleh Blade di dalam form
            // Anda bisa menggunakan ini untuk notifikasi lain jika perlu.
            showMessage(message, type) {
                const messageEl = document.createElement('div');
                messageEl.className = `p-4 rounded-lg mb-4 text-white slide-in ${
                    type === 'success' ? 'bg-green-600' : 
                    type === 'error' ? 'bg-red-600' : 'bg-blue-600'
                }`;
                messageEl.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas ${
                            type === 'success' ? 'fa-check-circle' : 
                            type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'
                        } mr-2"></i>
                        ${message}
                    </div>
                `;
                document.getElementById('messageContainer').appendChild(messageEl);

                setTimeout(() => {
                    messageEl.remove();
                }, 5000);
            }

            createParticles() {
                const container = document.getElementById('particles-container');
                if (!container) return;
                
                setInterval(() => {
                    const particle = document.createElement('div');
                    particle.className = 'particle absolute w-1 h-1 bg-white/30 rounded-full';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 2 + 's';
                    container.appendChild(particle);
                    
                    setTimeout(() => {
                        particle.remove();
                    }, 6000);
                }, 500);
            }
        }

        // DIHAPUS: Fungsi flipCard() dan togglePassword() tidak lagi diperlukan.
        
        document.addEventListener('DOMContentLoaded', () => {
            const authForm = new AuthForm();
            // DIHAPUS: Logika untuk mengecek error register dan membalik kartu saat load.
        });

        // Efek interaktif mouse tetap dipertahankan
        document.addEventListener('mousemove', (e) => {
            const mouseX = e.clientX / window.innerWidth;
            const mouseY = e.clientY / window.innerHeight;
            
            document.querySelectorAll('.floating-shapes').forEach((shape, index) => {
                const speed = (index + 1) * 0.01;
                const x = (mouseX - 0.5) * speed * 100;
                const y = (mouseY - 0.5) * speed * 100;
                
                shape.style.transform = `translate(${x}px, ${y}px)`;
            });
        });
    </script>
</body>
</html>