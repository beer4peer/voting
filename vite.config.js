import fs from 'fs';
import {defineConfig} from 'vite';
import laravel, {refreshPaths} from 'laravel-vite-plugin';
import {homedir} from 'os'
import {resolve} from 'path'

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                ...refreshPaths,
                'app/Http/Livewire/**',
                'app/Tables/Columns/**',
            ],

        }),
    ],
    server: detectServerConfig(),
});


function detectServerConfig() {
    let host = 'vote.test';
    let keyPath = resolve(homedir(), `.config/valet/Certificates/${host}.key`)
    let certificatePath = resolve(homedir(), `.config/valet/Certificates/${host}.crt`)

    if (!fs.existsSync(keyPath)) {
        return {}
    }

    if (!fs.existsSync(certificatePath)) {
        return {}
    }

    return {
        hmr: {host},
        host,
        https: {
            key: fs.readFileSync(keyPath),
            cert: fs.readFileSync(certificatePath),
        },
    }
}
