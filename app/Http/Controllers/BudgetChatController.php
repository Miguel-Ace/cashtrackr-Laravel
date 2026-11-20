<?php

namespace App\Http\Controllers;

use App\Ai\Agents\BudgetAssistant;
use App\Models\Budget;
use Illuminate\Http\Request;

class BudgetChatController extends Controller
{
    public function store(Request $request, Budget $budget)
    {
        $messages = $request->input('messages', []);
        $lastMessage = collect($messages)->last();

        $prompt = collect(data_get($lastMessage, 'parts', []))
            ->where('type', 'text')
            ->pluck('text')
            ->implode(' ')
            ?: data_get($lastMessage, 'content', '');

        // $prompt = $messages[0]['parts'][0]['text'];

        // dd($prompt);

        $agent = new BudgetAssistant;
        $agent->budgetId = $budget->id;
        $agent->hasCategories = $budget->isGeneral();

        if ($budget->isGoal()) {
            $agent->budgetContext = "Este presupuesto es de tipo Meta/Objetivo llamado '{$budget->name}' con un monto total de \${$budget->amount}. Los gastos NO tienen categorías, solo nombre y monto.";
        } else {
            $agent->budgetContext = "Este presupuesto es de tipo General llamado '{$budget->name}' con un monto total de \${$budget->amount}. Los gastos tienen nombre, monto y categoría.";
        }

        return $agent->stream(
                $prompt, 
                provider: 'openrouter',
                // model: 'inclusionai/ling-3.2-flash:free'
                model: 'baidu/ernie-5.5-flash-preview:free'
                // model: 'liquid/lfm-3-1.7b:free'
                // model: 'liquid/lfm-3-1.7b:free'
                // model: 'particle-ai/particle-1:free'
                // model: 'recursive/flint-v1.0:free'
                // model: 'nvidia/nemotron-3.5-super-vl:free'
                // model: 'dots-studio/dots-3-note-preview:free'
                // model: 'nvidia/nemotron-3.5-lightning:free'
                // model: 'cohere/north-mini-code:free'
                // model: 'nvidia/nemotron-3-ultra-550b-a55b:free'
                // model: 'nvidia/nemotron-3-nano-omni-30b-a3b-reasoning:free'
                // model: 'google/gemma-4-26b-a4b-it:free'
                // model: 'google/gemma-4-31b-it:free'
            )->usingVercelDataProtocol();
    }
}
