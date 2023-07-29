import { DefaultProvider } from "@/types";

export const defaultProviders: {
    [key: string]: DefaultProvider;
} = {
    clockify: {
        name: "Clockify",
        slug: "clockify",
        color: "bg-blue-50",
        icon: "/icons/clockify.png",
    },
    toggl: {
        name: "Toggl",
        slug: "toggl",
        color: "bg-red-50",
        icon: "/icons/toggl.webp",
    },
};
