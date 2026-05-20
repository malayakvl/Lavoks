import React from "react";

type Props = {
    open: boolean;
    onClose: () => void;
};

export function CartDropdown({ open, onClose }: Props) {
    if (!open) return null;

    return (
        <div className="absolute right-0 top-full mt-2 w-[320px] bg-white shadow-xl rounded-lg border z-50">
            <div className="p-4 flex justify-between items-center border-b">
                <h3 className="font-semibold">Кошик</h3>
                <button onClick={onClose}>✕</button>
            </div>

            <div className="p-4 text-sm text-gray-500">
                Кошик порожній
            </div>
        </div>
    );
}
