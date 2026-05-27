import React from "react";

type Props = {
    count?: number;
    onClick?: () => void;
};

export function CartButton({ count = 0, onClick }: Props) {
    return (
        <button
            onClick={onClick}
            className="relative flex items-center gap-2"
        >
            <img
                src="/images/header/cart.svg"
                alt="cart"
                className="w-[42px] h-[42px] mt-[4px] ml-[10px]"
            />

            <div className="text-left leading-tight">
                {count > 0 ? (
                    <>
                        <div className="text-xs text-white font-medium">
                            Кошик
                        </div>
                        <div className="text-xs text-white">
                            {count} товар(ів)
                        </div>
                    </>
                ) : (
                    <div className="empty-cart">
                        Кошик порожній
                    </div>
                )}
            </div>
        </button>
    );
}
