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
        <div className="relative w-full max-w-[500px] p-[6px] rounded-full">

            {/* Пунктирный шов вокруг инпута */}
            <div className="" />

            {/* Основное тело инпута с двойным золотым кантом и глубокой внутренней тенью */}
            <div className="flex items-center w-full bg-[#1b1009] border-2 border-[#d4a373] rounded-full px-4 py-2 shadow-[inset_0_6px_12px_rgba(0,0,0,0.9)] focus-within:border-[#d4af37] focus-within:shadow-[inset_0_4px_10px_rgba(0,0,0,0.9),_0_0_15px_rgba(212,175,55,0.3)] transition-all">

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
