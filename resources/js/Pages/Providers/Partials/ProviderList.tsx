import { Provider } from "@/types";
import ProviderItem from "./ProviderItem";

export default function ProviderList({ providers }: { providers: Provider[] }) {
    return (
        <ul className="divide-y">
            {providers.map((provider) => (
                <ProviderItem key={provider.id} provider={provider} />
            ))}
        </ul>
    );
}
