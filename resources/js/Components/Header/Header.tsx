import * as React from "react";
import { NavigationMenu } from "radix-ui";
import classNames from "classnames";
import { HamburgerMenuIcon, Cross1Icon } from "@radix-ui/react-icons";
import { DigitalClock } from "./DigitalClock";
import { SearchInput } from "./SearchInput";
import { CartButton } from "./CartButton";
import { CartDropdown } from "./CartDropdown";

type Category = {
    id: number;
    image?: string;
    current_translation?: {
        title: string;
    };
    children?: Category[];
};

type Props = {
    categories: Category[];
};

const ListItem = React.forwardRef(
    ({ className, children, title, image, ...props }, forwardedRef) => {
        // Replace 'original' path with 'cut' path and change extension to .png
        const cutImage = image
            ? image.replace('categories/original/', 'categories/cut/').replace('.webp', '.webp')
            : null;

        return (
            <li>
                <NavigationMenu.Link asChild>
                    <a
                        className={classNames("ListItemLink", className)}
                        {...props}
                        ref={forwardedRef}
                    >
                        <div className="image-menu-link flex gap-4">
                            <div className="image-div-cat">
                                {cutImage && (
                                    <img
                                        src={`/storage/${cutImage}`}
                                        alt={title}
                                        className="ListItemImage"
                                    />
                                )}
                            </div>
                            <div className="text-content relative">
                                <span className="ListItemTextNew cat-title">{children}</span>
                                {/*<span className="cat-description">Тонкі компактні картхолдери на любий смак</span>*/}
                                {/* Ссылку "Переглянути →" можно зашить прямо сюда в структуру, как на скетче */}
                                <span className="view-link-arrow">Переглянути &rarr;</span>
                            </div>
                        </div>
                    </a>
                </NavigationMenu.Link>
            </li>
        );
    },
);

export default function Header({ categories }: Props) {
    const [mobileMenuOpen, setMobileMenuOpen] = React.useState(false);
    const [cartOpen, setCartOpen] = React.useState(false);
    const [cartCount, setCartCount] = React.useState(0);

    React.useEffect(() => {
        const handler = () => setCartOpen(false);
        document.addEventListener("click", handler);
        return () => document.removeEventListener("click", handler);
    }, []);

    return (
        <header>
            <div className="w-full top-line">
                <div className="w-full max-w-[1400px] mx-auto px-6 h-10 flex items-center justify-between text-xs text-[#f5f5f3]/80">

                    {/* Левая сторона: Обслуживание и телефон */}
                    <div className="flex items-center gap-2">
                        <span className="text-[#b89742]/70 font-medium tracking-wider uppercase text-[10px]">Обслуговування клієнтів:</span>
                        <a href="tel:+380934430872" className="text-[#f5f5f3] font-semibold hover:text-[#d4af37] transition-colors">
                            +(380) 93 443 08 72
                        </a>
                    </div>

                    {/* Центр: Иконки соцсетей */}
                    <ul className="flex items-center gap-4 filter">
                        <li>
                            <a
                                href="https://www.facebook.com/lavoksleather/"
                                target="_blank"
                                className="group flex items-center justify-center transition-transform duration-200 hover:scale-110"
                            >
                                <img
                                    src="/images/header/facebook.png"
                                    alt="Facebook"
                                    className="gold-social-icon"
                                />
                            </a>
                        </li>
                        <li>
                            <a href="https://www.youtube.com/channel/UC1Uhf6JBo3xTDkQvk0yh5Hw" target="_blank" className="opacity-80 hover:opacity-100 transition-opacity">
                                <img
                                    src="/images/header/youtube.png"
                                    alt="Facebook"
                                    className="gold-social-icon"
                                />
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/lavoks.leather/" target="_blank" className="opacity-80 hover:opacity-100 transition-opacity">
                                <img
                                    src="/images/header/insta.png"
                                    alt="Facebook"
                                    className="gold-social-icon"
                                />
                            </a>
                        </li>
                        <li>
                            <a href="https://www.tiktok.com/@lavoks.leather" target="_blank" className="opacity-80 hover:opacity-100 transition-opacity">
                                <img
                                    src="/images/header/tiktok.svg"
                                    alt="Facebook"
                                    className="gold-social-icon"
                                />
                            </a>
                        </li>
                    </ul>

                    {/* Правая сторона: Статичное меню страниц + Переключатель языков */}
                    <div className="flex items-center gap-6 font-medium">
                        <nav className="flex items-center gap-4 border-r border-[#f5f5f3]/10 pr-6">
                            <a href="https://lavoks.com/page/about-us" className="hover:text-[#d4af37] transition-colors">Про нас</a>
                            <a href="https://lavoks.com/page/delivery" className="hover:text-[#d4af37] transition-colors">Оплата та доставка</a>
                            <a href="https://lavoks.com/page/contact" className="hover:text-[#d4af37] transition-colors">Контакти</a>
                            <a href="https://lavoks.com/login" className="hover:text-[#d4af37] transition-colors flex items-center gap-1">
                                <span>Кабінет</span>
                            </a>
                        </nav>

                        {/* Языковой блок */}
                        <div className="flex items-center gap-2 text-[11px] font-bold tracking-wider uppercase">
                            <span className="text-[#d4af37] cursor-default bg-[#1b1009] px-1.5 py-0.5 rounded border border-[#d4af37]/30 shadow-inner">Укр</span>
                            <span className="text-[#f5f5f3]/40 cursor-pointer hover:text-[#f5f5f3] transition-colors px-1">Рус</span>
                        </div>
                    </div>

                </div>
            </div>

            <div className="w-full max-w-[1100px] mx-auto px-6 py-1 flex items-center justify-between gap-12">
                {/* Логотип слева */}
                <div className="flex-shrink-0">
                    <img src="/images/header/t-logo.png" alt="Lavoks" className="w-[200px] h-auto" />
                    <DigitalClock />
                </div>

                {/* Поиск по центру */}
                <div className="flex-1 max-w-2xl">
                    <SearchInput onSearch={(v) => console.log("search:", v)} />
                </div>

                {/* Корзина справа */}
                <div className="relative flex-shrink-0 cart-button-block">
                    {/*<button className="leather-button">*/}
                    {/*    <div className="icon-container">*/}
                    {/*        <svg width="30" height="26" viewBox="0 0 30 26" fill="none"*/}
                    {/*             xmlns="http://www.w3.org/2000/svg">*/}
                    {/*            <path d="M1 1H5.5L8.5 16.5H24.5L27.5 5.5H7.5" stroke="#D4AF37" stroke-width="2"*/}
                    {/*                  stroke-linecap="round" stroke-linejoin="round"/>*/}
                    {/*            <circle cx="10" cy="22" r="2" fill="#D4AF37" stroke="#D4AF37" stroke-width="2"/>*/}
                    {/*            <circle cx="23" cy="22" r="2" fill="#D4AF37" stroke="#D4AF37" stroke-width="2"/>*/}
                    {/*        </svg>*/}
                    {/*    </div>*/}
                    {/*</button>*/}
                    <CartButton
                        count={cartCount}
                        onClick={() => setCartOpen((v) => !v)}
                    />

                    <CartDropdown
                        open={cartOpen}
                        onClose={() => setCartOpen(false)}
                    />
                </div>

                {/* Mobile burger button */}
                <button
                    className="md:hidden text-white p-2"
                    onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                    aria-label="Toggle menu"
                >
                    {mobileMenuOpen ? (
                        <Cross1Icon className="w-6 h-6" />
                    ) : (
                        <HamburgerMenuIcon className="w-6 h-6" />
                    )}
                </button>
            </div>

            {/* Desktop Navigation */}
            <div className="w-full mx-auto px-0 py-[0px] hidden md:block ">
                <NavigationMenu.Root className="NavigationMenuRoot">
                    <NavigationMenu.List className="NavigationMenuList sub-menu">
                        {/*<a href="#" className="text-[#e5c158] hover:text-white transition-colors drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)] flex items-center gap-1">*/}
                        {/*    🔥 Знижки*/}
                        {/*</a>*/}
                        {categories.map((cat) => (
                            <NavigationMenu.Item key={cat.id}>
                                {cat.children && cat.children.length > 0 ? (
                                    <>
                                        <NavigationMenu.Trigger className="NavigationMenuTrigger">
                                            <span className="parent-menu">{cat.current_translation?.title}</span>
                                        </NavigationMenu.Trigger>

                                        <NavigationMenu.Content className="NavigationMenuContent">
                                            <div className="dropdown-mega-menu dropdown-layout">
                                                {/* Левый сайдбар под стиль макета Figma */}
                                                <div className="dropdown-sidebar">
                                                    <h3 className="sidebar-title">Категорії</h3>
                                                    <ul className="sidebar-list">
                                                        <li>
                                                            <a href={`/category/${cat.id}`} className="sidebar-link active">
                                                                Всі {cat.current_translation?.title}
                                                                <span className="cat-count">{cat.children?.length || 0}</span>
                                                            </a>
                                                        </li>
                                                        {cat.children.map((child) => (
                                                            <li key={child.id}>
                                                                <a href={`/category/${child.id}`} className="sidebar-link">
                                                                    {child.current_translation?.title}
                                                                </a>
                                                            </li>
                                                        ))}
                                                    </ul>
                                                </div>


                                                {/* Правая контентная область со слайдером - 2 ряда по 3 карточки */}
                                                <div className="dropdown-carousel-wrapper">
                                                    <h3 className="carousel-section-title">Моделі</h3>

                                                    {/* Слайдер */}
                                                    <ul className="dropdown-carousel-track">
                                                        {(() => {
                                                            const slides = [];
                                                            const maxItemsPerSlide = 9;
                                                            const totalItems = cat.children.length;

                                                            // Розподіляємо по 9 елементів на слайд
                                                            let currentIndex = 0;
                                                            let slideIndex = 0;

                                                            while (currentIndex < totalItems) {
                                                                const remainingItems = totalItems - currentIndex;

                                                                // Якщо залишається 1 елемент і це не перший слайд - додаємо на попередній
                                                                if (remainingItems === 1 && slideIndex > 0) {
                                                                    // Останній елемент вже доданий на попередній слайд (дивись нижче)
                                                                    break;
                                                                }

                                                                // Беремо максимум 9 елементів
                                                                let slideSize = Math.min(maxItemsPerSlide, remainingItems);

                                                                // Якщо після цього залишиться 1 елемент - беремо 10 замість 9
                                                                if (remainingItems - maxItemsPerSlide === 1 && remainingItems > maxItemsPerSlide) {
                                                                    slideSize = maxItemsPerSlide + 1;
                                                                }

                                                                const slideItems = cat.children.slice(currentIndex, currentIndex + slideSize);
                                                                currentIndex += slideSize;

                                                                slides.push(
                                                                    <li key={slideIndex} className="carousel-slide">
                                                                        {slideItems.map((child) => (
                                                                            <ListItem
                                                                                key={child.id}
                                                                                href={`/category/${child.id}`}
                                                                                image={child.image}
                                                                            >
                                                                                <span className="card-title-class">{child.current_translation?.title || ''}</span>
                                                                            </ListItem>
                                                                        ))}
                                                                    </li>
                                                                );

                                                                slideIndex++;
                                                            }

                                                            return slides;
                                                        })()}
                                                    </ul>

                                                    {/* Точки пагинации */}
                                                    {cat.children.length > 9 && (
                                                        <div className="dropdown-pagination-dots">
                                                            {(() => {
                                                                const totalItems = cat.children.length;
                                                                const maxItemsPerSlide = 9;

                                                                // Рахуємо скільки буде слайдів з новою логікою
                                                                let slideCount = 0;
                                                                let currentIndex = 0;
                                                                while (currentIndex < totalItems) {
                                                                    const remainingItems = totalItems - currentIndex;

                                                                    if (remainingItems === 1 && slideCount > 0) {
                                                                        break;
                                                                    }

                                                                    let slideSize = Math.min(maxItemsPerSlide, remainingItems);
                                                                    if (remainingItems - maxItemsPerSlide === 1 && remainingItems > maxItemsPerSlide) {
                                                                        slideSize = maxItemsPerSlide + 1;
                                                                    }

                                                                    currentIndex += slideSize;
                                                                    slideCount++;
                                                                }

                                                                return Array.from({ length: slideCount }).map((_, index) => (
                                                                    <button
                                                                        key={index}
                                                                        className={`pagination-dot ${index === 0 ? 'active' : ''}`}
                                                                        onClick={(e) => {
                                                                            const track = e.currentTarget.closest('.dropdown-carousel-wrapper').querySelector('.dropdown-carousel-track');
                                                                            if (track) {
                                                                                const slideWidth = track.offsetWidth;
                                                                                track.scrollTo({
                                                                                    left: slideWidth * index,
                                                                                    behavior: 'smooth'
                                                                                });

                                                                                // Обновляем активную точку
                                                                                track.closest('.dropdown-carousel-wrapper')
                                                                                    .querySelectorAll('.pagination-dot')
                                                                                    .forEach((dot, i) => {
                                                                                        dot.classList.toggle('active', i === index);
                                                                                    });
                                                                            }
                                                                        }}
                                                                    />
                                                                ));
                                                            })()}
                                                        </div>
                                                    )}
                                                </div>
                                            </div>
                                        </NavigationMenu.Content>
                                    </>
                                ) : (
                                    <NavigationMenu.Link
                                        className="NavigationMenuLink"
                                        href={`/category/${cat.id}`}
                                    >
                                        {cat.current_translation?.title}
                                    </NavigationMenu.Link>
                                )}
                            </NavigationMenu.Item>
                        ))}

                        <NavigationMenu.Indicator className="NavigationMenuIndicator">
                            <div className="Arrow" />
                        </NavigationMenu.Indicator>
                    </NavigationMenu.List>

                    <div className="ViewportPosition">
                        <NavigationMenu.Viewport className="NavigationMenuViewport" />
                    </div>
                </NavigationMenu.Root>
            </div>

            {/* Mobile Navigation */}
            {mobileMenuOpen && (
                <div className="md:hidden bg-black bg-opacity-95 fixed inset-0 z-50 pt-20 px-4 overflow-y-auto">
                    <nav className="space-y-2">
                        {categories.map((cat) => (
                            <div key={cat.id} className="border-b border-gray-700 pb-2">
                                {cat.children && cat.children.length > 0 ? (
                                    <details className="group">
                                        <summary className="text-white text-sm font-medium py-2 cursor-pointer list-none">
                                            {cat.current_translation?.title}
                                        </summary>
                                        <ul className="pl-4 pb-2 space-y-1">
                                            {cat.children.map((child) => (
                                                <li key={child.id}>
                                                    <a
                                                        href={`/category/${child.id}`}
                                                        className="text-gray-300 text-sm block py-1"
                                                    >
                                                        {child.current_translation?.title}
                                                    </a>
                                                </li>
                                            ))}
                                        </ul>
                                    </details>
                                ) : (
                                    <a
                                        href={`/category/${cat.id}`}
                                        className="text-white text-sm font-medium py-2 block"
                                    >
                                        {cat.current_translation?.title}
                                    </a>
                                )}
                            </div>
                        ))}
                    </nav>
                </div>
            )}
        </header>
    );
}
