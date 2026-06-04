import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import EmblaCarousel, { Teaser } from './EmblaCarousel'
import { EmblaOptionsType } from 'embla-carousel'
import { ProductCard } from "../Components/Product/ProductCard";
import SwiperElement from "../Components/Home/SwiperElement";


const OPTIONS: EmblaOptionsType = { loop: true, duration: 30 }

interface Product {
    id: number;
    name: string | null;
    price: number | null;
    image: string | null;
}

interface HomeProps {
    teasers: Teaser[];
    newProducts: Product[],
    carouselItems: any[];
}

export default function Home({ teasers, newProducts, updatedProducts, carouselItems }: HomeProps) {
    return (
        <AuthenticatedLayout
            header={<h1 className="text-xl font-semibold">Home</h1>}
        >
            <Head title="Home" />

            <EmblaCarousel teasers={teasers} options={OPTIONS} />

            <div className="px-4">
                <section className="w-full bg-[#15110d] py-0 mt-4 mb-0">
                    <div className="mx-auto px-6 pt-4 pb-0">
                        <SwiperElement items={carouselItems} />

                        {/* Сетка категорий — 6 колонок на десктопе */}
                        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                            {/*<SwiperElement />*/}
                            {/* Карточка 1: Гаманці */}
                            {/*<a href="#" className="category-promo-card group category-promo-card-1">*/}
                            {/*    <div className="category-promo-img-wrap">*/}
                            {/*        <img*/}
                            {/*            src="/storage/categories/cut/sumki-tout-paket-zi-skiri.webp"*/}
                            {/*            alt="Гаманці"*/}
                            {/*            className="category-promo-img"*/}
                            {/*        />*/}
                            {/*    </div>*/}
                            {/*    <div className="category-promo-info">*/}
                            {/*        <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">*/}
                            {/*            /!* Твоя кастомная иконка или SVG кошелька *!/*/}
                            {/*            <span className="text-sm font-semibold tracking-wide">Гаманці</span>*/}
                            {/*        </div>*/}
                            {/*        <span className="category-promo-link">Переглянути &rarr;</span>*/}
                            {/*    </div>*/}
                            {/*</a>*/}

                            {/* Карточка 2: Обкладинки */}
                            {/*<a href="#" className="category-promo-card group category-promo-card-2">*/}
                            {/*    <div className="category-promo-img-wrap">*/}
                            {/*        <img*/}
                            {/*            src="/storage/categories/cut/zazimi-dlia-grosei-2-kiseni.webp"*/}
                            {/*            alt="Обкладинки"*/}
                            {/*            className="category-promo-img"*/}
                            {/*        />*/}
                            {/*    </div>*/}
                            {/*    <div className="category-promo-info">*/}
                            {/*        <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">*/}
                            {/*            <span className="text-sm font-semibold tracking-wide">Обкладинки</span>*/}
                            {/*        </div>*/}
                            {/*        <span className="category-promo-link">Переглянути &rarr;</span>*/}
                            {/*    </div>*/}
                            {/*</a>*/}

                            {/* Карточка 3: Зажими для грошей */}
                            {/*<a href="#" className="category-promo-card group">*/}
                            {/*    <div className="category-promo-img-wrap">*/}
                            {/*        <img*/}
                            {/*            src="/storage/categories/cut/sumki-tout-paket-zi-skiri-rozmir-l.webp"*/}
                            {/*            alt="Зажими для грошей"*/}
                            {/*            className="category-promo-img"*/}
                            {/*        />*/}
                            {/*    </div>*/}
                            {/*    <div className="category-promo-info">*/}
                            {/*        <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">*/}
                            {/*            <span className="text-sm font-semibold tracking-wide">Зажими для грошей</span>*/}
                            {/*        </div>*/}
                            {/*        <span className="category-promo-link">Переглянути &rarr;</span>*/}
                            {/*    </div>*/}
                            {/*</a>*/}

                            {/* Карточка 4: Картхолдери */}
                            {/*<a href="#" className="category-promo-card group category-promo-card-1">*/}
                            {/*    <div className="category-promo-img-wrap">*/}
                            {/*        <img*/}
                            {/*            src="/storage/categories/cut/poiasni-sumki-dizi-2.webp"*/}
                            {/*            alt="Картхолдери"*/}
                            {/*            className="category-promo-img"*/}
                            {/*        />*/}
                            {/*    </div>*/}
                            {/*    <div className="category-promo-info">*/}
                            {/*        <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">*/}
                            {/*            <span className="text-sm font-semibold tracking-wide">Картхолдери</span>*/}
                            {/*        </div>*/}
                            {/*        <span className="category-promo-link">Переглянути &rarr;</span>*/}
                            {/*    </div>*/}
                            {/*</a>*/}

                            {/* Карточка 5: Сумки */}
                            {/*<a href="#" className="category-promo-card category-promo-card-2 group">*/}
                            {/*    <div className="category-promo-img-wrap">*/}
                            {/*        <img*/}
                            {/*            src="/storage/categories/cut/skiriani-virobi-dlia-korporativnix-zamovlen.webp"*/}
                            {/*            alt="Сумки"*/}
                            {/*            className="category-promo-img"*/}
                            {/*        />*/}
                            {/*    </div>*/}
                            {/*    <div className="category-promo-info">*/}
                            {/*        <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">*/}
                            {/*            <span className="text-sm font-semibold tracking-wide">Сумки</span>*/}
                            {/*        </div>*/}
                            {/*        <span className="category-promo-link">Переглянути &rarr;</span>*/}
                            {/*    </div>*/}
                            {/*</a>*/}

                            {/* Карточка 6: Ремені */}
                            {/*<a href="#" className="category-promo-card group">*/}
                            {/*    <div className="category-promo-img-wrap">*/}
                            {/*        <img*/}
                            {/*            src="/storage/categories/cut/sumki-mi-mi-boxo.webp"*/}
                            {/*            alt="Ремені"*/}
                            {/*            className="category-promo-img"*/}
                            {/*        />*/}
                            {/*    </div>*/}
                            {/*    <div className="category-promo-info">*/}
                            {/*        <div className="flex items-center gap-2 text-[#e3d2c4] group-hover:text-[#b89742] transition-colors">*/}
                            {/*            <span className="text-sm font-semibold tracking-wide">Ремені</span>*/}
                            {/*        </div>*/}
                            {/*        <span className="category-promo-link">Переглянути &rarr;</span>*/}
                            {/*    </div>*/}
                            {/*</a>*/}

                        </div>
                    </div>
                </section>
            </div>



            <div className="promo-line">
                <ul>
                    <li>
                        <div className="flex">
                            <div className="promo-icon promo-sign"></div>
                            <span className="promo-text">
                                    Натуральна шкіра
                                    <i>Гарантія якості</i>
                                </span>
                        </div>
                    </li>
                    <li>
                        <div className="flex">
                            <div className="promo-icon promo-hand"></div>
                            <span className="promo-text">
                                    Ручна робота
                                    <i>Увага до деталей</i>
                                </span>
                        </div>
                    </li>
                    <li>
                        <div className="flex">
                            <div className="promo-icon promo-track"></div>
                            <span className="promo-text">
                                    Швидка доставка
                                    <i>Увага до деталей</i>
                                </span>
                        </div>
                    </li>
                    <li>
                        <div className="flex">
                            <div className="promo-icon promo-gift"></div>
                            <span className="promo-text">
                                    Система знижек
                                    <i>Знижки та акції</i>
                                </span>
                        </div>
                    </li>
                    <li></li>
                </ul>
            </div>
            <div className="white-content">
                <h1>Новинки</h1>
                <div className="products-grid">
                    {newProducts.map((product) => (
                        <ProductCard key={product.id} product={product} />
                    ))}
                </div>
            </div>
            <div className="b-sep" />
            <div className="flex pt-[15px] bg-[#f6f2ef]">
                <div className="new-product-text">
                    <h2>Оновлення каталогу</h2>
                    <span>Нові моделі, кольори та аксесуари шоб доповнити ваш стиль</span>
                    <a href="/catalog" className="more-link">Переглянути всі</a>
                </div>
                <div className="new-product-banner"></div>
            </div>
            {/*<img src="/public/images/home/page-separate.png"></img>*/}
            <div className="white-content">
                <h1>Оновлення каталогу</h1>
                <div className="products-grid">
                    {updatedProducts.map((product) => (
                        <ProductCard key={product.id} product={product} />
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
