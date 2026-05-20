@extends('layouts.app')

@section('title', 'Lavoks! - Аксессуары из натуральной кожи')

@section('content')
    {{-- Hero Section --}}
    <section class="bg-gradient-to-r from-gray-900 to-gray-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">
                    Аксессуары из натуральной кожи
                </h1>
                <p class="text-xl text-gray-300 mb-8">
                    Ручная работа. Премиальное качество.
                </p>
                <a href="{{ route('catalog') }}" class="inline-block bg-white text-gray-900 px-8 py-3 rounded-md font-semibold hover:bg-gray-100 transition">
                    Смотреть каталог
                </a>
            </div>
        </div>
    </section>

    {{-- Teasers Section --}}
    @if(isset($teasers) && $teasers->count() > 0)
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-center mb-12">Популярные категории</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($teasers as $teaser)
                        <a href="{{ $teaser->page_url ?? '#' }}" class="block group">
                            <div class="relative overflow-hidden rounded-lg shadow-lg">
                                @if($teaser->image)
                                    <img src="{{ asset('storage/' . $teaser->image) }}"
                                         alt="{{ $teaser->caption }}"
                                         class="w-full h-64 object-cover group-hover:scale-105 transition duration-300">
                                @endif
                                <div class="absolute inset-0 bg-black bg-opacity-40 flex items-center justify-center">
                                    <h3 class="text-white text-2xl font-semibold">{{ $teaser->caption }}</h3>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Features Section --}}
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-4xl mb-4">🎨</div>
                    <h3 class="text-xl font-semibold mb-2">Ручная работа</h3>
                    <p class="text-gray-600">Каждое изделие создается вручную мастерами</p>
                </div>
                <div>
                    <div class="text-4xl mb-4">✨</div>
                    <h3 class="text-xl font-semibold mb-2">Премиум кожа</h3>
                    <p class="text-gray-600">Используем только натуральную кожу высшего качества</p>
                </div>
                <div>
                    <div class="text-4xl mb-4">🚚</div>
                    <h3 class="text-xl font-semibold mb-2">Быстрая доставка</h3>
                    <p class="text-gray-600">Доставка по всей Украине за 1-3 дня</p>
                </div>
            </div>
        </div>
    </section>
@endsection
