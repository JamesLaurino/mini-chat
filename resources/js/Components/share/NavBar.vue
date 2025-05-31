<script setup>

</script>

<template>
    <nav class="bg-black text-white px-4 py-3 flex items-center justify-between">
        <div class="flex items-center space-x-2">
            <span class="font-bold text-xl">MonSite</span>
        </div>
        <div class="hidden md:flex items-center space-x-4">
            <a href="#" class="hover:text-gray-300 transition">Accueil</a>
            <a href="#" class="hover:text-gray-300 transition">À propos</a>
            <a href="#" class="hover:text-gray-300 transition">Contact</a>
        </div>
        <div class="md:hidden">
            <!-- Bouton pour le menu burger (optionnel) -->
            <button id="menu-button" class="focus:outline-none">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </nav>

    <!-- Menu mobile caché -->
    <div id="mobile-menu" class="hidden md:hidden bg-black text-white px-4 py-2">
        <a href="#" class="block py-1 hover:text-gray-300 transition">Accueil</a>
        <a href="#" class="block py-1 hover:text-gray-300 transition">À propos</a>
        <a href="#" class="block py-1 hover:text-gray-300 transition">Contact</a>
    </div>

</template>
