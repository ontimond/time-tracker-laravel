import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import Select from "@/Components/Select";
import TextInput from "@/Components/TextInput";
import { Transition } from "@headlessui/react";
import { useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";
import { defaultProviders } from "../defaultProviders";

export default function CreateProviderForm({
    className,
}: {
    className?: string;
}) {
    const {
        data,
        setData,
        errors,
        post,
        reset,
        processing,
        recentlySuccessful,
    } = useForm({
        slug: defaultProviders.clockify.slug,
        config: {
            api_key: "",
            workspace_id: "",
        },
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route("providers.store"));
    };

    return (
        <section className={className}>
            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="slug" value="Slug" />
                    <Select
                        id="slug"
                        name="slug"
                        className="mt-1 block w-full"
                        options={Object.keys(defaultProviders).map(
                            (provider) => ({
                                value: defaultProviders[provider].slug,
                                label: defaultProviders[provider].name,
                            })
                        )}
                        value={data.slug}
                        onChange={(e) => setData("slug", e.target.value)}
                        required
                    />

                    <InputError className="mt-2" message={errors.slug} />
                </div>
                {defaultProviders.clockify.slug === data.slug && (
                    <>
                        <div>
                            <InputLabel
                                htmlFor="clockify_api_key"
                                value="Api key"
                            />
                            <TextInput
                                id="clockify_api_key"
                                value={data.config.api_key}
                                onChange={(e) =>
                                    setData("config", {
                                        ...data.config,
                                        api_key: e.target.value,
                                    })
                                }
                                type="text"
                                className="mt-1 block w-full"
                            />
                        </div>
                        <div>
                            <InputLabel
                                htmlFor="clockify_workspace_id"
                                value="Workspace Id"
                            />
                            <TextInput
                                id="clockify_workspace_id"
                                value={data.config.workspace_id}
                                onChange={(e) =>
                                    setData("config", {
                                        ...data.config,
                                        workspace_id: e.target.value,
                                    })
                                }
                                type="text"
                                className="mt-1 block w-full"
                            />
                        </div>
                    </>
                )}

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Save</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enterFrom="opacity-0"
                        leaveTo="opacity-0"
                        className="transition ease-in-out"
                    >
                        <p className="text-sm text-gray-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
