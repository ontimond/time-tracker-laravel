import { DefaultProvider } from "@/types";

export const defaultProviders: {
    [key: string]: DefaultProvider;
} = {
    clockify: {
        name: "Clockify",
        slug: "clockify",
    },
    toggl: {
        name: "Toggl",
        slug: "toggl",
    },
};
