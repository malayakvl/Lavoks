<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            {{-- Company Info --}}
            <div>
                <h3 class="text-lg font-semibold mb-4">Lavoks</h3>
                <p class="text-gray-400 text-sm">
                    Качественные аксессуары из натуральной кожи
                </p>
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Каталог</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition">Сумки</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition">Кошельки</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition">Аксессуары</a></li>
                </ul>
            </div>

            {{-- Customer Service --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Информация</h4>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition">О нас</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition">Доставка</a></li>
                    <li><a href="#" class="text-gray-400 hover:text-white text-sm transition">Контакты</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider mb-4">Контакты</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li>Email: info@lavoks.com</li>
                    <li>Тел: +380 XX XXX XX XX</li>
                </ul>
            </div>
        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; {{ date('Y') }} Lavoks. Все права защищены.</p>
        </div>
    </div>
</footer>
