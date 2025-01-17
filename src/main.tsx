import { createRoot } from 'react-dom/client';
import App from './App.tsx';
import './index.css';

// Wait for DOM to be ready (WordPress compatibility)
document.addEventListener('DOMContentLoaded', () => {
  const root = document.getElementById('root');
  if (root) {
    createRoot(root).render(<App />);
  }
});