import React from "react";

type Props = {
    product?: any;
    onClick?: () => void;
};

export function ProductCard({ product }: Props) {
    return (
        <>
            <div className="product-card" data-id={product.id}>
                <div className="product-image">
                    <img src={`http://localhost:8000/storage/${product.image}`} alt={product.name} />
                </div>
                <div className="product-content">
                    <h3 className="product-card-title">{product.name}</h3>
                    <span>{product.code}</span>
                    <div className="product-card-price">{product.price}</div>
                </div>
            </div>
        </>
    );
}
