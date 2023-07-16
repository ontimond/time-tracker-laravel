import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

import { Head, Link } from "@inertiajs/react";
import { PageProps } from "@/types";
import PrimaryButton from "@/Components/PrimaryButton";

export default function Index({
    auth,
    providers,
}: PageProps<{ providers: any[] }>) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Providers
                </h2>
            }
        >
            <Head title="Providers" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <section>
                            <header>
                                <h2 className="text-lg font-medium text-gray-900">
                                    Providers
                                </h2>

                                <p className="mt-1 text-sm text-gray-600">
                                    Providers are a way to bind your time
                                    tracking data to a third party service. This
                                    allows you to use the data in other
                                    applications.
                                </p>
                            </header>
                            <Link href={route("providers.create")}>
                                <PrimaryButton>Create</PrimaryButton>
                            </Link>
                        </section>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
