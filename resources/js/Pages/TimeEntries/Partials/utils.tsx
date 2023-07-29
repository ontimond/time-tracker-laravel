import { TimeEntry } from "@/types";
import moment, { Moment } from "moment";
import momentDurationFormatSetup from "moment-duration-format";

momentDurationFormatSetup(
    // @ts-ignore
    moment
);

export function parseToCalendarFromNow(day: string): string {
    return moment(day).calendar({
        sameDay: "[Today]",
        nextDay: "[Tomorrow]",
        nextWeek: "dddd",
        lastDay: "[Yesterday]",
        lastWeek: "[Last] dddd",
        sameElse: "DD/MM/YYYY",
    });
}

export function calculateTotalDurationToHms(timeEntries: TimeEntry[]) {
    const total = sumDurations(timeEntries);
    const totalInHms = parseSecondsToHms(total);
    return totalInHms;
}

export function calculateDifferenceToHms(start: string, stop: string | Moment) {
    const difference = calculateDifference(start, stop);
    const differenceInHms = parseSecondsToHms(difference);
    return differenceInHms;
}

export function calculateDifferenceFromNowToHms(start: string) {
    const difference = calculateDifferenceFromNow(start);
    const differenceInHms = parseSecondsToHms(difference);
    return differenceInHms;
}

export function calculateDifferenceFromNow(start: string) {
    const difference = calculateDifference(start, moment());
    return difference;
}

export function calculateDifference(
    start: string | Moment,
    stop: string | Moment
) {
    const difference = moment(stop).diff(moment(start), "seconds");
    return difference;
}

export function parseSecondsToHms(seconds: number): string {
    return moment.duration({ seconds }).format("HH:mm:ss", {
        trim: false,
    });
}

export function parseDateToHmsOrNow(any?: any): string {
    return any ? parseDateToHms(any) : "Now";
}

export function parseDateToHms(any?: any): string {
    return moment(any).format("HH:mm:ss");
}

export function parseDateToLt(any?: any): string {
    return moment(any).format("LT");
}

export function parseDateToLtOrNow(any?: any): string {
    return any ? parseDateToLt(any) : "Now";
}

function sumDurations(timeEntries: TimeEntry[]): number {
    return timeEntries.reduce((total, timeEntry) => {
        return total + timeEntry.duration;
    }, 0);
}

export function isSomeEntryRunning(timeEntries: TimeEntry[]): boolean {
    return timeEntries.some((timeEntry) => isEntryRunning(timeEntry));
}

export function findEntriesRunning(timeEntries: TimeEntry[]): TimeEntry[] {
    return timeEntries.filter((timeEntry) => isEntryRunning(timeEntry));
}

export function isEntryRunning(timeEntry: TimeEntry): boolean {
    return !timeEntry.stop;
}
