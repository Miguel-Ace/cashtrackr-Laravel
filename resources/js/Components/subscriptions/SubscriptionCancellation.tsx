import { Subscription } from "@/types/subscription";
import { formatDate } from "@/utils";
import { useState } from "react";
import { router } from "@inertiajs/react";
import { route } from "ziggy-js";

type Props = {
    next_billing_date: Subscription['next_billing_date']
}

export default function SubscriptionCancellation({next_billing_date}: Props) {
  const [loading, setLoading] = useState(false);
  const [confirmCancel, setConfirmCancel] = useState(false);

  const cancelSubscription = () => {
    setLoading(true)
    router.post(
        route('subscription.cancel'),
        {},
        {
            onFinish: () => {
                setLoading(false)
                setConfirmCancel(false)
            },
            preserveScroll: true
        }
    )
  };


  return (
    (
      <div className="rounded-xl  bg-red-600 p-6">
        <h3 className="text-2xl font-bold mb-1 text-white">
          Cancelar Suscripción
        </h3>
        <p className="text-white mb-4">
          Mantendrás acceso al Asistente AI hasta el final del periodo pagado {formatDate(next_billing_date)}. Después se cancelará automáticamente sin más cobros.
        </p>

        {!confirmCancel ? (
          <button
            onClick={() => setConfirmCancel(true)}
            className="bg-red-700 hover:bg-red-800 text-white font-bold py-3 px-6 rounded-lg disabled:opacity-50 cursor-pointer"

          >
            Cancelar mi suscripción
          </button>
        ) : (
          <div className="bg-white rounded-lg p-4 border border-red-300">
            <p className="font-bold mb-3">¿Estás seguro?</p>
            <div className="flex gap-3">
              <button
                onClick={cancelSubscription}
                disabled={loading}
                className="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
              >
                {loading ? 'Cancelando...' : 'Sí, cancelar'}
              </button>
              <button
                onClick={() => setConfirmCancel(false)}
                disabled={loading}
                className="bg-gray-200 hover:bg-gray-300 font-bold py-2 px-4 rounded"
              >
                No, mantener
              </button>
            </div>
          </div>
        )}
      </div>
    )
  )
}