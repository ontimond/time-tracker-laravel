import { Provider } from ".";

export interface TimeEntry {
    id: number;
    description?: string;
    start: string;
    stop?: string;
    billable: boolean;
    user_id: number;
    duration: number;
    providers: (Provider & {
        pivot: {
            data: any;
        };
    })[];
}
