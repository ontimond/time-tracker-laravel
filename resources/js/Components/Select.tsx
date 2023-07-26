import { SelectHTMLAttributes } from "react";

export default function Select({
    className = "",
    options = [],
    ...props
}: SelectHTMLAttributes<HTMLSelectElement> & {
    options: { value: string; label: string }[];
}) {
    return (
        <select
            {...props}
            className={
                "rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 " +
                className
            }
        >
            {options.map((option) => (
                <option key={option.value} value={option.value}>
                    {option.label}
                </option>
            ))}
        </select>
    );
}
