<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'KAS') }} - Data Center Premium</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    <!-- Custom Premium CSS -->
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    @vite(['resources/js/app.js'])
    
    <style>
        /* Landing Overrides */
        body {
            padding-top: 0;
            background: #020617;
            overflow-x: hidden;
        }
        
        /* Hero Section */
        .hero-section {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at center, #1e1b4b 0%, #020617 100%);
            overflow: hidden;
        }
        
        .hero-bg-glow {
            position: absolute;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(168, 85, 247, 0.15) 0%, transparent 50%);
            z-index: 0;
        }
        
        .hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
            max-width: 800px;
            padding: 2rem;
        }
        
        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            background: linear-gradient(135deg, #fff 0%, #a5b4fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            line-height: 1.1;
            animation: fadeUp 1s ease-out;
        }
        
        .hero-subtitle {
            font-size: 1.25rem;
            color: #94a3b8;
            margin-bottom: 3rem;
            line-height: 1.6;
            animation: fadeUp 1s ease-out 0.2s backwards;
        }
        
        .hero-btns {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            animation: fadeUp 1s ease-out 0.4s backwards;
        }
        
        /* Navbar */
        .landing-nav {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            padding: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 50;
        }
        
        .nav-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Resources Section */
        .resources-section {
            padding: 6rem 2rem;
            background: #020617;
            position: relative;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            margin-bottom: 1rem;
        }
        
        .resource-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .resource-card {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
            position: relative;
            backdrop-filter: blur(10px);
        }
        
        .resource-card:hover {
            transform: translateY(-10px) scale(1.02);
            border-color: var(--primary-color);
            box-shadow: 0 20px 40px -10px rgba(99, 102, 241, 0.3);
        }
        
        .resource-image-wrapper {
            height: 200px;
            background: #0f172a;
            position: relative;
            overflow: hidden;
        }
        
        .resource-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        
        .resource-card:hover .resource-image {
            transform: scale(1.1);
        }
        
        .resource-content {
            padding: 1.5rem;
        }
        
        .resource-category {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--primary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .resource-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        
        .resource-specs-mini {
            display: flex;
            gap: 1rem;
            color: #94a3b8;
            font-size: 0.875rem;
            margin-top: 1rem;
        }
        
        /* Modal */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            z-index: 100;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .modal-backdrop.active {
            display: flex;
            opacity: 1;
        }
        
        .modal-content {
            background: #0f172a;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            width: 90%;
            max-width: 900px;
            margin: auto;
            position: relative;
            transform: scale(0.95);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        .modal-backdrop.active .modal-content {
            transform: scale(1);
        }
        
        .modal-image-side {
            width: 40%;
            background: #1e293b;
            position: relative;
        }
        
        .modal-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .modal-details-side {
            width: 60%;
            padding: 3rem;
            display: flex;
            flex-direction: column;
        }
        
        .modal-close {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            z-index: 10;
        }
        
        .modal-close:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .hero-title { font-size: 2.5rem; }
            .modal-content { flex-direction: column; max-height: 90vh; overflow-y: auto; }
            .modal-image-side, .modal-details-side { width: 100%; }
            .modal-image-side { height: 200px; }
            .modal-details-side { padding: 1.5rem; }
        }
    </style>
</head>
<body>

    <!-- Navigation -->
    <nav class="landing-nav">
        <a href="/" class="nav-brand">
            <i data-lucide="server" style="color: var(--primary-color);"></i>
            KAS DATA
        </a>
        <div class="nav-links">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary">Mon Tableau de Bord</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary-outline" style="margin-right: 10px; color: white; border: 1px solid rgba(255,255,255,0.2);">Connexion</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary">Commencer</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="hero-section">
        <div class="hero-bg-glow"></div>
        <div class="hero-content">
            <h1 class="hero-title">Infrastructure Cloud Nouvelle Génération</h1>
            <p class="hero-subtitle">
                Accédez à une puissance de calcul illimitée. Serveurs dédiés, VPS et solutions de stockage haute performance pour vos projets les plus ambitieux.
            </p>
            <div class="hero-btns">
                <a href="#catalogue" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem;">
                    Explorer les Ressources
                </a>
                <a href="{{ route('register') }}" class="btn btn-secondary" style="padding: 1rem 2rem; font-size: 1.1rem; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.1);">
                    Créer un compte
                </a>
            </div>
        </div>
    </header>

    <!-- Catalogue Section -->
    <main id="catalogue" class="resources-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Nos Ressources Disponibles</h2>
                <p style="color: #94a3b8; font-size: 1.1rem;">Sélectionnez une ressource pour voir les détails et réserver.</p>
            </div>

            <div class="resource-grid">
                @forelse($resources as $resource)
                    <div class="resource-card" onclick="openModal({{ json_encode($resource) }})">
                        <div class="resource-image-wrapper">
                            @if($resource->image)
                                <img src="{{ asset('storage/' . $resource->image) }}" alt="{{ $resource->name }}" class="resource-image">
                            @else
                                <!-- Fallback gradients based on category -->
                                <div class="resource-image" style="background: linear-gradient(45deg, #1e293b, #0f172a); display: flex; align-items: center; justify-content: center; height: 100%;">
                                    <i data-lucide="{{ strtolower($resource->category->name ?? '') == 'serveurs' ? 'server' : 'box' }}" size="48" color="#4f46e5"></i>
                                </div>
                            @endif
                        </div>
                        <div class="resource-content">
                            <div class="resource-category">{{ $resource->category->name ?? 'Général' }}</div>
                            <h3 class="resource-name">{{ $resource->name }}</h3>
                            <div class="resource-specs-mini">
                                <span><i data-lucide="cpu" size="14" style="display:inline; vertical-align:middle"></i> {{ $resource->cpu_cores }} Cores</span>
                                <span><i data-lucide="zap" size="14" style="display:inline; vertical-align:middle"></i> {{ $resource->ram_gb }} GB RAM</span>
                            </div>
                            <div style="margin-top: 15px;">
                                @auth
                                    <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn btn-primary" style="width: 100%;">Réserver</a>
                                @else
                                    <a href="{{ route('register') }}" class="btn btn-secondary" style="width: 100%;">S'inscrire pour réserver</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="grid-column: 1 / -1; text-align: center; color: #64748b; padding: 4rem;">
                        <i data-lucide="server-off" size="48" style="margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p>Aucune ressource disponible pour le moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>

    <!-- Modal Details -->
    <div id="resourceModal" class="modal-backdrop" onclick="if(event.target === this) closeModal()">
        <div class="modal-content">
            <button class="modal-close" onclick="closeModal()">
                <i data-lucide="x"></i>
            </button>
            
            <div class="modal-image-side">
                <img id="modalImage" src="" alt="Resource" class="modal-image" style="display: none;">
                <div id="modalImagePlaceholder" style="width:100%; height:100%; background: #1e293b; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="server" size="64" color="#6366f1"></i>
                </div>
            </div>
            
            <div class="modal-details-side">
                <span id="modalCategory" class="resource-category" style="font-size: 0.9rem;">CATEGORY</span>
                <h2 id="modalTitle" style="font-size: 2rem; font-weight: 700; color: white; margin: 0.5rem 0 1.5rem 0;">Resource Name</h2>
                
                <p id="modalDescription" style="color: #94a3b8; line-height: 1.7; margin-bottom: 2rem; flex-grow: 1;">
                    Description goes here...
                </p>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; background: rgba(255,255,255,0.03); padding: 1.5rem; border-radius: 12px;">
                    <div>
                        <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">CPU Cores</div>
                        <div id="modalCpu" style="color: white; font-weight: 600; font-size: 1.1rem;">-</div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">RAM Memory</div>
                        <div id="modalRam" style="color: white; font-weight: 600; font-size: 1.1rem;">-</div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">Storage</div>
                        <div id="modalStorage" style="color: white; font-weight: 600; font-size: 1.1rem;">-</div>
                    </div>
                    <div>
                        <div style="color: #64748b; font-size: 0.875rem; margin-bottom: 0.25rem;">OS</div>
                        <div id="modalOs" style="color: white; font-weight: 600; font-size: 1.1rem;">-</div>
                    </div>
                </div>
                
                <div style="margin-top: auto;">
                    @auth
                        <a id="modalReserveBtn" href="#" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem;">
                            Réserver maintenant
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem;">
                            Créer un compte pour réserver
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
        
        const modal = document.getElementById('resourceModal');
        const modalBackdrop = document.querySelector('.modal-backdrop');
        const baseReservationUrl = "{{ url('/reservations/create') }}";
        
        function openModal(resource) {
            // Populate Data
            document.getElementById('modalTitle').textContent = resource.name;
            document.getElementById('modalCategory').textContent = resource.category ? resource.category.name : 'RESOURCE';
            document.getElementById('modalDescription').textContent = resource.description || 'Aucune description disponible.';
            document.getElementById('modalCpu').textContent = resource.cpu_cores + ' Cores';
            document.getElementById('modalRam').textContent = resource.ram_gb + ' GB';
            document.getElementById('modalStorage').textContent = resource.storage_tb + ' TB';
            document.getElementById('modalOs').textContent = resource.os_name || 'N/A';
            
            // Image handling
            const img = document.getElementById('modalImage');
            const placeholder = document.getElementById('modalImagePlaceholder');
            if(resource.image) {
                img.src = '/storage/' + resource.image;
                img.style.display = 'block';
                placeholder.style.display = 'none';
            } else {
                img.style.display = 'none';
                placeholder.style.display = 'flex';
            }
            
            // Link handling
            const reserveBtn = document.getElementById('modalReserveBtn');
            if(reserveBtn) {
                // Assuming route is /reservations/create/{id}
                reserveBtn.href = baseReservationUrl + '/' + resource.id;
            }
            
            // Show
            modal.style.display = 'flex';
            // Small delay for transition
            setTimeout(() => {
                modal.classList.add('active');
            }, 10);
        }
        
        function closeModal() {
            modal.classList.remove('active');
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }

        // Close on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                closeModal();
            }
        });
    </script>
</body>
</html>