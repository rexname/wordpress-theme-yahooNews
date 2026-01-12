import { defineConfig } from 'vite'
import tailwindcss from '@tailwindcss/vite'

function fullReloadOnThemeFiles() {
  const watched = ['**/*.php', 'style.css']

  return {
    name: 'full-reload-theme-files',
    configureServer(server) {
      server.watcher.add(watched)
    },
    handleHotUpdate({ file, server }) {
      if (file.endsWith('.php') || file.endsWith('/style.css') || file.endsWith('\\style.css')) {
        server.ws.send({ type: 'full-reload', path: '*' })
        return []
      }
    }
  }
}

export default defineConfig(({ mode }) => {
  const isProduction = mode === 'production'
  const allowedHostsRaw = process.env.VITE_ALLOWED_HOSTS
  const allowedHosts =
    typeof allowedHostsRaw === 'string' && allowedHostsRaw.trim() !== ''
      ? allowedHostsRaw
          .split(',')
          .map((s) => s.trim())
          .filter(Boolean)
      : undefined

  const hmrHost = process.env.VITE_HMR_HOST
  const hmrProtocol = process.env.VITE_HMR_PROTOCOL
  const hmrClientPortRaw = process.env.VITE_HMR_CLIENT_PORT
  const hmrClientPort = typeof hmrClientPortRaw === 'string' && hmrClientPortRaw !== '' ? Number(hmrClientPortRaw) : undefined
  const hmrPath = process.env.VITE_HMR_PATH
  const hmr =
    typeof hmrHost === 'string' && hmrHost.trim() !== ''
      ? {
          host: hmrHost.trim(),
          protocol: typeof hmrProtocol === 'string' && hmrProtocol.trim() !== '' ? hmrProtocol.trim() : undefined,
          clientPort: Number.isFinite(hmrClientPort) ? hmrClientPort : undefined,
          path: typeof hmrPath === 'string' && hmrPath.trim() !== '' ? hmrPath.trim() : undefined
        }
      : undefined

  return {
    plugins: [tailwindcss(), fullReloadOnThemeFiles()],
    base: isProduction ? './' : '/',
    build: {
      outDir: 'dist',
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: {
          main: 'src/main.js'
        }
      }
    },
    server: {
      host: true,
      allowedHosts,
      cors: true,
      hmr,
      strictPort: true,
      port: 5173
    }
  }
})
