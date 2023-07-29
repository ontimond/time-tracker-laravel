import { Provider, TimeEntry } from "@/types";
import moment from "moment";
import { useEffect, useState } from "react";
import TimeEntryItem from "./TimeEntryItem";
import {
    calculateDifferenceFromNow,
    calculateTotalDurationToHms,
    isEntryRunning,
    isSomeEntryRunning,
    parseToCalendarFromNow,
} from "./utils";
import React from "react";

function TimeEntryList({
    timeEntries: _timeEntries,
    providers,
    className = "",
    day,
}: {
    className?: string;
    timeEntries: TimeEntry[];
    providers: Provider[];
    day: string;
}) {
    const [timeEntries, setTimeEntries] = useState(_timeEntries);
    const [totalDurationHms, setTotalDurationHms] = useState(
        calculateTotalDurationToHms(timeEntries)
    );
    const dayFromNow = parseToCalendarFromNow(day);
    const isRunning = isSomeEntryRunning(timeEntries);

    useEffect(() => {
        setTimeEntries(_timeEntries);
    }, [_timeEntries]);

    return (
        <section className={"divide-y" + className}>
            <header className="flex divide-x py-5 px-4 sm:px-8">
                <h2 className="font-bold pr-3">{dayFromNow}</h2>
                <p
                    className={
                        "pl-3 " + (isRunning ? "text-blue-500 font-bold" : "")
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
                    <TimeEntryItem
                        key={timeEntry.id}
                        timeEntry={timeEntry}
                        providers={providers}
                    />
                ))}
            </ul>
        </section>
    );
}

export default React.memo(TimeEntryList);
