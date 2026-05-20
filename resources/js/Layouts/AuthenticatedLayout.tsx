import { ReactNode } from 'react';
import { usePage } from '@inertiajs/react';
import Header from '../Components/Header/Header';

type Props = {
    children: ReactNode;
    header?: ReactNode;
};

export default function AuthenticatedLayout({ children, header }: Props) {
    const { categories } = usePage().props;

    return (
        <div className="min-h-screen flex flex-col">

            {/* HEADER SLOT */}
            <Header categories={categories as any[]} />

            {/* MAIN CONTENT */}
            <main className="flex-1">
                <div className="max-w-7xl mx-auto px-4 py-6">
                    {children}
                </div>
            </main>

        </div>
    );
}
