import { createInertiaApp } from '@inertiajs/react'

createInertiaApp({
    title: title => `CashTrackr - ${title}`,
    pages: {
        path: './Pages',
        extension: '.tsx'
        // lazy: true,
        // transform: (name, page) => name.replace('/', '-'),
    },
})