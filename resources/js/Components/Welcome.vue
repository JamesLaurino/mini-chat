<script setup>
import {Link} from "@inertiajs/vue3";
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gray-100 py-1 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-xl overflow-hidden">
            <div class="p-6 sm:p-10">

                <h1 class="mt-1 text-3xl sm:text-4xl font-bold text-gray-900">
                    Bienvenue sur Mini-chat ✨
                </h1>

                <p class="mt-4 text-gray-600 text-base leading-relaxed">
                    ChatLibre est une application de discussion propulsée par l’intelligence artificielle.
                    <br />
                    Pose-lui des questions, discute avec elle comme avec ChatGPT, en toute liberté !
                </p>

                <p class="mt-4 text-gray-700 text-sm">
                    💬 <strong>50 messages gratuits</strong> par utilisateur — Pas besoin de carte bancaire.
                </p>

                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-indigo-50 border border-indigo-200 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-indigo-700 mb-2">⚡ Simple et rapide</h2>
                        <p class="text-sm text-gray-600">
                            Commence à discuter immédiatement sans configuration complexe.
                        </p>
                    </div>
                    <div class="bg-green-50 border border-green-200 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-green-700 mb-2">🔒 Respect de ta vie privée</h2>
                        <p class="text-sm text-gray-600">
                            Tes messages ne sont jamais utilisés à des fins commerciales.
                        </p>
                    </div>
                    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-blue-700 mb-2">🎯 50 prompts gratuits</h2>
                        <p class="text-sm text-gray-600">
                            Tu disposes de 50 messages offerts pour explorer toutes les fonctionnalités.
                        </p>
                    </div>
                    <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg">
                        <h2 class="text-lg font-semibold text-yellow-700 mb-2">🌙 Préférences personnalisées</h2>
                        <p class="text-sm text-gray-600">
                            Personnalise ton expérience avec tes propres préférences.
                        </p>
                    </div>
                </div>

                <div class="mt-10 text-center">
                    <Link dusk="click-start-chat" :href="route('ask.index')" class="btn btn-primary">
                        Commencer à discuter
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
