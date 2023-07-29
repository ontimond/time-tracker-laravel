import DangerButton from "@/Components/DangerButton";
import Dropdown from "@/Components/Dropdown";
import Popover from "@/Components/Popover";
import SecondaryButton from "@/Components/SecondaryButton";
import TextInput from "@/Components/TextInput";
import { Provider, TimeEntry } from "@/types";
import { useForm } from "@inertiajs/react";
import {
    calculateDifferenceFromNowToHms,
    calculateDifferenceToHms,
    isEntryRunning,
    parseDateToLt,
    parseDateToLtOrNow,
    parseSecondsToHms,
} from "./utils";
import moment from "moment";
import { useEffect, useState } from "react";
import React from "react";
import PrimaryButton from "@/Components/PrimaryButton";

type TimeEntryProps = {
    timeEntry: TimeEntry;
    providers: Provider[];
};

function TimEntry({ timeEntry, providers }: TimeEntryProps) {
    const [startInLt, setStartInLt] = useState(parseDateToLt(timeEntry.start));
    const [stopInLtOrNow, setStopInLtOrNow] = useState(
        parseDateToLtOrNow(timeEntry.stop)
    );
    const [duration, setDuration] = useState(
        parseSecondsToHms(timeEntry.duration)
    );
    const [isRunning, setIsRunning] = useState(isEntryRunning(timeEntry));
    const interval = React.useRef<any>();

    const {
        data,
        setData,
        transform,
        isDirty,
        reset,
        setDefaults,
        delete: destroy,
        patch,
        post,
    } = useForm({
        description: timeEntry.description,
        start: timeEntry.start,
        stop: timeEntry.stop,
    });

    // Transform dates to ISO format and UTC timezone
    transform((data) => ({
        ...data,
        start: moment(data.start).utc().toISOString(),
        stop: moment(data.stop).utc().toISOString(),
    }));

    useEffect(() => {
        setStartInLt(parseDateToLt(data.start));
        setStopInLtOrNow(parseDateToLtOrNow(data.stop));

        const _isRunning = isEntryRunning(data as TimeEntry);

        setIsRunning(_isRunning);

        if (_isRunning) {
            interval.current = setInterval(function () {
                setDuration(calculateDifferenceFromNowToHms(data.start));
            }, 1000);

            return () => clearInterval(interval.current);
        } else {
            setDuration(
                calculateDifferenceToHms(data.start, data.stop as string)
            );
            clearInterval(interval.current);
        }
    }, [data.start, data.stop]);

    function deleteTimeEntry() {
        destroy(`/time-entries/${timeEntry.id}`, {
            preserveScroll: true,
        });
    }

    function updateTimEntry() {
        patch(route("time-entries.update", { timeEntry: timeEntry.id }), {
            preserveScroll: true,
            onSuccess: () => setDefaults(),
        });
    }

    function stopNow() {
        setData("stop", moment().format("YYYY-MM-DDTHH:mm"));
    }

    function isProviderAlreadyAdded(provider: Provider) {
        return timeEntry.providers.some((p) => p.id === provider.id);
    }

    function toggleProvider(provider: Provider) {
        if (isProviderAlreadyAdded(provider)) {
            post(
                route("time-entries.providers.detach", {
                    timeEntry: timeEntry.id,
                    provider: provider.id,
                }),
                {
                    preserveScroll: true,
                    data: undefined,
                }
            );
        } else {
            post(
                route("time-entries.providers.attach", {
                    timeEntry: timeEntry.id,
                    provider: provider.id,
                }),
                {
                    preserveScroll: true,
                    data: undefined,
                }
            );
        }
    }

    return (
        <li className="bg-white p-4 sm:p-4 ease-in duration-100 shadow hover:z-10 hover:shadow-xl sm:rounded-lg divide-x flex items-center">
            {isDirty && (
                <div>
                    <PrimaryButton className="mr-3" onClick={updateTimEntry}>
                        Confirm
                    </PrimaryButton>
                    <SecondaryButton className="mr-3" onClick={() => reset()}>
                        Cancel
                    </SecondaryButton>
                </div>
            )}
            <TextInput
                className="grow mr-3 border-none !shadow-none"
                value={data.description}
                onChange={(e) => setData("description", e.target.value)}
            ></TextInput>

            <Popover>
                <Popover.Trigger>
                    <div className="px-3">
                        {timeEntry.providers.length > 0 ? (
                            timeEntry.providers.map((provider) => (
                                <span
                                    key={provider.id}
                                    className="bg-sky-100 hover:bg-sky-300 py-1 px-2 rounded-full cursor-pointer text-sm font-bold"
                                >
                                    {provider.slug}
                                </span>
                            ))
                        ) : (
                            <div>No providers</div>
                        )}
                    </div>
                </Popover.Trigger>
                <Popover.Content>
                    <ul>
                        {providers.map((provider) => (
                            <li key={provider.id}>
                                <button
                                    className="w-full text-left px-3 py-2 hover:bg-gray-100"
                                    onClick={() => toggleProvider(provider)}
                                >
                                    {provider.slug}
                                </button>
                            </li>
                        ))}
                    </ul>
                </Popover.Content>
            </Popover>

            <Popover>
                <Popover.Trigger>
                    <button className="px-3 text-neutral-500 font-extralight w-44 text-center">
                        {startInLt} - {stopInLtOrNow}
                    </button>
                </Popover.Trigger>

                <Popover.Content>
                    <section className="space-y-3 p-3">
                        <TextInput
                            type="datetime-local"
                            name="start"
                            value={moment(data.start).format(
                                "YYYY-MM-DDTHH:mm"
                            )}
                            onChange={(e) => setData("start", e.target.value)}
                        />
                        <TextInput
                            type="datetime-local"
                            name="stop"
                            value={moment(data.stop).format("YYYY-MM-DDTHH:mm")}
                            onChange={(e) => setData("stop", e.target.value)}
                        />
                    </section>
                </Popover.Content>
            </Popover>
            <p
                className={
                    "px-3 font-bold m-w-24 text-center" +
                    (isRunning ? " text-blue-500" : "")
                }
            >
                {duration}
                {isRunning && (
                    <DangerButton className="ml-3" onClick={stopNow}>
                        Stop
                    </DangerButton>
                )}
            </p>
            <div className="pl-3">
                <Dropdown>
                    <Dropdown.Trigger>
                        <SecondaryButton>...</SecondaryButton>
                    </Dropdown.Trigger>
                    <Dropdown.Content>
                        <Dropdown.Button onClick={deleteTimeEntry}>
                            Delete
                        </Dropdown.Button>
                    </Dropdown.Content>
                </Dropdown>
            </div>
        </li>
    );
}

export default React.memo(TimEntry);
