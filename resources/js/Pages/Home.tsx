import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';

export default function Home({ categories }) {
    return (
        <AuthenticatedLayout
            header={<h1 className="text-xl font-semibold">Home</h1>}
        >
            <Head title="Home" />


        </AuthenticatedLayout>
    );
}
