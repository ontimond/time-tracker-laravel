import DangerButton from "@/Components/DangerButton";
import Modal from "@/Components/Modal";
import SecondaryButton from "@/Components/SecondaryButton";
import { Provider } from "@/types";
import { useForm } from "@inertiajs/react";
import { FormEventHandler, useState } from "react";

export default function DeleteProviderForm({
    className = "",
    provider,
}: {
    className?: string;
    provider: Provider;
}) {
    const [confirmingProviderrDeletion, setConfirmProviderDeletion] =
        useState(false);

    const { delete: destroy, processing, reset } = useForm();

    const confirmUserDeletion = () => {
        setConfirmProviderDeletion(true);
    };

    const deleteProvider: FormEventHandler = (e) => {
        e.preventDefault();

        destroy(
            route("providers.destroy", {
                provider: provider.id,
            }),
            {
                preserveScroll: true,
                onSuccess: () => closeModal(),
                onFinish: () => reset(),
            }
        );
    };

    const closeModal = () => {
        setConfirmProviderDeletion(false);

        reset();
    };

    return (
        <section className={`space-y-6 ${className}`}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    Delete Provider
                </h2>

                <p className="mt-1 text-sm text-gray-600">
                    Once the provider is deleted, all of its time entries will
                    be detached from this provider.
                </p>
            </header>

            <DangerButton onClick={confirmUserDeletion}>
                Delete Provider
            </DangerButton>

            <Modal show={confirmingProviderrDeletion} onClose={closeModal}>
                <form onSubmit={deleteProvider} className="p-6">
                    <h2 className="text-lg font-medium text-gray-900">
                        Are you sure you want to delete this provider?
                    </h2>

                    <p className="mt-1 text-sm text-gray-600">
                        Once the provider is deleted, all of its time entries
                        will be detached from this provider.
                    </p>

                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeModal}>
                            Cancel
                        </SecondaryButton>

                        <DangerButton className="ml-3" disabled={processing}>
                            Delete Provider
                        </DangerButton>
                    </div>
                </form>
            </Modal>
        </section>
    );
}
