import React from "react";

type Props = {
    product?: any;
    onClick?: () => void;
};

export function ProductCard({ product }: Props) {
    return (
        <>
            <div className="product-card" data-id={product.id}>
                {/* Контейнер для фото + бейджі статусів */}
                <div className="product-image relative">

                    {/* Блок з фірмовими галочками-іконками */}
                    <div className="absolute top-3 left-3 flex flex-col gap-2 z-10">
                        {/* 1. Нова модель */}
                        {product.id && (
                            <div className="status-badge" title="Нова модель">
                                <svg
                                    className="w-[24px] h-[24px]"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <defs>
                                        {/* Шляхетний металевий градієнт: від платини до глибокого графіту */}
                                        <linearGradient id="metalGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stopColor="#FFFFFF" stopOpacity="0.9" />
                                            <stop offset="30%" stopColor="#A1A1AA" />
                                            <stop offset="70%" stopColor="#3F3F46" />
                                            <stop offset="100%" stopColor="#18181B" />
                                        </linearGradient>

                                        {/* Тінь для створення 3D ефекту між деталями */}
                                        <filter id="metalShadow" x="-10%" y="-10%" width="120%" height="120%">
                                            <feDropShadow dx="0" dy="1" stdDeviation="0.5" floodColor="#000000" floodOpacity="0.5" />
                                        </filter>
                                    </defs>

                                    {/* Зовнішнє металеве захисне кільце/орбіта */}
                                    <circle
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="url(#metalGrad)"
                                        strokeWidth="1.2"
                                        strokeOpacity="0.7"
                                        fill="rgba(24, 24, 27, 0.2)"
                                    />

                                    {/* Об'ємна геометрична літера М (модель/модерн) з гострими гранями */}
                                    <g filter="url(#metalShadow)">
                                        {/* Ліва грань */}
                                        <path
                                            d="M5 17L8 7L12 13L10.5 17H5Z"
                                            fill="url(#metalGrad)"
                                            stroke="#27272A"
                                            strokeWidth="0.5"
                                        />
                                        {/* Права грань (віддзеркалена для об'єму) */}
                                        <path
                                            d="M19 17L16 7L12 13L13.5 17H19Z"
                                            fill="url(#metalGrad)"
                                            stroke="#18181B"
                                            strokeWidth="0.5"
                                        />
                                        {/* Центральний гострий шип/стрілка компаса */}
                                        <path
                                            d="M12 5L14 11L12 13L10 11L12 5Z"
                                            fill="url(#metalGrad)"
                                            opacity="0.9"
                                        />
                                    </g>

                                    {/* Делікатні крапки по осях (як на преміальних годинниках чи компасах) */}
                                    <circle cx="12" cy="3.5" r="0.7" fill="#FFFFFF" opacity="0.8" />
                                    <circle cx="12" cy="20.5" r="0.7" fill="#A1A1AA" opacity="0.6" />
                                    <circle cx="3.5" cy="12" r="0.7" fill="#A1A1AA" opacity="0.6" />
                                    <circle cx="20.5" cy="12" r="0.7" fill="#A1A1AA" opacity="0.6" />
                                </svg>
                            </div>
                        )}

                        {/* 2. Нова шкіра (твоя улюблена іконка шкури з тисненням) */}
                        {product.id && (
                            <div className="status-badge" title="Нова шкіра">
                                <svg
                                    className="w-[18px] h-[18px] stroke-stone-600 fill-none stroke-[1.2]"
                                    viewBox="0 0 24 24"
                                >
                                    {/* Контур натуральної шкіри */}
                                    <path d="M12 2C9 3.5 6 2 5 4C4 6 5 8 3 10C1 12 2 15 4 16C5 18 4 20 6 21C8 22 10 20.5 12 22C14 20.5 16 22 18 21C20 20 19 18 20 16C22 15 23 12 21 10C19 8 20 6 19 4C18 2 15 3.5 12 2Z" />
                                    {/* Внутрішня перфорація/пунктир як на скріншоті */}
                                    <path d="M12 5C10 6.2 7.5 5 6.7 6.7C6 8.2 6.7 9.8 5.2 11.2C3.7 12.7 4.5 15 6 15.7C6.7 17.2 6 18.8 7.5 19.5C9 20.2 10.5 19 12 20.2M12 5C14 6.2 16.5 5 17.3 6.7C18 8.2 17.3 9.8 18.8 11.2" strokeDasharray="2 2" />
                                </svg>
                            </div>
                        )}

                        {/* 3. Новий колір */}
                        {product.id && (
                            <div className="status-badge" title="Новий колір">
                                <svg
                                    className="w-[18px] h-[18px] stroke-stone-600 fill-none stroke-[1.5]"
                                    viewBox="0 0 24 24"
                                >
                                    {/* Елегантна крапля кольору / палітра */}
                                    <path d="M12 22C16.4183 22 20 18.4183 20 14C20 8 12 2 12 2C12 2 4 8 4 14C4 18.4183 7.58172 22 12 22Z" />
                                    <path d="M12 18C13.6569 18 15 16.6569 15 15C15 13.5 13.5 12 12 12" strokeDasharray="1.5 1.5" />
                                </svg>
                            </div>
                        )}
                    </div>

                    <img src={`http://localhost:8000/storage/${product.image}`} alt={product.name} />
                </div>

                <div className="product-content">
                    <h3 className="product-card-title">{product.name}</h3>
                    {product.leathers && product.leathers.length > 0 && (
                        <div className="product-leathers">
                            {product.leathers.map((leather: any, index: number) => (
                                <div key={index} className="leather-badge">
                                    {leather.image ? (
                                        <img
                                            src={`/storage/${leather.image.startsWith('storage/') ? leather.image.replace('storage/', '') : leather.image}`}
                                            alt={leather.title}
                                            className="leather-image"
                                        />
                                    ) : (
                                        <div className="leather-image-placeholder">🎨</div>
                                    )}
                                    <span className="leather-title">{leather.title}</span>
                                </div>
                            ))}
                        </div>
                    )}
                    <span className="product-card-sku">{product.code}</span>
                    <div className="product-card-price">{product.price} грн</div>
                    <div className={"product-card-actions"}>
                        <a className={"add-to-cart"} href={`/products/${product.id}`}>
                            <span>Додати у кошик</span>
                        </a>
                    </div>
                </div>
            </div>
        </>
    );
}
