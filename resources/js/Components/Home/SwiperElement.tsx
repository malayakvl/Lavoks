import { useEffect, useRef } from "react";
import Swiper from "swiper";
import { Navigation, Pagination, FreeMode } from "swiper/modules"; // Добавили FreeMode

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/free-mode"; // Не забываем стили для него

interface SwiperElementProps {
    elements?: any[]; // Делаем необязательным, пока не используем
}

export default function SwiperElement() {
    const swiperContainerRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!swiperContainerRef.current) return;

        const swiperInstance = new Swiper(swiperContainerRef.current, {
            modules: [Navigation, Pagination, FreeMode] as any,
            slidesPerView: "auto",
            spaceBetween: 16,
            grabCursor: true,
            freeMode: true,

            observer: true,
            observeParents: true,

            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
        });

        // На всякий случай пинаем Swiper обновиться чуть-чуть позже
        setTimeout(() => {
            swiperInstance.update();
        }, 100);

        return () => {
            swiperInstance.destroy();
        };
    }, []);

    return (
        // Контейнеру обязательно даем w-full, чтобы он занял всю ширину родителя
        <div ref={swiperContainerRef} className="swiper w-full !overflow-hidden">
            <div className="swiper-wrapper">

                {/* Важно: на swiper-slide вешаем !w-auto,
                  а реальную ширину (например, w-[280px]) задаем блоку ВНУТРИ
                */}
                <div className="swiper-slide !w-auto">
                    {/* Карточка 1: Гаманці */}
                    <a href="#" className="category-promo-card group category-promo-card-1">
                        <div className="category-promo-img-wrap">
                            <img
                                src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"
                                alt="Гаманці"
                                className="category-promo-img"
                            />
                        </div>
                        <div className="category-promo-info">
                            <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">
                                {/* Твоя кастомная иконка или SVG кошелька */}
                                <span className="text-sm font-semibold tracking-wide">Гаманці</span>
                            </div>
                            <span className="category-promo-link">Переглянути &rarr;</span>
                        </div>
                    </a>
                </div>
                <div className="swiper-slide !w-auto">
                    {/* Карточка 1: Гаманці */}
                    <a href="#" className="category-promo-card group category-promo-card-1">
                        <div className="category-promo-img-wrap">
                            <img
                                src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"
                                alt="Гаманці"
                                className="category-promo-img"
                            />
                        </div>
                        <div className="category-promo-info">
                            <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">
                                {/* Твоя кастомная иконка или SVG кошелька */}
                                <span className="text-sm font-semibold tracking-wide">Гаманці</span>
                            </div>
                            <span className="category-promo-link">Переглянути &rarr;</span>
                        </div>
                    </a>
                </div>
                <div className="swiper-slide !w-auto">
                    {/* Карточка 1: Гаманці */}
                    <a href="#" className="category-promo-card group category-promo-card-1">
                        <div className="category-promo-img-wrap">
                            <img
                                src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"
                                alt="Гаманці"
                                className="category-promo-img"
                            />
                        </div>
                        <div className="category-promo-info">
                            <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">
                                {/* Твоя кастомная иконка или SVG кошелька */}
                                <span className="text-sm font-semibold tracking-wide">Гаманці</span>
                            </div>
                            <span className="category-promo-link">Переглянути &rarr;</span>
                        </div>
                    </a>
                </div>
                <div className="swiper-slide !w-auto">
                    {/* Карточка 1: Гаманці */}
                    <a href="#" className="category-promo-card group category-promo-card-1">
                        <div className="category-promo-img-wrap">
                            <img
                                src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"
                                alt="Гаманці"
                                className="category-promo-img"
                            />
                        </div>
                        <div className="category-promo-info">
                            <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">
                                {/* Твоя кастомная иконка или SVG кошелька */}
                                <span className="text-sm font-semibold tracking-wide">Гаманці</span>
                            </div>
                            <span className="category-promo-link">Переглянути &rarr;</span>
                        </div>
                    </a>
                </div>
                <div className="swiper-slide !w-auto">
                    {/* Карточка 1: Гаманці */}
                    <a href="#" className="category-promo-card group category-promo-card-1">
                        <div className="category-promo-img-wrap">
                            <img
                                src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"
                                alt="Гаманці"
                                className="category-promo-img"
                            />
                        </div>
                        <div className="category-promo-info">
                            <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">
                                {/* Твоя кастомная иконка или SVG кошелька */}
                                <span className="text-sm font-semibold tracking-wide">Гаманці</span>
                            </div>
                            <span className="category-promo-link">Переглянути &rarr;</span>
                        </div>
                    </a>
                </div>
                <div className="swiper-slide !w-auto">
                    {/* Карточка 1: Гаманці */}
                    <a href="#" className="category-promo-card group category-promo-card-1">
                        <div className="category-promo-img-wrap">
                            <img
                                src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"
                                alt="Гаманці"
                                className="category-promo-img"
                            />
                        </div>
                        <div className="category-promo-info">
                            <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">
                                {/* Твоя кастомная иконка или SVG кошелька */}
                                <span className="text-sm font-semibold tracking-wide">Гаманці</span>
                            </div>
                            <span className="category-promo-link">Переглянути &rarr;</span>
                        </div>
                    </a>
                </div>
                <div className="swiper-slide !w-auto">
                    {/* Карточка 1: Гаманці */}
                    <a href="#" className="category-promo-card group category-promo-card-1">
                        <div className="category-promo-img-wrap">
                            <img
                                src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"
                                alt="Гаманці"
                                className="category-promo-img"
                            />
                        </div>
                        <div className="category-promo-info">
                            <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">
                                {/* Твоя кастомная иконка или SVG кошелька */}
                                <span className="text-sm font-semibold tracking-wide">Гаманці</span>
                            </div>
                            <span className="category-promo-link">Переглянути &rarr;</span>
                        </div>
                    </a>
                </div>



            </div>

            {/* Точки и стрелки */}
            <div className="swiper-pagination !relative !bottom-0 !mt-4"></div>
            <div className="swiper-button-prev"></div>
            <div className="swiper-button-next"></div>
        </div>
    );
}
