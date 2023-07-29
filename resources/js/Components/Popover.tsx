import {
    useState,
    createContext,
    useContext,
    Fragment,
    PropsWithChildren,
    Dispatch,
    SetStateAction,
    ButtonHTMLAttributes,
} from "react";
import { Link, InertiaLinkProps } from "@inertiajs/react";
import { Transition } from "@headlessui/react";

const PopoverContext = createContext<{
    open: boolean;
    setOpen: Dispatch<SetStateAction<boolean>>;
    toggleOpen: () => void;
}>({
    open: false,
    setOpen: () => {},
    toggleOpen: () => {},
});

const Popover = ({ children }: PropsWithChildren) => {
    const [open, setOpen] = useState(false);

    const toggleOpen = () => {
        setOpen((previousState) => !previousState);
    };

    return (
        <PopoverContext.Provider value={{ open, setOpen, toggleOpen }}>
            <div className="relative">{children}</div>
        </PopoverContext.Provider>
    );
};

const Trigger = ({ children }: PropsWithChildren) => {
    const { open, setOpen, toggleOpen } = useContext(PopoverContext);

    return (
        <>
            <div onClick={toggleOpen}>{children}</div>

            {open && (
                <div
                    className="fixed inset-0 z-40"
                    onClick={() => setOpen(false)}
                ></div>
            )}
        </>
    );
};

const Content = ({
    align = "right",
    contentClasses = "py-1 bg-white",
    children,
}: PropsWithChildren<{
    align?: "left" | "right";
    contentClasses?: string;
}>) => {
    const { open } = useContext(PopoverContext);

    let alignmentClasses = "origin-top";

    if (align === "left") {
        alignmentClasses = "origin-top-left left-0";
    } else if (align === "right") {
        alignmentClasses = "origin-top-right right-0";
    }

    return (
        <>
            <Transition
                as={Fragment}
                show={open}
                enter="transition ease-out duration-200"
                enterFrom="transform opacity-0 scale-95"
                enterTo="transform opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="transform opacity-100 scale-100"
                leaveTo="transform opacity-0 scale-95"
            >
                <div
                    className={`absolute z-50 mt-2 rounded-md shadow-lg w-auto ${alignmentClasses}`}
                >
                    <div
                        className={
                            `rounded-md ring-1 ring-black ring-opacity-5 ` +
                            contentClasses
                        }
                    >
                        {children}
                    </div>
                </div>
            </Transition>
        </>
    );
};

Popover.Trigger = Trigger;
Popover.Content = Content;

export default Popover;
