import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

import { Head } from "@inertiajs/react";
import { PageProps, Provider } from "@/types";
import CreateProviderForm from "./Partials/CreateProviderForm";
import UpdateProviderForm from "./Partials/UpdateProviderForm";
import DeleteProviderForm from "./Partials/DeleteProviderForm";

export default function Edit({
    auth,
    provider,
}: PageProps<{ provider: Provider }>) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Create provider
                </h2>
            }
        >
            <Head title="Providers" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <UpdateProviderForm provider={provider} />
                    </div>
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <DeleteProviderForm
                            className="max-w-xl"
                            provider={provider}
                        />
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
