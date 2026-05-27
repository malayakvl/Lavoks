import React from "react";
import { MagnifyingGlassIcon } from "@radix-ui/react-icons";

type Props = {
    onSearch?: (value: string) => void;
};

export function SearchInput({ onSearch }: Props) {
    const [value, setValue] = React.useState("");

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const val = e.target.value;
        setValue(val);
        onSearch?.(val);
    };

    return (
        <div className="relative w-full p-[6px] ">

            {/* Пунктирный шов вокруг инпута */}
            <div className="" />

            {/* Основное тело инпута с двойным золотым кантом и глубокой внутренней тенью */}
            <div className="top-search-input">

                {/* Иконка лупы в золотом цвете с легким объемом */}
                <MagnifyingGlassIcon className="w-5 h-5 text-[#d4a373]  shrink-0" />

                {/* Текст ввода, мимикрирующий под тиснение */}
                <input
                    value={value}
                    onChange={handleChange}
                    placeholder="Пошук товарів..."
                    className="w-full ml-3 bg-transparent outline-none text-base text-[#d4af37] font-medium min-h-[36px] placeholder-[#876242] drop-shadow-[0_1px_1px_rgba(0,0,0,0.5)]"
                />
            </div>
        </div>
    );
}
