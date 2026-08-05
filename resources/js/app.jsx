import './bootstrap';
import '../css/app.css';

import React from 'react';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';

const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });

createInertiaApp({
    title: (title) => `${title} - mybiz`,
    resolve: (name) => {
        const page = pages[`./Pages/${name}.jsx`];
        if (!page) {
            console.error(`Page not found: ./Pages/${name}.jsx`);
        }
        return page.default || page;
    },
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(React.createElement(App, props));
    },
    progress: {
        color: '#6366f1',
    },
});
