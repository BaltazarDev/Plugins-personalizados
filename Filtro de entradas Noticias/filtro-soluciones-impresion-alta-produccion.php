<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode: [bg_high_production_print_filter]
 * Variante aislada para "Blog Soluciones de impresion de alta produccion".
 */
add_shortcode('bg_high_production_print_filter', function () {
    ob_start();
    ?>
    <div class="bgp-news-wrapper">
        <div class="bgp-news-filters">
            <div class="bgp-filter-box bgp-order">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M8 4V20M8 20L4 16M8 20L12 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M16 20V4M16 4L12 8M16 4L20 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <select id="bgp-order">
                    <option value="desc">De mas reciente a mas antiguo</option>
                    <option value="asc">De mas antiguo a mas reciente</option>
                </select>
            </div>

            <div class="bgp-filter-box bgp-small">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M16 3V7M8 3V7M3 10H21" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <div class="bgp-date-placeholder" id="bgp-placeholder-after"></div>
                <input type="date" id="bgp-after" min="2000-01-01" aria-label="Fecha desde">
            </div>

            <div class="bgp-filter-box bgp-small">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M16 3V7M8 3V7M3 10H21" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <div class="bgp-date-placeholder" id="bgp-placeholder-before"></div>
                <input type="date" id="bgp-before" min="2000-01-01" aria-label="Fecha hasta">
            </div>

            <div class="bgp-filter-box bgp-search">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M20 20L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <input type="text" id="bgp-search" placeholder="Buscar por palabra clave">
            </div>

            <button id="bgp-submit" type="button">BUSCAR</button>
        </div>

        <div class="bgp-news-grid" id="bgp-container"></div>
        <div class="bgp-news-empty" id="bgp-empty" style="display:none;">No se encontraron resultados.</div>

        <div class="bgp-news-loadmore-wrap">
            <button id="bgp-loadmore" class="bgp-news-loadmore" type="button">CARGAR MAS</button>
        </div>
    </div>

    <style>
    .bgp-news-wrapper{width:100%}
    .bgp-news-filters{width:100%;display:flex;align-items:center;gap:20px;margin-bottom:50px}
    .bgp-filter-box{height:50px;border:1px solid #d8dde3;border-radius:12px;background:#fff;display:flex;align-items:center;padding:0 18px;box-sizing:border-box;position:relative;overflow:hidden}
    .bgp-filter-box svg{width:18px;height:18px;margin-right:12px;flex-shrink:0;position:relative;z-index:2}
    .bgp-filter-box input,.bgp-filter-box select{width:100%;border:none !important;outline:none !important;box-shadow:none !important;background:transparent;font-family:"Proxima Nova",Arial,sans-serif;font-size:14px;font-weight:500;color:#000000;position:relative;z-index:2}
    .bgp-filter-box select{appearance:none;-webkit-appearance:none;-moz-appearance:none;cursor:pointer}
    .bgp-filter-box input[type="date"]{position:absolute;inset:0;width:100%;height:100%;padding-left:48px;border:none;outline:none;background:transparent;cursor:pointer;z-index:5;appearance:none;-webkit-appearance:none}
    .bgp-filter-box input[type="date"]::-webkit-calendar-picker-indicator{position:absolute;inset:0;width:100%;height:100%;cursor:pointer;opacity:0}
    .bgp-date-placeholder{position:absolute;left:48px;top:50%;transform:translateY(-50%);font-family:"Proxima Nova",Arial,sans-serif;font-size:14px;font-weight:500;color:#000000;pointer-events:none}
    .bgp-order{width:320px}
    .bgp-small{width:170px}
    .bgp-search{flex:1}
    .bgp-filter-box input::placeholder{color:#939393}
    #bgp-submit{height:50px;padding:0 34px;border:none;background:#000;color:#fff;cursor:pointer;border-radius:2px;font-size:14px;font-weight:600;font-family:"Proxima Nova",Arial,sans-serif}
    .bgp-news-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:40px 20px;width:100%;align-items:stretch}
    @media(max-width:1024px){.bgp-news-filters{flex-wrap:wrap}.bgp-order{width:100%}.bgp-small{width:calc(50% - 10px)}.bgp-search{width:100%;flex:unset}.bgp-news-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:767px){.bgp-news-filters{flex-direction:column;gap:14px}.bgp-filter-box,.bgp-order,.bgp-small,.bgp-search,#bgp-submit{width:100%}.bgp-news-grid{grid-template-columns:1fr}}
    .bgp-news-card{position:relative;display:flex;flex-direction:column;width:100%;height:100%}
    .bgp-news-link{display:flex;flex-direction:column;height:100%;text-decoration:none;color:inherit}
    .bgp-news-image{width:100%;margin-bottom:18px;overflow:hidden;border-top-left-radius:18px;border-top-right-radius:18px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;background:#f4f4f4;aspect-ratio:16/9}
    .bgp-news-image img{width:100%;height:100%;display:block;object-fit:cover;object-position:center}
    .bgp-news-meta{display:flex;align-items:center;gap:12px;margin-bottom:18px;min-width:0}
    .bgp-news-category{font-size:14px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#1f1f1f;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .bgp-news-date{font-size:14px;font-weight:400;color:#7a7a7a;white-space:nowrap;flex-shrink:0}
    .bgp-news-plus{margin-left:auto;width:30px;height:30px;flex-shrink:0;border-radius:50%;background:#d60000;position:relative;top:20px}
    .bgp-news-plus::before,.bgp-news-plus::after{content:"";position:absolute;top:50%;left:50%;background:#fff;transform:translate(-50%,-50%)}
    .bgp-news-plus::before{width:14px;height:3px}
    .bgp-news-plus::after{width:3px;height:14px}
    .bgp-news-title{font-size:16px;line-height:1.45;font-weight:600;color:#000;margin:0 0 10px;display:-webkit-box;-webkit-line-clamp:3;line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;min-height:4.35em}
    .bgp-news-excerpt{font-size:14px;line-height:1.55;font-weight:400;color:#7a7a7a;margin:0;display:-webkit-box;-webkit-line-clamp:3;line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;min-height:4.65em}
    .bgp-news-loadmore-wrap{display:flex;justify-content:center;margin-top:70px}
    .bgp-news-loadmore{height:52px;padding:0 38px;border:1px solid #d9d9d9 !important;background:#fff !important;color:#6e6e6e !important;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;font-size:14px;font-weight:400;letter-spacing:.5px;text-transform:uppercase;transition:all .2s ease}.bgp-news-loadmore:hover{background:#000 !important;color:#fff !important;border-color:#000 !important}
    .bgp-news-empty{text-align:center;padding:60px 0;font-size:16px}
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const CONFIG = {
            categoryId: 30,
            maxApiItems: 100,
            offsetPosts: 1,
            excludeCurrentPost: true,
            titleMaxChars: 80,
            excerptMaxChars: 110
        };
        const el = {
            container: document.getElementById('bgp-container'),
            loadMoreBtn: document.getElementById('bgp-loadmore'),
            emptyState: document.getElementById('bgp-empty'),
            order: document.getElementById('bgp-order'),
            after: document.getElementById('bgp-after'),
            before: document.getElementById('bgp-before'),
            search: document.getElementById('bgp-search'),
            submit: document.getElementById('bgp-submit'),
            afterPlaceholder: document.getElementById('bgp-placeholder-after'),
            beforePlaceholder: document.getElementById('bgp-placeholder-before')
        };

        if (!el.container || !el.loadMoreBtn) return;

        const currentPostId = document.body.classList.contains('single-post')
            ? (document.body.className.match(/postid-(\d+)/) || [])[1]
            : null;

        const state = {
            page: 1,
            loading: false,
            hasMore: true,
            filters: { order: 'desc', search: '', after: '', before: '' }
        };

        const stripTags = (v) => (v || '').replace(/(<([^>]+)>)/gi, '').trim();
        const normalize = (v) => (v || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, ' ').trim();
        const truncate = (v, n) => (v && v.length > n ? v.slice(0, n).trim() + '...' : (v || ''));
        const formatDateFromKey = (key) => {
            const m = key.match(/^(\d{4})-(\d{2})-(\d{2})$/);
            if (!m) return '';
            const months = ['enero','febrero','marzo','abril','mayo','junio','julio','agosto','septiembre','octubre','noviembre','diciembre'];
            const day = String(Number(m[3]));
            const month = months[Number(m[2]) - 1] || '';
            const year = m[1];
            return `${day} de ${month} de ${year}`;
        };
        const perPage = () => (window.innerWidth <= 767 ? 3 : window.innerWidth <= 1024 ? 6 : 12);
        const toPostDateKey = (dateString) => {
            if (!dateString) return '';
            const raw = String(dateString);
            const iso = raw.match(/^(\d{4})-(\d{2})-(\d{2})/);
            return iso ? `${iso[1]}-${iso[2]}-${iso[3]}` : '';
        };
        const normalizeInputDateKey = (value) => {
            if (!value) return '';
            const raw = String(value).trim();
            const isoMatch = raw.match(/^(\d{4})-(\d{2})-(\d{2})$/);
            if (isoMatch) return `${isoMatch[1]}-${isoMatch[2]}-${isoMatch[3]}`;
            const latamMatch = raw.match(/^(\d{2})\/(\d{2})\/(\d{4})$/);
            if (latamMatch) return `${latamMatch[3]}-${latamMatch[2]}-${latamMatch[1]}`;
            return '';
        };
        const getPostDateKey = (post) => toPostDateKey(post?.date);
        function syncDatePlaceholder(input, placeholder) {
            placeholder.style.display = input.value ? 'none' : 'block';
        }

        function createCard(post) {
            const img = post._embedded?.['wp:featuredmedia']?.[0]?.source_url || '';
            const title = truncate(stripTags(post.title?.rendered), CONFIG.titleMaxChars);
            const excerpt = truncate(stripTags(post.excerpt?.rendered), CONFIG.excerptMaxChars);
            const tagRaw = post._embedded?.['wp:term']?.[1]?.[0]?.name || 'NOTICIA';
            const tag = truncate(tagRaw, 18);
            const dateKey = getPostDateKey(post);
            return `
            <article class="bgp-news-card">
                <a href="${post.link}" class="bgp-news-link">
                    <div class="bgp-news-image">${img ? `<img src="${img}" alt="">` : ''}</div>
                    <div class="bgp-news-meta">
                        <div class="bgp-news-category">${tag}</div>
                        <div class="bgp-news-date">${formatDateFromKey(dateKey)}</div>
                        <div class="bgp-news-plus"></div>
                    </div>
                    <h3 class="bgp-news-title">${title}</h3>
                    <p class="bgp-news-excerpt">${excerpt}</p>
                </a>
            </article>`;
        }

        async function getPosts() {
            let url = `/wp-json/wp/v2/posts?_embed&categories=${CONFIG.categoryId}&per_page=${CONFIG.maxApiItems}&orderby=date&order=desc`;
            if (CONFIG.excludeCurrentPost && currentPostId) url += `&exclude=${currentPostId}`;
            const response = await fetch(url);
            const posts = await response.json();
            if (!Array.isArray(posts)) return [];
            return posts.slice(Math.max(0, Number(CONFIG.offsetPosts) || 0));
        }

        function applyClientFilters(posts) {
            const search = normalize(state.filters.search);
            let result = posts;

            if (search) {
                result = result.filter((post) => {
                    const title = normalize(stripTags(post.title?.rendered));
                    const excerpt = normalize(stripTags(post.excerpt?.rendered));
                    const tags = normalize((post._embedded?.['wp:term']?.[1] || []).map((t) => t.name).join(' '));
                    return title.includes(search) || excerpt.includes(search) || tags.includes(search);
                });
            }

            if (state.filters.after) {
                result = result.filter((post) => getPostDateKey(post) >= state.filters.after);
            }

            if (state.filters.before) {
                result = result.filter((post) => getPostDateKey(post) <= state.filters.before);
            }

            if (state.filters.order === 'asc') {
                result = result.slice().reverse();
            }

            return result;
        }

        async function loadPosts() {
            if (state.loading || !state.hasMore) return;
            state.loading = true;

            try {
                const allPosts = applyClientFilters(await getPosts());
                const size = perPage();
                const start = (state.page - 1) * size;
                const end = start + size;
                const chunk = allPosts.slice(start, end);

                if (state.page === 1 && !chunk.length) {
                    el.emptyState.style.display = 'block';
                    el.loadMoreBtn.style.display = 'none';
                    return;
                }

                el.container.insertAdjacentHTML('beforeend', chunk.map(createCard).join(''));
                state.page += 1;

                if (end >= allPosts.length) {
                    state.hasMore = false;
                    el.loadMoreBtn.style.display = 'none';
                }
            } catch (error) {
                console.error(error);
            } finally {
                state.loading = false;
            }
        }

        function applyFilters() {
            const afterKey = normalizeInputDateKey(el.after.value);
            const beforeKey = normalizeInputDateKey(el.before.value);
            state.filters = {
                order: el.order.value,
                search: normalize(el.search.value),
                after: afterKey,
                before: beforeKey
            };
            state.page = 1;
            state.hasMore = true;
            el.container.innerHTML = '';
            el.emptyState.style.display = 'none';
            el.loadMoreBtn.style.display = 'inline-flex';
            loadPosts();
        }

        el.after.addEventListener('input', function () { syncDatePlaceholder(el.after, el.afterPlaceholder); });
        el.before.addEventListener('input', function () { syncDatePlaceholder(el.before, el.beforePlaceholder); });
        el.loadMoreBtn.addEventListener('click', loadPosts);
        el.submit.addEventListener('click', applyFilters);
        el.order.addEventListener('change', applyFilters);

        loadPosts();
    });
    </script>
    <?php
    return ob_get_clean();
});



