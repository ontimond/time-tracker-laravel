import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";

import { PageProps, Provider, TimeEntry } from "@/types";
import { Head } from "@inertiajs/react";
import CreateTimeEntryForm from "./Partials/CreateTimeEntryForm";
import TimeEntryList from "./Partials/TimeEntryList";

type IndexProps = {
    timeEntriesGroupedByDay: {
        [key: string]: TimeEntry[];
    };
    providers: Provider[];
};

export default function Index({
    auth,
    timeEntriesGroupedByDay,
    providers,
}: PageProps<IndexProps>) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Time entries
                </h2>
            }
        >
            <Head title="Time entries" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <section>
                            <header>
                                <h2 className="text-lg font-medium text-gray-900">
                                    Time entries
                                </h2>

                                <p className="mt-1 text-sm text-gray-600">
                                    Time entries are a way to track your time,
                                    and bind it to a third party service. This
                                    allows you to use the data in other
                                    applications.
                                </p>
                            </header>
                        </section>
                    </div>
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <CreateTimeEntryForm />
                    </div>
                    {Object.keys(timeEntriesGroupedByDay).map((day) => (
                        <div
                            key={day}
                            className="bg-gray-50 border border-gray-200 sm:rounded-lg"
                        >
                            <TimeEntryList
                                day={day}
                                timeEntries={timeEntriesGroupedByDay[day]}
                                providers={providers}
                            />
                        </div>
                    ))}
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
