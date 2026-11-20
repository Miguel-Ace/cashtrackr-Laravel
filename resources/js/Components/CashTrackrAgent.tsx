import { useRef, useState } from 'react';
import { useChat } from '@ai-sdk/react';
import { DefaultChatTransport } from 'ai';
import { toast } from 'react-toastify';
import { router } from '@inertiajs/react'

type Props = {
    budgetId: number
    name: string
}


export default function CashTrackrAgent({ budgetId, name }: Props) {

    const [input, setInput] = useState('');
    const fileInputRef = useRef<HTMLInputElement>(null)

    const { messages, sendMessage, status, setMessages } = useChat({
        transport: new DefaultChatTransport({
            api: `/dashboard/budget/${budgetId}/chat`,
        }),
        onFinish: ({message}) => {
            const expenseCreated = message.parts.some(part => {
                // if(!part.output) return null
                // return part.output.startsWith('[EXPENSE_CREATED]')

                const isAddExpenseTool = part.type === 'tool-AddExpense'
                const finished = part.state === 'output-available'
                
                return isAddExpenseTool && finished
            })

            if (expenseCreated) {
                toast.success('Gasto registrado correctamente')
                router.reload({only: ['expenses', 'budget']})
            }
        }
    });

    const handleSubmit = (e: React.SubmitEvent<HTMLFormElement>) => {
        e.preventDefault()

        if (input.trim()) {
            sendMessage({ text: input });
            setInput('');
        }
    }

    const handleImageUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0]
        if(!file) return

        setMessages(prev => [
            ...prev,
            {
                id: crypto.randomUUID(),
                role: 'user' as const,
                content: 'Ticket de compra subido',
                parts: [{type: 'text' as const, text: 'Ticket de compra subido'}]
            }
        ])


        try {
            
        } catch (error) {
            console.error('Error al procesar el Ticket: ', error)
        }
    }

    return (
        <section className='p-10 lg:px-5 shadow-lg mt-10'>
            <h2 className="text-3xl font-bold">Pregunta sobre tu Presupuesto, añade gastos y más.</h2>
            <div className="space-y-3 mb-4 mt-8">
                {messages.map(m => (
                    <div 
                        key={m.id}
                        className={`p-3 rounded-lg max-w-[80%] lg:max-w-[60%] ${m.role === 'user' ? 'bg-amber-500 text-white ml-auto' : 'bg-gray-100 mr-auto' }`}
                    >
                        {m.parts.map((part, i) => {
                            if (part.type !== 'text') return null

                            const text = part.text.trim()

                            if (!text) return null

                            return (
                                <p key={i} className='text-xl'>
                                    <strong>{m.role === 'user' ? name : 'CashTrackr IA'}:</strong>
                                    {' '}
                                    {text}
                                </p>
                            )
                        })}
                    </div>
                ))}
            </div>

            <form
                onSubmit={handleSubmit}
                className="flex flex-col gap-2"
            >
                <textarea
                    value={input}
                    onChange={(e) => setInput(e.target.value)}
                    placeholder="Consulta dudas sobre tu Presupuesto o Agrega Gastos"
                    className="w-full border border-gray-300 p-3 rounded-lg text-xl"
                />
                <div className="flex gap-2">
                    <button
                        type="submit"
                        className="flex-1 mt-5 bg-purple-950 hover:bg-purple-800 p-3 rounded-lg text-white font-bold text-xl cursor-pointer disabled:opacity-20"
                    >
                        Consultar
                    </button>
                    <button
                        type="button"
                        onClick={() => fileInputRef.current?.click()}
                        className="mt-5 bg-amber-500 hover:bg-amber-500 p-3 rounded-lg text-white font-bold text-xl cursor-pointer disabled:opacity-20"
                    >
                        Subir Ticket
                    </button>
                </div>
                <input
                    type="file"
                    accept="image/*"
                    className="hidden"
                    ref={fileInputRef}
                    onChange={handleImageUpload}
                />
            </form>
        </section>
    );
}