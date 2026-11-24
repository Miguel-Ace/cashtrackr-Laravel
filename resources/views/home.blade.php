@extends('layouts.app')

@section('title')
    Administrador de Presupuestos impulsado por IA
@endsection

@section('contents')
    <section class="relative bg-purple-950 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12 items-center">
                <div class="lg:col-span-3 space-y-6">
                    <div
                        class="inline-flex items-center gap-2 rounded-lg border border-orange-400/80 px-5 py-2 text-orange-400 shadow-[0_0_18px_rgba(251,146,60,0.25)]">
                        <span class="text-2xl font-bold">
                            Impulsado por IA
                        </span>
                    </div>

                    <h1 class="text-6xl font-bold text-white leading-tight">
                        Controla tus gastos.<br>
                        Alcanza tus <span class="text-amber-500">metas.</span>
                    </h1>
                    <p class="text-lg text-white max-w-xl">
                        CashTrackr es la app inteligente que te ayuda a gestionar tus gastos,
                        crear presupuestos y tomar mejores decisiones financieras con el poder de la IA.
                    </p>

                    <div>
                        <a href="{{ route('register')}} "
                            class=" px-10 py-3 bg-amber-500 hover:bg-amber-600 text-white  transition-colors text-lg font-bold uppercase">
                            Obtener Cuenta Gratis
                        </a>
                    </div>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 pt-8">
                        <div class="flex flex-col items-start gap-2">
                            <div
                                class="w-14 h-14 rounded-full border-2 border-amber-500 bg-amber-500/50 flex items-center justify-center">
                                <img src="{{ asset('img/icon_01.svg') }}" class="w-10" />
                            </div>
                            <h4 class="text-white font-semibold text-xl">IA Avanzada</h4>
                            <p class="text-white">CashTrackr está impulsado por IA.</p>
                        </div>
                        <div class="flex flex-col items-start gap-2">
                            <div
                                class="w-14 h-14 rounded-full border-2 border-indigo-800 bg-indigo-800/40 flex items-center justify-center">
                                <img src="{{ asset('img/icon_02.svg') }}" class="w-10" />
                            </div>
                            <h4 class="text-white font-semibold text-xl">Presupuestos</h4>
                            <p class="text-white">Crea y ajusta presupuestos fácilmente</p>
                        </div>
                        <div class="flex flex-col items-start gap-2">
                            <div
                                class="w-14 h-14 rounded-full border-2 border-pink-600 bg-pink-600/5 flex items-center justify-center">
                                <img src="{{ asset('img/icon_03.svg') }}" class="w-10" />
                            </div>
                            <h4 class="text-white font-semibold text-xl">Categorías</h4>
                            <p class="text-white">Organiza cada gasto a tu manera</p>
                        </div>
                        <div class="flex flex-col items-start gap-2">
                            <div
                                class="w-14 h-14 rounded-full border-2 border-lime-600 bg-lime-600/30 flex items-center justify-center">
                                <img src="{{ asset('img/icon_04.svg') }}" class="w-10" />
                            </div>
                            <h4 class="text-white font-semibold text-xl">100% Seguro</h4>
                            <p class="text-white">Tus datos y privacidad son nuestra prioridad</p>
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-2 relative hidden lg:block">

                    <div class="relative z-10">
                        <img src="{{ asset('img/1.png') }}" alt="App preview" class="w-100 lg:w-80">
                    </div>

                    <div class="absolute bottom-30 right-0 z-10 ">
                        <div
                            class="lg:right-0 w-60 bg-purple-950/60  backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-2xl">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center">🤖
                                </div>
                                <h3 class="text-white font-semibold text-xl">Asistente IA</h3>
                            </div>
                            <p class="text-white">
                                Estoy analizando tus gastos y encontré 3 formas de ahorrar este mes...
                            </p>
                        </div>

                        <div
                            class="mt-5 ml-5 w-60 bg-purple-950/60 backdrop-blur-md border border-white/10 rounded-2xl p-5 shadow-2xl ">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-full bg-amber-500 flex items-center justify-center">🎯
                                </div>
                                <div>
                                    <h3 class="text-white font-semibold text-xl">Presupuestos por  <br /> Meta</h3>
                                    <p class="text-amber-500">Remodelación</p>
                                </div>
                            </div>
                            <p class="text-xl font-bold text-white mb-2">
                                $300 <span class="text-xs text-gray-400 font-normal">/ $500</span>
                            </p>
                            <div class="w-full bg-white/10 rounded-full h-1.5">
                                <div class="bg-amber-500 h-1.5 rounded-full" style="width: 60%"></div>
                            </div>
                            <p class="text-right text-xs text-gray-400 mt-1">60%</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <svg class="w-full h-24 block -mb-px" viewBox="0 0 900 40.5" preserveAspectRatio="none"
        xmlns="http://www.w3.org/2000/svg">
        <path fill="oklch(29.1% 0.149 302.717)"
            d="M0,24l21.5,1c21.5,1,64.5,3,107.3,4.2,42.9,1.1,85.5,1.5,128.4,1,42.8-.5,85.8-1.9,128.6-1.7,42.9.2,85.5,1.8,128.4.5,42.8-1.3,85.8-5.7,128.6-7.7,42.9-2,85.5-1.6,128.4.4,42.8,2,85.8,5.6,107.3,7.5l21.5,1.8V0H0v24Z" />
    </svg>


    <main class="bg-violet-50 pt-40 pb-20 -mt-20 ">
        <div class="mx-auto max-w-7xl">
            <div class="space-y-2">
                <p class="text-purple-900 uppercase font-bold text-2xl">Visualiza, entiende, decide</p>
                <p class="text-6xl font-bold">Todo en <span class="text-amber-500"> un solo lugar</span></p>
                <p class="max-w-[50%] text-xl text-slate-800">
                    Organiza tus finanzas, visualiza tus gastos por categorías y mantén el control de tu presupuesto en
                    tiempo
                    real.
                </p>
            </div>
            <div class="grid gap-5 lg:grid-cols-3 mt-20">

                <div class="">
                    <img src="{{ asset('img/2.png') }}" alt="Imagen chat IA" />
                </div>

                <section class="py-16 px-6 col-span-2 space-y-10 flex flex-col justify-center divide-y divide-amber-500">

                    <article class="flex gap-4 items-start  px-5 pb-10">
                        <div class="shrink-0 w-14 h-14 rounded-full border-2 border-purple-600 bg-purple-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-purple-600 text-2xl">Gráficas claras</h3>
                            <p class="text-lg">Visualiza tus gastos con gráficos intuitivos y fáciles de
                                entender.</p>
                        </div>
                    </article>

                    <article class="flex gap-4 items-start  px-5  pb-10">
                        <div class="shrink-0 w-14 h-14 rounded-full border-2 border-orange-600 bg-orange-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-orange-600 text-2xl">Presupuestos flexibles</h3>
                            <p class="text-lg">Crea presupuestos por categoría y ajústalos cuando lo necesites.
                            </p>
                        </div>
                    </article>

                    <article class="flex gap-4 items-start  px-5  pb-10">
                        <div class="shrink-0 w-14 h-14 rounded-full border-2 border-pink-600 bg-pink-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-pink-600 text-2xl">Inteligencia Artificial</h3>
                            <p class="text-lg">Agrega Gastos, Pregunta sobre tu presupuestos o Registra tus Tickets de Compra</p>
                        </div>
                    </article>

                    <article class="flex gap-4 items-start px-5">
                        <div
                            class="shrink-0 w-14 h-14 rounded-full border-2 border-lime-500 bg-lime-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-lime-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-lime-600 text-2xl">Sincroniza todo</h3>
                            <p class="text-lg">Tus datos siempre seguros y disponibles en todos tus
                                dispositivos.</p>
                        </div>
                    </article>
            </div>
            </section>
        </div>
        </div>
    </main>

    <footer class="py-10 bg-purple-950">
      <p class="text-center text-amber-500 text-xl">Todos los derechos reservados  {{ date('Y') }} &copy;</p>
    </footer>
@endsection