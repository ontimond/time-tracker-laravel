import DangerButton from "@/Components/DangerButton";
import SecondaryButton from "@/Components/SecondaryButton";
import { TimeEntry } from "@/types";
import {
    isEntryRunning,
    parseDateToLt,
    parseDateToLtOrNow,
    parseSecondsToHms,
} from "./utils";
import { Menu } from "@headlessui/react";
import Dropdown from "@/Components/Dropdown";
import { useForm } from "@inertiajs/react";
import TextInput from "@/Components/TextInput";

export default function TimEntry({ timeEntry }: { timeEntry: TimeEntry }) {
    const start = parseDateToLt(timeEntry.start);
    const stop = parseDateToLtOrNow(timeEntry.stop);
    const duration = parseSecondsToHms(timeEntry.duration);
    const isRunning = isEntryRunning(timeEntry);

    const {
        data,
        setData,
        delete: destroy,
    } = useForm({
        description: timeEntry.description,
        start: start,
        stop: stop,
    });

    function deleteTimeEntry() {
        destroy(`/time-entries/${timeEntry.id}`, {
            preserveScroll: true,
        });
    }

    return (
        <li className="bg-white p-4 sm:p-4 ease-in duration-100 shadow hover:z-10 hover:shadow-xl sm:rounded-lg divide-x flex items-center">
            <TextInput
                className="grow mr-3 border-none shadow-none"
                value={data.description}
                onChange={(e) => setData("description", e.target.value)}
            ></TextInput>
            <p className="px-3 text-neutral-500 font-extralight w-44 text-center">
                {data.start} - {data.stop}
            </p>
            <p
                className={
                    "px-3 font-bold m-w-24 text-center" +
                    (isRunning ? " text-blue-500" : "")
                }
            >
                {duration}
                {isRunning && (
                    <DangerButton className="ml-3">Stop</DangerButton>
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
