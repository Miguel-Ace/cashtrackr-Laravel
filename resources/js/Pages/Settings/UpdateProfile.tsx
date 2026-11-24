import InputError from "@/Components/InputError";
import AppLayout from "@/Layouts/AppLayout";
import { useForm, usePage } from "@inertiajs/react";
import { route } from "ziggy-js";

export default function UpdateProfile() {
    const {user: {user}} = usePage().props

    const {data, setData, put, errors, processing} = useForm({
        name: user.name,
        email: user.email,
    })

    const handleSubmit = (e: React.SubmitEvent<HTMLFormElement>) => {
        e.preventDefault()

        put(route('settings.profile.update'))
    }
    
    return (
        <AppLayout title="Actualizar Perfil">
            <div className="sm:flex sm:items-center mt-10">
                <div className="sm:flex-auto">
                <h1 className="font-bold text-4xl">Ajustes</h1>
                <p className="mt-2 text-xl text-gray-500">Modifica tu información en esta página</p>
                </div>
                <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href={route('dashboard')}
                    className="block bg-amber-500 text-white w-full px-5 py-3 rounded-lg  font-bold  text-xl cursor-pointer text-center">Volver a Presupuestos</a>
                </div>
            </div>

            <form
                className="mt-14 space-y-3 max-w-2xl mx-auto" 
                onSubmit={handleSubmit}
            >

                <div className="flex flex-col gap-2">
                    <label className="font-bold text-2xl" htmlFor="name">Nombre</label>

                    <input
                        id="name"
                        type="text"
                        placeholder="Tu Nombre"
                        className="w-full border border-gray-300 p-3 rounded-lg"
                        value={data.name}
                        onChange={e => setData('name',e.target.value)}
                    />

                    {errors.name && <InputError>{errors.name}</InputError>}
                </div>

                <div className="flex flex-col gap-2">
                    <label className="font-bold text-2xl" htmlFor="email">Email</label>

                    <input
                        id="email"
                        type="email"
                        placeholder="Tu Email"
                        className="w-full border border-gray-300 p-3 rounded-lg"
                        value={data.email}
                        onChange={e => setData('email',e.target.value)}
                    />

                    {errors.email && <InputError>{errors.email}</InputError>}
                </div>

                <input type="submit" value='Guardar Cambios'
                className="bg-purple-950 hover:bg-purple-800 w-full p-3 rounded-lg text-white font-bold  text-xl cursor-pointer" />
            </form>
        </AppLayout>
    )
}