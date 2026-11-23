import { router } from "@inertiajs/react"
import { useState } from "react"
import { route } from "ziggy-js"

export default function SubscriptionUpgrade() {
    const [loading, setLoading] = useState(false)

    const swapPlan = (newPlan: string) => {
        setLoading(true)

        router.post(
            route('subscription.swap', newPlan),
            {},
            {
                onFinish: () => {
                    setLoading(false)
                },
                preserveScroll: true
            }
        )
    }

    return (
        <div className="rounded-xl bg-purple-950 p-6 mb-6">
        <div className="flex items-start gap-4">
            <div className="flex-1">
            <h3 className="text-2xl font-bold mb-1 text-white">
                Upgrade a Anual y ahorra
            </h3>
            <p className="text-white mb-4">
                Paga $990 al año en lugar de $1,188 ($99 × 12).
                <strong> Te ahorras $198 al año</strong> — el equivalente a 2 meses gratis.
            </p>
            <button
                className="bg-amber-500 hover:bg-amber-400 text-white font-bold py-3 px-6 rounded-lg disabled:opacity-50 cursor-pointer"
                onClick={() => swapPlan('yearly')}
            >
                {loading ? 'Procesando...' : 'Upgrade a Anual'}
            </button>
            <p className="text-xs text-white mt-3">
                Solo pagas la diferencia proporcional al tiempo que te queda del mes actual.
            </p>
            </div>
        </div>
        </div>
    )
}