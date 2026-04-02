import { computed, ComputedRef } from 'vue'
import { usePage } from '@inertiajs/vue3'

// Define the shape of the currency data provided by the backend middleware
export interface CurrencyData {
    code: string;
    symbol: string;
    label: string;
}

// Define the expected Inertia page props
interface PageProps {
    currency: CurrencyData;
    [key: string]: any; // Allow other default Inertia props
}

export function useCurrency() {
    // Pass the PageProps interface to usePage to strictly type page.props
    const page = usePage<PageProps>()

    // Create a computed ref typed to our CurrencyData interface
    const currency: ComputedRef<CurrencyData> = computed(() => page.props.currency)

    /**
     * Formats an amount with the active currency symbol.
     * 
     * @param amount - The numerical value to format (accepts number or string)
     * @param decimals - Number of decimal places (default: 2)
     * @returns The formatted currency string (e.g., "S$1,500.00")
     */
    function format(amount: number | string, decimals: number = 2): string {
        const symbol: string = currency.value?.symbol ?? '৳'
        
        // Ensure the amount is treated as a number
        const numericAmount: number = typeof amount === 'string' ? parseFloat(amount) : amount;

        // Fallback for invalid numbers
        if (isNaN(numericAmount)) {
            return `${symbol}0.00`;
        }

        const formatted: string = numericAmount.toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals,
        })

        return `${symbol}${formatted}`
    }

    return { 
        currency, 
        format 
    }
}