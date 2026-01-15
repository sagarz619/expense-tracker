import './bootstrap';

// Import Bootstrap
import 'bootstrap/dist/css/bootstrap.min.css';
import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Import Chart.js
import { Chart, registerables } from 'chart.js';
Chart.register(...registerables);
window.Chart = Chart;

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
