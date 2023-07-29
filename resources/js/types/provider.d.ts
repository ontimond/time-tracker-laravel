export interface Provider {
    id: number;
    slug: string;
    config: any;
}

export interface DefaultProvider {
    name: string;
    slug: string;
    color: string;
    icon: string;
}
