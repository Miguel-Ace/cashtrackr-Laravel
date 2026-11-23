import { Subscription } from "@/types/subscription";
import { formatDate } from "@/utils";
import { useState } from "react";
import { router } from "@inertiajs/react";
import { route } from "ziggy-js";

type Props = {
    ends_at: Subscription['ends_at']
}

export default function SubscriptionResume({ends_at}: Props) {
  const [loading, setLoading] = useState(false);

  const resumeSubscription = () => {
    setLoading(true)
    router.post(
        route('subscription.resume'),
        {},
        {
            onFinish: () => setLoading(false),
            preserveScroll: true
        }
    )
  };

  return (
    <div className="rounded-xl border-2 border-purple-200 bg-purple-50 p-6">
      <h3 className="text-xl font-bold mb-2">¿Cambiaste de opinión?</h3>
      <p className="text-gray-600 mb-4">
        Aún puedes reactivar tu suscripción antes del{' '}
        {formatDate(ends_at)} sin cargos adicionales.
      </p>
      <button
        onClick={resumeSubscription}
        disabled={loading}
        className="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg disabled:opacity-50"
      >
        {loading ? 'Procesando...' : 'Reactivar suscripción'}
      </button>
    </div>
  )
}