import { Expense } from "./expense"

type budgetType = 'general' | 'goal'

export type Budget = {
    id: number,
    name: string,
    amount: string,
    type: budgetType,
    created_at: string,
    expenses: Expense[]
}