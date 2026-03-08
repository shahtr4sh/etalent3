<section class="max-h-screen py-5 bg-white flex items-center">
    <div class="container max-w-6xl px-4 mx-auto">
        <!-- Header Section -->
        <div class="text-center">
            <h2 class="text-4xl font-bold tracking-tight text-gray-900">eTalent@UniSHAMS</h2>
            <p class="mt-2 text-lg text-gray-600">Selamat datang ke portal eTalent.</p>
        </div>

        <!-- Cards Grid -->
        <div class="grid max-w-4xl grid-cols-1 gap-3 mx-auto mt-12 md:grid-cols-2 lg:gap-8">
            <!-- Card 1: Buat Permohonan -->
            <a href="{{ route('app.permohonan.create') }}"
               class="group block transform transition-all duration-300 hover:scale-105">
                <div class="flex flex-col items-center h-full p-8 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-colors duration-200">
                    <!-- Icon Container -->
                    <div class="flex items-center justify-center w-20 h-20 mb-6 bg-blue-500 rounded-2xl group-hover:bg-blue-600 transition-colors duration-200">
                        <i class="fa-solid fa-laptop-file fa-2x text-white"></i>
                    </div>

                    <!-- Text Content -->
                    <h3 class="mb-3 text-xl font-semibold text-gray-800 text-center">
                        Buat Permohonan Kenaikan Pangkat
                    </h3>
                    <p class="text-base text-center text-gray-600 max-w-[250px]">
                        Send out notifications to all your customers to keep them engaged.
                    </p>
                </div>
            </a>

            <!-- Card 2: Semak Permohonan -->
            <a href="{{ route('app.permohonan.index') }}"
               class="group block transform transition-all duration-300 hover:scale-105">
                <div class="flex flex-col items-center h-full p-8 bg-gray-100 rounded-2xl hover:bg-gray-200 transition-colors duration-200">
                    <!-- Icon Container -->
                    <div class="flex items-center justify-center w-20 h-20 mb-6 bg-blue-500 rounded-2xl group-hover:bg-blue-600 transition-colors duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="w-10 h-10 text-white"
                             viewBox="0 0 24 24"
                             stroke-width="1.5"
                             stroke="currentColor"
                             fill="none">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M18 8a3 3 0 0 1 0 6" />
                            <path d="M10 8v11a1 1 0 0 1 -1 1h-1a1 1 0 0 1 -1 -1v-5" />
                            <path d="M12 8h0l4.524 -3.77a0.9 .9 0 0 1 1.476 .692v12.156a0.9 .9 0 0 1 -1.476 .692l-4.524 -3.77h-8a1 1 0 0 1 -1 -1v-4a1 1 0 0 1 1 -1h8" />
                        </svg>
                    </div>

                    <!-- Text Content -->
                    <h3 class="mb-3 text-xl font-semibold text-gray-800 text-center">
                        Semak Permohonan Anda
                    </h3>
                    <p class="text-base text-center text-gray-600 max-w-[250px]">
                        Send out notifications to all your customers to keep them engaged.
                    </p>
                </div>
            </a>
        </div>
    </div>
</section>
