import '../css/app.css';
import React, { useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import { initializeTheme } from './hooks/use-appearance';

// Tell TypeScript about window.MathJax
declare global {
    interface Window {
        MathJax?: {
            typeset?: () => void;
        };
    }
}

interface LatexRendererProps {
    formula: string;
}

// React component to render LaTeX formulas using MathJax
const LatexRenderer: React.FC<LatexRendererProps> = ({ formula }) => {
    useEffect(() => {
        if (window.MathJax && window.MathJax.typeset) {
            window.MathJax.typeset();
        }
    }, [formula]);

    return <div className="mathjax-latex" dangerouslySetInnerHTML={{ __html: formula }} />;
};

// Initialize your app and theme
initializeTheme();

// This example assumes you have an element with id 'latex-root' in your Blade template
const container = document.getElementById('latex-root');
if (container) {
    const formula = container.getAttribute('data-formula') || '';
    const root = createRoot(container);
    root.render(<LatexRenderer formula={formula} />);
}
