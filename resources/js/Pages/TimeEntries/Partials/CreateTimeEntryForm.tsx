import TextInput from "@/Components/TextInput";
import { useForm } from "@inertiajs/react";
import moment from "moment";
import { calculateDifferenceToHms } from "./utils";
import PrimaryButton from "@/Components/PrimaryButton";
import { useEffect, useState } from "react";

export default function CreateTimeEntryForm() {
    const defaultStart = moment().format("YYYY-MM-DDTHH:mm");
    const defaultStop = moment().format("YYYY-MM-DDTHH:mm");
    const { data, setData, post, reset } = useForm({
        description: "",
        start: defaultStart,
        stop: defaultStop,
    });

    const [duration, setDuration] = useState(
        calculateDifferenceToHms(data.start, data.stop)
    );

    useEffect(() => {
        setDuration(calculateDifferenceToHms(data.start, data.stop));
    }, [data.start, data.stop]);

    function createTimeEntry(e: React.FormEvent<HTMLFormElement>) {
        e.preventDefault();
        data.start = moment(data.start).utc(true).toISOString();
        data.stop = moment(data.stop).utc(true).toISOString();
        post("/time-entries", {
            onFinish: () => reset(),
        });
    }

    return (
        <section>
            <form
                className="flex space-x-3 items-center"
                onSubmit={createTimeEntry}
            >
                <TextInput
                    className="grow"
                    placeholder="Description"
                    name="description"
                    value={data.description}
                    onChange={(e) => setData("description", e.target.value)}
                />
                <TextInput
                    placeholder="Start"
                    name="start"
                    type="datetime-local"
                    max={data.stop}
                    value={data.start}
                    onChange={(e) => setData("start", e.target.value)}
                />
                <TextInput
                    placeholder="Stop"
                    name="stop"
                    type="datetime-local"
                    min={data.start}
                    value={data.stop}
                    onChange={(e) => setData("stop", e.target.value)}
                />
                <p className="px-3 text-neutral-500 font-extralight text-center">
                    {duration}
                </p>
                <PrimaryButton>
                    <span className="hidden sm:inline">Add</span>
                    <span className="sm:hidden">+</span>
                </PrimaryButton>
            </form>
        </section>
    );
}
