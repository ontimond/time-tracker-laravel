import { TimeEntry } from "@/types";
import TimeEntryItem from "./TimeEntryItem";
import {
    calculateTotalDurationToHms,
    isSomeEntryRunning,
    parseToCalendarFromNow,
} from "./utils";

export default function TimeEntryList({
    timeEntries,
    className = "",
    day,
}: {
    className?: string;
    timeEntries: TimeEntry[];
    day: string;
}) {
    const totalDurationHms = calculateTotalDurationToHms(timeEntries);
    const dayFromNow = parseToCalendarFromNow(day);
    const isRunning = isSomeEntryRunning(timeEntries);

    return (
        <section className={"divide-y" + className}>
            <header className="flex divide-x py-5 px-4 sm:px-8">
                <h2 className="font-bold pr-3">{dayFromNow}</h2>
                <p
                    className={
                        "pl-3 " +
                        (isRunning
                            ? "text-blue-500 font-bold animate-pulse"
                            : "")
                    }
                >
                    {totalDurationHms}
                </p>
            </header>
            <ul
                className={
                    "flex flex-col space-y-4 py-5 px-4 sm:px-8" + className
                }
            >
                {timeEntries.map((timeEntry) => (
                    <TimeEntryItem key={timeEntry.id} timeEntry={timeEntry} />
                ))}
            </ul>
        </section>
    );
}
