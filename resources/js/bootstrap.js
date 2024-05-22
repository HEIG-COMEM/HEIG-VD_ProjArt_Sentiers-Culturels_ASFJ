import { setDefaultHeaders, setBaseUrl } from './Composables/fetchApi.js';

export const csrfToken =
  document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

setDefaultHeaders({ 'X-CSRF-TOKEN': csrfToken });

setBaseUrl(document.querySelector('meta[name="api-base-url"]')?.getAttribute('content') ?? '');
