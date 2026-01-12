import './style.css'

const trendBar = document.getElementById('trend-bar')

if (trendBar) {
  let lastY = window.scrollY
  let ticking = false
  let hidden = false

  const show = () => {
    if (!hidden) return
    hidden = false
    trendBar.classList.remove('h-0', 'opacity-0', 'pointer-events-none')
    trendBar.classList.add('h-10', 'opacity-100')
  }

  const hide = () => {
    if (hidden) return
    hidden = true
    trendBar.classList.remove('h-10', 'opacity-100')
    trendBar.classList.add('h-0', 'opacity-0', 'pointer-events-none')
  }

  trendBar.classList.add('h-10', 'opacity-100')

  const onScroll = () => {
    const y = window.scrollY
    const delta = y - lastY
    lastY = y

    if (y < 20) {
      show()
      return
    }

    if (Math.abs(delta) < 8) return

    if (delta > 0) {
      hide()
    } else {
      show()
    }
  }

  window.addEventListener(
    'scroll',
    () => {
      if (ticking) return
      ticking = true
      window.requestAnimationFrame(() => {
        onScroll()
        ticking = false
      })
    },
    { passive: true }
  )
}

const featuredImg = document.querySelector('.js-featured-image')
const featuredPanel = document.querySelector('.js-featured-panel')

if (featuredImg instanceof HTMLImageElement && featuredPanel instanceof HTMLElement) {
  const computeAverageRgb = (img) => {
    const canvas = document.createElement('canvas')
    const ctx = canvas.getContext('2d', { willReadFrequently: true })
    if (!ctx) return null

    const w = 48
    const h = 48
    canvas.width = w
    canvas.height = h
    ctx.drawImage(img, 0, 0, w, h)

    const { data } = ctx.getImageData(0, 0, w, h)
    let r = 0
    let g = 0
    let b = 0
    let count = 0

    for (let i = 0; i < data.length; i += 4) {
      const a = data[i + 3]
      if (a < 32) continue

      const rr = data[i]
      const gg = data[i + 1]
      const bb = data[i + 2]

      const max = Math.max(rr, gg, bb)
      const min = Math.min(rr, gg, bb)
      const sat = max === 0 ? 0 : (max - min) / max

      if (max > 245 && sat < 0.1) continue

      r += rr
      g += gg
      b += bb
      count += 1
    }

    if (count === 0) return null
    return {
      r: Math.round(r / count),
      g: Math.round(g / count),
      b: Math.round(b / count)
    }
  }

  const tint = (rgb, mix = 0.82) => {
    const inv = 1 - mix
    return {
      r: Math.round(255 * mix + rgb.r * inv),
      g: Math.round(255 * mix + rgb.g * inv),
      b: Math.round(255 * mix + rgb.b * inv)
    }
  }

  const apply = () => {
    try {
      const src = featuredImg.currentSrc || featuredImg.src
      if (typeof src === 'string' && src !== '') {
        featuredPanel.style.setProperty('--featured-image', `url(${JSON.stringify(src)})`)
      }

      const avg = computeAverageRgb(featuredImg)
      if (!avg) return
      const bg = tint(avg)
      featuredPanel.style.setProperty('--featured-panel-bg', `rgb(${bg.r} ${bg.g} ${bg.b})`)
    } catch {
      return
    }
  }

  if (featuredImg.complete && featuredImg.naturalWidth > 0) {
    apply()
  } else {
    featuredImg.addEventListener('load', apply, { once: true })
  }
}

const storiesList = document.getElementById('stories-list')
const storiesSentinel = document.getElementById('stories-sentinel')
const storiesLoading = document.getElementById('stories-loading')

if (storiesList && storiesSentinel) {
  let loading = false
  let done = false
  let offset = 1 + storiesList.querySelectorAll('article').length

  const setLoading = (isLoading) => {
    if (!storiesLoading) return
    storiesLoading.classList.toggle('hidden', !isLoading)
  }

  const fetchMore = async () => {
    if (loading || done) return

    loading = true
    setLoading(true)

    try {
      const url = `/wp-json/yahoonews/v1/stories?offset=${encodeURIComponent(offset)}&limit=5`
      const res = await fetch(url, { headers: { Accept: 'application/json' } })
      if (!res.ok) {
        done = true
        return
      }

      const data = await res.json()
      const items = Array.isArray(data?.items) ? data.items : []
      if (items.length === 0) {
        done = true
        return
      }

      for (const item of items) {
        if (!item?.html) continue
        storiesList.insertAdjacentHTML('beforeend', item.html)
      }

      offset = typeof data?.nextOffset === 'number' ? data.nextOffset : offset + items.length
      if (data?.hasMore === false) done = true
    } finally {
      loading = false
      setLoading(false)
    }
  }

  const shouldPrefetch = () => {
    const rect = storiesSentinel.getBoundingClientRect()
    return rect.top <= window.innerHeight + 600
  }

  const loadUntilSettled = async () => {
    while (!loading && !done && shouldPrefetch()) {
      await fetchMore()
    }
  }

  const io = new IntersectionObserver(
    (entries) => {
      const entry = entries[0]
      if (!entry || !entry.isIntersecting) return
      loadUntilSettled()
    },
    { rootMargin: '600px 0px' }
  )

  io.observe(storiesSentinel)

  loadUntilSettled()
}
