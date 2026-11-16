import { formatCurrency } from "@/utils"

type Props = {
    label: string,
    amount: number
}

export default function AmountDisplay({label, amount} : Props) {
  return (
    <p className="text-3xl font-bold text-purple-950">
        {label}: {' '}
        <span className="font-black text-amber-500">{formatCurrency(amount)}</span>
    </p>
  )
}
