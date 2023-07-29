import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import PrimaryButton from "@/Components/PrimaryButton";
import Select from "@/Components/Select";
import TextInput from "@/Components/TextInput";
import { Provider } from "@/types";
import { Transition } from "@headlessui/react";
import { useForm } from "@inertiajs/react";
import { FormEventHandler } from "react";
import { defaultProviders } from "../defaultProviders";

export default function UpdateProviderForm({
    className,
    provider,
}: {
    className?: string;
    provider: Provider;
}) {
    const {
        data,
        setData,
        errors,
        patch,
        reset,
        processing,
        recentlySuccessful,
    } = useForm({
        slug: provider.slug,
        config: provider.config,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route("providers.update", { provider: provider.id }));
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
                        <div>
                            <InputLabel
                                htmlFor="clockify_project_id"
                                value="Project Id"
                            />
                            <TextInput
                                id="clockify_project_id"
                                value={data.config.project_id}
                                onChange={(e) =>
                                    setData("config", {
                                        ...data.config,
                                        project_id: e.target.value,
                                    })
                                }
                                type="text"
                                className="mt-1 block w-full"
                            />
                        </div>
                    </>
                )}

                {defaultProviders.toggl.slug === data.slug && (
                    <>
                        <div>
                            <InputLabel
                                htmlFor="toggl_api_token"
                                value="Api token"
                            />
                            <TextInput
                                id="toggl_api_token"
                                value={data.config.api_token}
                                onChange={(e) =>
                                    setData("config", {
                                        ...data.config,
                                        api_token: e.target.value,
                                    })
                                }
                                type="text"
                                className="mt-1 block w-full"
                            />
                        </div>
                        <div>
                            <InputLabel
                                htmlFor="toggl_workspace_id"
                                value="Workspace Id"
                            />
                            <TextInput
                                id="toggl_workspace_id"
                                value={data.config.workspace_id}
                                onChange={(e) =>
                                    setData("config", {
                                        ...data.config,
                                        workspace_id: parseInt(e.target.value),
                                    })
                                }
                                type="text"
                                className="mt-1 block w-full"
                            />
                        </div>
                        <div>
                            <InputLabel
                                htmlFor="toggl_project_id"
                                value="Project Id"
                            />
                            <TextInput
                                id="toggl_project_id"
                                value={data.config.project_id}
                                onChange={(e) =>
                                    setData("config", {
                                        ...data.config,
                                        project_id: parseInt(e.target.value),
                                    })
                                }
                                type="text"
                                className="mt-1 block w-full"
                            />
                        </div>
                    </>
                )}

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Update</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enterFrom="opacity-0"
                        leaveTo="opacity-0"
                        className="transition ease-in-out"
                    >
                        <p className="text-sm text-gray-600">Updated.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
