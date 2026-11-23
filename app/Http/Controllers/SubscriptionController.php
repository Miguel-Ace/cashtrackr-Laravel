<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Cashier\Subscription;

class SubscriptionController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        $subscription = $user->subscription('default');
        
        if(!$subscription)
        {
            return redirect()->route('plans');
        }

        $currentPlan = $user->subscribedToPrice(config('services.stripe.price_ai_yearly'), 'default') ? 'yearly' : 'monthly';

        $nextBillingDate = $this->getNextBillingDate($subscription);
        
        return Inertia::render('Subscriptions/Manage', [
            'subscription' => [
                'plan' => $currentPlan,
                'price' => $currentPlan === 'yearly' ? 990 : 99,
                'status_label' => $this->buildStatusLabel($subscription, $nextBillingDate),
                'on_grace_period' => $subscription->onGracePeriod(),
                'next_billing_date' => $nextBillingDate,
                'ends_at' => $subscription->end_at?->toIso8601String(),
                'canceled' => $subscription->canceled()
            ]
        ]);
    }

    public function swap(Request $request, string $plan)
    {
        $prices = [
            'monthly' => config('services.stripe.price_ai_monthly'),
            'yearly' => config('services.stripe.price_ai_yearly'),
        ];

        abort_unless(isset($prices[$plan]), 404, 'Plan no válido');

        $user = $request->user();
        $subscription = $user->subscription('default');

        $currentPlan = $user->subscribedToPrice(config('services.stripe.price_ai_yearly'), 'default') ? 'yearly' : 'monthly';

        if($currentPlan === 'yearly' && $plan === 'monthly')
        {
            return back()->with('error', 'No es posible cambiar de plan anual a mensual..');
        }

        if($currentPlan === $plan)
        {
            return back()->with('error', 'Ya estás en este plan..');
        }
        $subscription->swap($prices[$plan]);
        cache()->forget("stripe.next_billing.{$subscription->id}");

        return redirect()->route('subscription.manage')->with('success', 'Bienvenido(a) al plan Anual, Disfruta de tu ahorro.');
    }

    public function cancel(Request $request)
    {
        $request->user()->subscription('default')->cancel();
        return back()->with('success', 'Tu suscripción ha sido cancelada, mantendrás el acceso hasta el finale del periodo pagado.');
    }

    public function resume(Request $request)
    {
        $request->user()->subscription('default')->resume();
        return back()->with('success', 'Bienvenido(a) de vuelta!, Tu suscripción está activa.');
    }
    
    private function getNextBillingDate(Subscription $subscription): ?string
    {
        return cache()->remember(
            "stripe.next_billing.{$subscription->id}",
            now()->addHours(1),
            function () use ($subscription) {
                try {
                    $stripe = $subscription->asStripeSubscription();

                    $periodEnd = $stripe->items->data[0]->current_period_end ?? null;

                    return $periodEnd
                        ? Carbon::createFromTimestamp($periodEnd)->toIso8601String()
                        : null;
                } catch (\Exception $e) {
                    logger()->error('Error obteniendo next billing date', [
                        'error' => $e->getMessage(),
                        'subscription_id' => $subscription->id,
                    ]);
                    return null;
                }
            }
        );
    }

    private function buildStatusLabel(Subscription $subscription, ?string $nextBillingDate): array
    {
        if ($subscription->ended()) {
            return [
                'text' => 'Suscripción terminada',
                'description' => 'Terminó el',
                'date' => $subscription->ends_at?->toIso8601String(),
                'color' => 'gray',
            ];
        }

        if ($subscription->onGracePeriod()) {
            return [
                'text' => 'Cancelada',
                'description' => 'Acceso hasta el',
                'date' => $subscription->ends_at?->toIso8601String(),
                'color' => 'orange',
            ];
        }

        if ($subscription->hasIncompletePayment() || $subscription->pastDue()) {
            if ($this->latestInvoiceIsPaid($subscription)) {
                return [
                    'text' => 'Suscripción Activa',
                    'description' => 'Tu próximo cobro será el ',
                    'color' => 'green',
                    'date' => $nextBillingDate,
                ];
            }

            if ($subscription->hasIncompletePayment()) {
                return [
                    'text' => 'Pago por confirmar',
                    'description' => 'Completa la verificación de tu tarjeta',
                    'date' => null,
                    'color' => 'red',
                ];
            }

            return [
                'text' => 'Pago pendiente',
                'description' => 'Actualiza tu método de pago para continuar',
                'date' => null,
                'color' => 'red',
            ];
        }

        return [
            'text' => 'Suscripción Activa',
            'description' => 'Tu próximo cobro será el ',
            'color' => 'green',
            'date' => $nextBillingDate,
        ];
    }

    private function latestInvoiceIsPaid(Subscription $subscription): bool
    {
        try {
            $stripeSub = $subscription->asStripeSubscription();
            if (!$stripeSub->latest_invoice) {
                return false;
            }

            $invoice = \Laravel\Cashier\Cashier::stripe()
                ->invoices
                ->retrieve($stripeSub->latest_invoice);

            return $invoice->status === 'paid';

        } catch (\Exception $e) {
            logger()->error('Error verificando invoice', [
                'error' => $e->getMessage(),
                'subscription_id' => $subscription->id,
            ]);
            return false;
        }
    }
}
