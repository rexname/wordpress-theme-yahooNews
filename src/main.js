import './style.css'

const mobileSearchToggle = document.getElementById('mobile-search-toggle')
const mobileSearch = document.getElementById('mobile-search')
const mobileSearchClose = document.getElementById('mobile-search-close')
const mobileSearchInput = document.getElementById('site-search-mobile')
const siteBrand = document.getElementById('site-brand')
const mobileActions = document.getElementById('mobile-actions')

const mobileMenuToggle = document.getElementById('mobile-menu-toggle')
const mobileMenu = document.getElementById('mobile-menu')

if (mobileMenuToggle && mobileMenu) {
  const openIcon = mobileMenuToggle.querySelector('[data-mobile-menu-icon="open"]')
  const closeIcon = mobileMenuToggle.querySelector('[data-mobile-menu-icon="close"]')
  const backdrop = mobileMenu.querySelector('#mobile-menu-backdrop')

  const setOpen = (open) => {
    mobileMenu.classList.toggle('hidden', !open)
    mobileMenuToggle.setAttribute('aria-expanded', open ? 'true' : 'false')
    document.body.classList.toggle('overflow-hidden', open)

    if (openIcon && closeIcon) {
      openIcon.classList.toggle('hidden', open)
      closeIcon.classList.toggle('hidden', !open)
    }
  }

  setOpen(false)

  mobileMenuToggle.addEventListener('click', () => {
    const open = mobileMenuToggle.getAttribute('aria-expanded') === 'true'
    setOpen(!open)
  })

  if (backdrop instanceof HTMLElement) {
    backdrop.addEventListener('click', () => {
      setOpen(false)
    })
  }

  window.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return
    if (mobileMenuToggle.getAttribute('aria-expanded') !== 'true') return
    setOpen(false)
  })

  mobileMenu.addEventListener('click', (e) => {
    const target = e.target
    if (!(target instanceof Element)) return
    if (!target.closest('a')) return
    setOpen(false)
  })
}

if (mobileSearchToggle && mobileSearch && mobileSearchClose && mobileSearchInput) {
  const setSearchOpen = (open) => {
    mobileSearchToggle.setAttribute('aria-expanded', open ? 'true' : 'false')

    mobileSearch.classList.toggle('pointer-events-none', !open)
    mobileSearch.classList.toggle('opacity-0', !open)
    mobileSearch.classList.toggle('opacity-100', open)
    mobileSearch.classList.toggle('[transform:scaleX(0.92)]', !open)
    mobileSearch.classList.toggle('[transform:scaleX(1)]', open)

    if (siteBrand) {
      siteBrand.classList.toggle('pointer-events-none', open)
      siteBrand.classList.toggle('opacity-0', open)
    }

    if (mobileActions) {
      mobileActions.classList.toggle('pointer-events-none', open)
      mobileActions.classList.toggle('opacity-0', open)
    }

    if (mobileMenuToggle && mobileMenu) {
      mobileMenu.classList.add('hidden')
      mobileMenuToggle.setAttribute('aria-expanded', 'false')
      document.body.classList.remove('overflow-hidden')

      const openIcon = mobileMenuToggle.querySelector('[data-mobile-menu-icon="open"]')
      const closeIcon = mobileMenuToggle.querySelector('[data-mobile-menu-icon="close"]')
      if (openIcon && closeIcon) {
        openIcon.classList.remove('hidden')
        closeIcon.classList.add('hidden')
      }
    }

    if (open) {
      mobileSearchInput.focus()
      mobileSearchInput.select()
    } else {
      mobileSearchToggle.focus()
    }
  }

  setSearchOpen(false)

  mobileSearchToggle.addEventListener('click', () => {
    const open = mobileSearchToggle.getAttribute('aria-expanded') === 'true'
    setSearchOpen(!open)
  })

  mobileSearchClose.addEventListener('click', () => {
    setSearchOpen(false)
  })

  window.addEventListener('keydown', (e) => {
    if (e.key !== 'Escape') return
    if (mobileSearchToggle.getAttribute('aria-expanded') !== 'true') return
    setSearchOpen(false)
  })

  document.addEventListener('click', (e) => {
    if (mobileSearchToggle.getAttribute('aria-expanded') !== 'true') return
    const target = e.target
    if (!(target instanceof Node)) return
    if (mobileSearch.contains(target) || mobileSearchToggle.contains(target)) return
    setSearchOpen(false)
  })
}

const trendBar = document.getElementById('trend-bar')

if (trendBar) {
  let lastY = window.scrollY
  let ticking = false
  let hidden = false
  let accumulatedDelta = 0
  let lastToggleAt = 0
  let expandResetTimer = 0
  let expandResetListener = null
  let resizeObserver = null

  const media = window.matchMedia('(min-width: 768px)')

  const getConfig = () => {
    if (media.matches) {
      return {
        toggleCooldownMs: 200,
        topLockPx: 80,
        minDeltaPx: 4,
        hideThresholdPx: 160,
        showThresholdPx: -70,
      }
    }

    return {
      toggleCooldownMs: 180,
      topLockPx: 20,
      minDeltaPx: 2,
      hideThresholdPx: 30,
      showThresholdPx: -30,
    }
  }

  const clearExpandResetListener = () => {
    if (!expandResetListener) return
    trendBar.removeEventListener('transitionend', expandResetListener)
    expandResetListener = null
  }

  const ensureResizeObserver = () => {
    if (resizeObserver) return
    if (typeof ResizeObserver !== 'function') return

    resizeObserver = new ResizeObserver(() => {
      if (hidden) return
      const maxHeight = trendBar.style.maxHeight
      if (maxHeight === 'none' || maxHeight === '') return
      trendBar.style.maxHeight = `${trendBar.scrollHeight}px`
    })
    resizeObserver.observe(trendBar)
  }

  const show = () => {
    if (!hidden) return
    ensureResizeObserver()
    hidden = false
    trendBar.classList.remove('pointer-events-none')
    trendBar.style.opacity = '1'
    trendBar.style.maxHeight = `${trendBar.scrollHeight}px`
    window.clearTimeout(expandResetTimer)
    clearExpandResetListener()
    expandResetListener = (e) => {
      if (!(e instanceof TransitionEvent)) return
      if (e.propertyName !== 'max-height') return
      if (hidden) return
      clearExpandResetListener()
      trendBar.style.maxHeight = 'none'
    }
    trendBar.addEventListener('transitionend', expandResetListener)
    expandResetTimer = window.setTimeout(() => {
      if (hidden) return
      clearExpandResetListener()
      trendBar.style.maxHeight = 'none'
    }, 600)

    window.requestAnimationFrame(() => {
      if (hidden) return
      const maxHeight = trendBar.style.maxHeight
      if (maxHeight === 'none' || maxHeight === '') return
      trendBar.style.maxHeight = `${trendBar.scrollHeight}px`
    })

    accumulatedDelta = 0
    lastToggleAt = performance.now()
  }

  const hide = () => {
    if (hidden) return
    hidden = true
    window.clearTimeout(expandResetTimer)
    clearExpandResetListener()
    if (trendBar.style.maxHeight === 'none' || trendBar.style.maxHeight === '') {
      trendBar.style.maxHeight = `${trendBar.scrollHeight}px`
    }
    trendBar.offsetHeight
    trendBar.classList.add('pointer-events-none')
    trendBar.style.opacity = '0'
    trendBar.style.maxHeight = '0px'
    accumulatedDelta = 0
    lastToggleAt = performance.now()
  }

  trendBar.style.opacity = '1'
  trendBar.style.maxHeight = 'none'

  const reset = () => {
    hidden = false
    window.clearTimeout(expandResetTimer)
    clearExpandResetListener()
    trendBar.classList.remove('pointer-events-none')
    trendBar.style.opacity = '1'
    trendBar.style.maxHeight = 'none'
    accumulatedDelta = 0
    lastToggleAt = performance.now()
  }

  const onScroll = () => {
    const y = window.scrollY
    const delta = y - lastY
    lastY = y

    const config = getConfig()

    const now = performance.now()
    if (now - lastToggleAt < config.toggleCooldownMs) return

    if (y < config.topLockPx) {
      show()
      return
    }

    if (Math.abs(delta) < config.minDeltaPx) return

    if (delta > 0) {
      accumulatedDelta = Math.max(0, accumulatedDelta) + delta
    } else {
      accumulatedDelta = Math.min(0, accumulatedDelta) + delta
    }

    if (accumulatedDelta > config.hideThresholdPx) {
      hide()
      return
    }

    if (accumulatedDelta < config.showThresholdPx) {
      show()
    }
  }

  const onScrollRaf = () => {
    if (ticking) return
    ticking = true
    window.requestAnimationFrame(() => {
      onScroll()
      ticking = false
    })
  }

  reset()
  window.addEventListener('scroll', onScrollRaf, { passive: true })
  if (typeof media.addEventListener === 'function') {
    media.addEventListener('change', reset)
  } else if (typeof media.addListener === 'function') {
    media.addListener(reset)
  }
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
  const categoryId = storiesList instanceof HTMLElement ? storiesList.dataset.categoryId : undefined
  const searchQuery = storiesList instanceof HTMLElement ? storiesList.dataset.searchQuery : undefined
  const tagId = storiesList instanceof HTMLElement ? storiesList.dataset.tagId : undefined
  const nextOffsetAttr =
    storiesList instanceof HTMLElement ? Number.parseInt(storiesList.dataset.nextOffset ?? '', 10) : Number.NaN
  let offset = Number.isFinite(nextOffsetAttr) ? nextOffsetAttr : 1 + storiesList.querySelectorAll('article').length

  const setLoading = (isLoading) => {
    if (!storiesLoading) return
    storiesLoading.classList.toggle('hidden', !isLoading)
  }

  const fetchMore = async () => {
    if (loading || done) return

    loading = true
    setLoading(true)

    try {
      const qs = new URLSearchParams({
        offset: String(offset),
        limit: '5'
      })
      if (typeof categoryId === 'string' && categoryId !== '') {
        qs.set('category', categoryId)
      }
      if (typeof searchQuery === 'string' && searchQuery !== '') {
        qs.set('search', searchQuery)
      }
      if (typeof tagId === 'string' && tagId !== '') {
        qs.set('tag', tagId)
      }
      const url = `/wp-json/yahoonews/v1/stories?${qs.toString()}`
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
      if (storiesList instanceof HTMLElement) storiesList.dataset.nextOffset = String(offset)
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

const readAction = document.getElementById('read-action')
const readActionProgress = document.getElementById('read-action-progress')
const readActionIconClose = document.getElementById('read-action-icon-close')
const readActionIconUp = document.getElementById('read-action-icon-up')

const topStoriesScroll = document.getElementById('top-stories-scroll')
const topStoriesPrev = document.getElementById('top-stories-prev')
const topStoriesNext = document.getElementById('top-stories-next')

if (topStoriesScroll instanceof HTMLElement && topStoriesPrev instanceof HTMLButtonElement && topStoriesNext instanceof HTMLButtonElement) {
  let ticking = false

  const atEnd = () => topStoriesScroll.scrollLeft + topStoriesScroll.clientWidth >= topStoriesScroll.scrollWidth - 8
  const atStart = () => topStoriesScroll.scrollLeft <= 8
  const canScroll = () => topStoriesScroll.scrollWidth > topStoriesScroll.clientWidth + 8

  const update = () => {
    const enabled = canScroll()

    topStoriesPrev.classList.toggle('hidden', atStart())
    topStoriesPrev.disabled = !enabled
    topStoriesPrev.classList.toggle('opacity-30', !enabled)
    topStoriesPrev.classList.toggle('cursor-not-allowed', !enabled)

    topStoriesNext.disabled = !enabled
    topStoriesNext.classList.toggle('opacity-30', !enabled)
    topStoriesNext.classList.toggle('cursor-not-allowed', !enabled)
  }

  const onScrollOrResize = () => {
    if (ticking) return
    ticking = true
    window.requestAnimationFrame(() => {
      update()
      ticking = false
    })
  }

  topStoriesNext.addEventListener('click', () => {
    if (!canScroll()) return

    if (atEnd()) {
      topStoriesScroll.scrollTo({ left: 0, behavior: 'smooth' })
      return
    }

    const step = Math.max(220, Math.round(topStoriesScroll.clientWidth * 0.7))
    topStoriesScroll.scrollBy({ left: step, behavior: 'smooth' })
  })

  topStoriesPrev.addEventListener('click', () => {
    if (!canScroll()) return

    const step = Math.max(220, Math.round(topStoriesScroll.clientWidth * 0.7))
    topStoriesScroll.scrollBy({ left: -step, behavior: 'smooth' })
  })

  topStoriesScroll.addEventListener('scroll', onScrollOrResize, { passive: true })
  window.addEventListener('resize', onScrollOrResize)
  update()
}

if (readAction instanceof HTMLButtonElement && readActionProgress instanceof SVGPathElement) {
  let ticking = false
  let showUp = false

  const setMode = (up) => {
    showUp = up
    if (readActionIconClose instanceof SVGElement) readActionIconClose.classList.toggle('hidden', up)
    if (readActionIconUp instanceof SVGElement) readActionIconUp.classList.toggle('hidden', !up)
    readAction.setAttribute('aria-label', up ? 'Back to top' : 'Close')
  }

  const update = () => {
    const doc = document.documentElement
    const scrollTop = window.scrollY || doc.scrollTop || 0
    const max = Math.max(1, doc.scrollHeight - window.innerHeight)
    const p = Math.max(0, Math.min(1, scrollTop / max))
    readActionProgress.setAttribute('stroke-dasharray', `${p * 100} 100`)
    setMode(scrollTop > 160)
  }

  const onScroll = () => {
    if (ticking) return
    ticking = true
    window.requestAnimationFrame(() => {
      update()
      ticking = false
    })
  }

  readAction.addEventListener('click', () => {
    if (showUp) {
      window.scrollTo({ top: 0, behavior: 'smooth' })
      return
    }

    const homeUrl = readAction.dataset.homeUrl
    if (typeof homeUrl === 'string' && homeUrl !== '') {
      window.location.href = homeUrl
      return
    }
  })

  window.addEventListener('scroll', onScroll, { passive: true })
  window.addEventListener('resize', onScroll)
  update()
}
