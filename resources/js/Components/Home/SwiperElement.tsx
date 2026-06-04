import React, { useEffect, useRef } from "react";
import Swiper from "swiper";
import { Navigation, Pagination, FreeMode } from "swiper/modules";
import type { SwiperOptions } from "swiper/types";

import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import "swiper/css/free-mode";

export default function SwiperElement({ items }: { items: any[] }) {
    const swiperContainerRef = useRef<HTMLDivElement>(null);
console.log(items);
    useEffect(() => {
        if (!swiperContainerRef.current) return;

        const swiperParams: { observer: boolean; observeParents: boolean; navigation: { nextEl: string; prevEl: string }; pagination: { el: string; clickable: boolean }; freeMode: boolean; grabCursor: boolean; slidesPerView: string; modules: ((options: { params: Swiper["params"]; swiper: Swiper; extendParams: (obj: { [p: string]: any }) => void; on: Swiper["on"]; once: Swiper["once"]; off: Swiper["off"]; emit: Swiper["emit"] }) => void)[]; spaceBetween: number } = {
            modules: [Navigation, Pagination, FreeMode],
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
        };

        const swiperInstance = new Swiper(swiperContainerRef.current, swiperParams);

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
                {items.map((item, index) => {
                    // Transform image path for categories (same as Header)
                    const imageSrc = item.slidable_type === 'App\\Models\\Category'
                        ? item.image?.replace('categories/original/', 'categories/cut/')
                        : item.image;

                    return (
                        <div className="swiper-slide !w-auto" key={index}>
                            <a href="#" className="category-card group">

                                {/* IMAGE */}
                                <div className="category-card-image">
                                    <div class='swiper-img' style={{ backgroundImage: `url(/storage/${imageSrc})`, width: '100%', }}></div>
                                    {/*<img*/}
                                    {/*    src={'/storage/' + imageSrc}*/}
                                    {/*    alt={item.title}*/}
                                    {/*    className="category-card-img"*/}
                                    {/*/>*/}
                                </div>

                                {/* INFO */}
                                <div className="category-card-info">
                                    <div className="swiper-title">{item.title}</div>
                                    <div className="category-card-cta">
                                        Переглянути →
                                    </div>
                                </div>

                            </a>
                        </div>
                    );
                })}
                {/*<div className="swiper-slide !w-auto">*/}
                {/*    <a href="#" className="category-card group">*/}

                {/*        /!* IMAGE *!/*/}
                {/*        <div className="category-card-image">*/}
                {/*            <div className="category-card-glow" />*/}

                {/*            <img*/}
                {/*                src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"*/}
                {/*                alt="Гаманці"*/}
                {/*                className="category-card-img"*/}
                {/*            />*/}
                {/*        </div>*/}

                {/*        /!* INFO *!/*/}
                {/*        <div className="category-card-info">*/}
                {/*            <div className="swiper-title">Гаманці</div>*/}

                {/*            <div className="category-card-meta">*/}
                {/*                24 моделі · handmade*/}
                {/*            </div>*/}

                {/*            <div className="category-card-cta">*/}
                {/*                Переглянути →*/}
                {/*            </div>*/}
                {/*        </div>*/}

                {/*    </a>*/}
                {/*</div>*/}
                {/*<div className="swiper-slide !w-auto">*/}
                {/*    <a href="#" className="category-card group">*/}

                {/*        /!* IMAGE *!/*/}
                {/*        <div className="category-card-image">*/}
                {/*            <div className="category-card-glow" />*/}

                {/*            <img*/}
                {/*                src="/storage/categories/cut/gamanci-ultra.webp"*/}
                {/*                alt="Гаманці"*/}
                {/*                className="category-card-img"*/}
                {/*            />*/}
                {/*        </div>*/}

                {/*        /!* INFO *!/*/}
                {/*        <div className="category-card-info">*/}
                {/*            <div className="swiper-title">Гаманці</div>*/}

                {/*            <div className="category-card-meta">*/}
                {/*                24 моделі · handmade*/}
                {/*            </div>*/}

                {/*            <div className="category-card-cta">*/}
                {/*                Переглянути →*/}
                {/*            </div>*/}
                {/*        </div>*/}

                {/*    </a>*/}
                {/*</div>*/}






            </div>

            {/* Точки и стрелки */}
            <div className="swiper-pagination !relative !bottom-0 !mt-4"></div>
            <div className="swiper-button-prev"></div>
            <div className="swiper-button-next"></div>
        </div>
    );
}
