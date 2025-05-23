// This file sets up global JavaScript dependencies and configurations
// (was previously named bootstrap.js but renamed to avoid confusion with the Bootstrap framework)

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
