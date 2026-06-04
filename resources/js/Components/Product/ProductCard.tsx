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
                        {product.new_model && (
                            <span className="badge badge-new">Новинка</span>
                            // <div className="status-badge" title="Нова модель">
                            //     <svg
                            //         className="w-[24px] h-[24px]"
                            //         viewBox="0 0 24 24"
                            //         fill="none"
                            //         xmlns="http://www.w3.org/2000/svg"
                            //     >
                            //         <defs>
                            //             {/* Шляхетний металевий градієнт: від платини до глибокого графіту */}
                            //             <linearGradient id="metalGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                            //                 <stop offset="0%" stopColor="#FFFFFF" stopOpacity="0.9" />
                            //                 <stop offset="30%" stopColor="#A1A1AA" />
                            //                 <stop offset="70%" stopColor="#3F3F46" />
                            //                 <stop offset="100%" stopColor="#18181B" />
                            //             </linearGradient>
                            //
                            //             {/* Тінь для створення 3D ефекту між деталями */}
                            //             <filter id="metalShadow" x="-10%" y="-10%" width="120%" height="120%">
                            //                 <feDropShadow dx="0" dy="1" stdDeviation="0.5" floodColor="#000000" floodOpacity="0.5" />
                            //             </filter>
                            //         </defs>
                            //
                            //         {/* Зовнішнє металеве захисне кільце/орбіта */}
                            //         <circle
                            //             cx="12"
                            //             cy="12"
                            //             r="10"
                            //             stroke="url(#metalGrad)"
                            //             strokeWidth="1.2"
                            //             strokeOpacity="0.7"
                            //             fill="rgba(24, 24, 27, 0.2)"
                            //         />
                            //
                            //         {/* Об'ємна геометрична літера М (модель/модерн) з гострими гранями */}
                            //         <g filter="url(#metalShadow)">
                            //             {/* Ліва грань */}
                            //             <path
                            //                 d="M5 17L8 7L12 13L10.5 17H5Z"
                            //                 fill="url(#metalGrad)"
                            //                 stroke="#27272A"
                            //                 strokeWidth="0.5"
                            //             />
                            //             {/* Права грань (віддзеркалена для об'єму) */}
                            //             <path
                            //                 d="M19 17L16 7L12 13L13.5 17H19Z"
                            //                 fill="url(#metalGrad)"
                            //                 stroke="#18181B"
                            //                 strokeWidth="0.5"
                            //             />
                            //             {/* Центральний гострий шип/стрілка компаса */}
                            //             <path
                            //                 d="M12 5L14 11L12 13L10 11L12 5Z"
                            //                 fill="url(#metalGrad)"
                            //                 opacity="0.9"
                            //             />
                            //         </g>
                            //
                            //         {/* Делікатні крапки по осях (як на преміальних годинниках чи компасах) */}
                            //         <circle cx="12" cy="3.5" r="0.7" fill="#FFFFFF" opacity="0.8" />
                            //         <circle cx="12" cy="20.5" r="0.7" fill="#A1A1AA" opacity="0.6" />
                            //         <circle cx="3.5" cy="12" r="0.7" fill="#A1A1AA" opacity="0.6" />
                            //         <circle cx="20.5" cy="12" r="0.7" fill="#A1A1AA" opacity="0.6" />
                            //     </svg>
                            // </div>
                        )}

                        {/* 2. Нова шкіра (твоя улюблена іконка шкури з тисненням) */}
                        {product.new_leather && (
                            <span className="badge new-leather">Нова шкіра</span>
                        )}

                        {/* 3. Новий колір */}
                        {product.new_color && (
                            <span className="badge new-color">Новий колір</span>
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
