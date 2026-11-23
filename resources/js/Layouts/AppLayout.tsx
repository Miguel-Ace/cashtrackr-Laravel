import { Head, usePage } from "@inertiajs/react";
import { useEffect } from "react";
import { toast, ToastContainer } from "react-toastify";

type Props = {
    title: string
    children: React.ReactNode
}

export default function AppLayout({title, children}: Props) {
    const { flash } = usePage().props
    
    useEffect(() => {
        if(flash.success){
            toast.success(flash.success)
        }

        if(flash.error){
            toast.error(flash.error)
        }
    }, [flash])

    return (
        <>
            <Head title={title}/>

            {children}

            <ToastContainer />
        </>
    )
}
