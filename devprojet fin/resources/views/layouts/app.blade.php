<header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
    
                  @if (Route::has('login'))
    <nav class="flex items-center justify-end gap-4">
        @auth
            {{-- L'utilisateur est CONNECTÉ : Affiche Dashboard et Déconnexion --}}
            
            <a
                href="{{ url('/dashboard') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
            >
                Dashboard
            </a>
            
            {{-- **LE NOUVEAU LIEN DE DÉCONNEXION** --}}
            <a
                href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="inline-block px-5 py-1.5 bg-[#F53003] hover:bg-black text-white dark:bg-[#FF4433] dark:hover:bg-white dark:hover:text-[#1C1C1A] rounded-sm text-sm leading-normal transition-all"
            >
                Déconnexion
            </a>
            
            {{-- **LE FORMULAIRE POST CACHÉ (OBLIGATOIRE)** --}}
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf 
            </form>

        @else
            {{-- L'utilisateur n'est PAS connecté : Affiche Log in et Register --}}

            <a
                href="{{ route('login') }}"
                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
            >
                Log in
            </a>

            @if (Route::has('register'))
                <a
                    href="{{ route('register') }}"
                    class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                >
                    Register
                </a>
            @endif
        @endauth
    </nav>
@endif
</header>

{{-- Reste du code de la page --}}