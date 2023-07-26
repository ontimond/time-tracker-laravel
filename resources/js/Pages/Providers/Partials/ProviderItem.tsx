import { Provider } from "@/types";
import { Link } from "@inertiajs/react";
import { defaultProviders } from "../defaultProviders";

export default function ProviderItem({ provider }: { provider: Provider }) {
    const name = defaultProviders[provider.slug].name;

    return (
        <Link
            as="li"
            href={route("providers.edit", provider.id)}
            className="p-4 sm:p-8 cursor-pointer"
        >
            {name}
        </Link>
    );
}
