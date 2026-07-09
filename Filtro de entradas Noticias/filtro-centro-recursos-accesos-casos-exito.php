<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Shortcode: [bg_resource_center_success_cases_filter]
 * Renders filters + card grid. Data comes from WP REST API.
 */
add_shortcode('bg_resource_center_success_cases_filter', function () {
    ob_start();
    ?>
    <div class="bgrc-news-wrapper">
        <div class="bgrc-news-filters">
            <div class="bgrc-filter-box bgrc-order">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M8 4V20M8 20L4 16M8 20L12 16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                    <path d="M16 20V4M16 4L12 8M16 4L20 8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <select id="bgrc-order">
                    <option value="desc">De mas reciente a mas antiguo</option>
                    <option value="asc">De mas antiguo a mas reciente</option>
                </select>
            </div>

            <div class="bgrc-filter-box bgrc-small">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M16 3V7M8 3V7M3 10H21" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <div class="bgrc-date-placeholder" id="bgrc-placeholder-after"></div>
                <input type="date" id="bgrc-after" min="2000-01-01" aria-label="Fecha desde">
            </div>

            <div class="bgrc-filter-box bgrc-small">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="5" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M16 3V7M8 3V7M3 10H21" stroke="currentColor" stroke-width="1.8"/>
                </svg>
                <div class="bgrc-date-placeholder" id="bgrc-placeholder-before"></div>
                <input type="date" id="bgrc-before" min="2000-01-01" aria-label="Fecha hasta">
            </div>

            <div class="bgrc-filter-box bgrc-search">
                <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="11" cy="11" r="7" stroke="currentColor" stroke-width="1.8"/>
                    <path d="M20 20L17 17" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <input type="text" id="bgrc-search" placeholder="Buscar por palabra clave">
            </div>

            <button id="bgrc-submit" type="button">BUSCAR</button>
        </div>

        <div class="bgrc-news-grid" id="bgrc-news-container"></div>
        <div class="bgrc-news-empty" id="bgrc-news-empty" style="display:none;">No se encontraron resultados.</div>

        <div class="bgrc-news-loadmore-wrap">
            <button id="bgrc-news-loadmore" class="bgrc-news-loadmore" type="button">CARGAR MAS</button>
        </div>
    </div>

    <style>
    .bgrc-news-wrapper{width:100%}
    .bgrc-news-filters{width:100%;display:flex;align-items:center;gap:20px;margin-bottom:50px}
    .bgrc-filter-box{height:50px;border:1px solid #d8dde3;border-radius:12px;background:#fff;display:flex;align-items:center;padding:0 18px;box-sizing:border-box;position:relative;overflow:hidden}
    .bgrc-filter-box svg{width:18px;height:18px;margin-right:12px;flex-shrink:0;position:relative;z-index:2}
    .bgrc-filter-box input,.bgrc-filter-box select{width:100%;border:none !important;outline:none !important;box-shadow:none !important;background:transparent;font-family:"Proxima Nova",Arial,sans-serif;font-size:14px;font-weight:500;color:#000000;position:relative;z-index:2}
    .bgrc-filter-box select{appearance:none;-webkit-appearance:none;-moz-appearance:none;cursor:pointer}
    .bgrc-filter-box input[type="date"]{position:absolute;inset:0;width:100%;height:100%;padding-left:48px;border:none;outline:none;background:transparent;cursor:pointer;z-index:5;appearance:none;-webkit-appearance:none}
    .bgrc-filter-box input[type="date"]::-webkit-calendar-picker-indicator{position:absolute;inset:0;width:100%;height:100%;cursor:pointer;opacity:0}
    .bgrc-date-placeholder{position:absolute;left:48px;top:50%;transform:translateY(-50%);font-family:"Proxima Nova",Arial,sans-serif;font-size:14px;font-weight:500;color:#000000;pointer-events:none}
    .bgrc-order{width:320px}
    .bgrc-small{width:170px}
    .bgrc-search{flex:1}
    .bgrc-filter-box input::placeholder{color:#939393}
    #bgrc-submit{height:50px;padding:0 34px;border:none;background:#000;color:#fff;cursor:pointer;border-radius:2px;font-size:14px;font-weight:600;font-family:"Proxima Nova",Arial,sans-serif}
    .bgrc-news-grid{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:40px 20px;width:100%;align-items:stretch}
    @media(max-width:1024px){.bgrc-news-filters{flex-wrap:wrap}.bgrc-order{width:100%}.bgrc-small{width:calc(50% - 10px)}.bgrc-search{width:100%;flex:unset}.bgrc-news-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:767px){.bgrc-news-filters{flex-direction:column;gap:14px}.bgrc-filter-box,.bgrc-order,.bgrc-small,.bgrc-search,#bgrc-submit{width:100%}.bgrc-news-grid{grid-template-columns:1fr}}
    .bgrc-news-card{position:relative;display:flex;flex-direction:column;width:100%;height:100%}
    .bgrc-news-link{display:flex;flex-direction:column;height:100%;text-decoration:none;color:inherit}
    .bgrc-news-image{width:100%;margin-bottom:18px;overflow:hidden;border-top-left-radius:18px;border-top-right-radius:18px;border-bottom-left-radius:5px;border-bottom-right-radius:5px;background:#f4f4f4;aspect-ratio:16/9}
    .bgrc-news-image img{width:100%;height:100%;display:block;object-fit:cover;object-position:center}
    .bgrc-news-meta{display:flex;align-items:center;gap:12px;margin-bottom:18px;min-width:0}
    .bgrc-news-category{font-size:14px;font-weight:600;letter-spacing:1.5px;text-transform:uppercase;color:#1f1f1f;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
    .bgrc-news-date{font-size:14px;font-weight:400;color:#7a7a7a;white-space:nowrap;flex-shrink:0}
    .bgrc-news-plus{margin-left:auto;width:30px;height:30px;flex-shrink:0;border-radius:50%;background:#d60000;position:relative;top:20px}
    .bgrc-news-plus::before,.bgrc-news-plus::after{content:"";position:absolute;top:50%;left:50%;background:#fff;transform:translate(-50%,-50%)}
    .bgrc-news-plus::before{width:14px;height:3px}
    .bgrc-news-plus::after{width:3px;height:14px}
    .bgrc-news-title{font-size:16px;line-height:1.45;font-weight:600;color:#000;margin:0 0 10px;display:-webkit-box;-webkit-line-clamp:3;line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;min-height:4.35em}
    .bgrc-news-excerpt{font-size:14px;line-height:1.55;font-weight:400;color:#7a7a7a;margin:0;display:-webkit-box;-webkit-line-clamp:3;line-clamp:3;-webkit-box-orient:vertical;overflow:hidden;min-height:4.65em}
    .bgrc-news-loadmore-wrap{display:flex;justify-content:center;margin-top:70px}
    .bgrc-news-loadmore{height:52px;padding:0 38px;border:1px solid #d9d9d9 !important;background:#fff !important;color:#6e6e6e !important;cursor:pointer;display:flex;align-items:center;justify-content:center;line-height:1;font-size:14px;font-weight:400;letter-spacing:.5px;text-transform:uppercase;transition:all .2s ease}.bgrc-news-loadmore:hover{background:#000 !important;color:#fff !important;border-color:#000 !important}
    .bgrc-news-empty{text-align:center;padding:60px 0;font-size:16px}
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const CONFIG = {
            taxonomy: 'categories', // 'categories' or 'tags'
            termId: 57,             // Put here the category/tag ID
            includeCategoryChildren: true,
            maxApiItems: 100,
            offsetPosts: 0,
            excludeCurrentPost: false,
            titleMaxChars: 80,
            excerptMaxChars: 110
        };
        const el = {
            container: document.getElementById('bgrc-news-container'),
            loadMoreBtn: document.getElementById('bgrc-news-loadmore'),
            emptyState: document.getElementById('bgrc-news-empty'),
            order: document.getElementById('bgrc-order'),
            after: document.getElementById('bgrc-after'),
            before: document.getElementById('bgrc-before'),
            search: document.getElementById('bgrc-search'),
            submit: document.getElementById('bgrc-submit'),
            afterPlaceholder: document.getElementById('bgrc-placeholder-after'),
            beforePlaceholder: document.getElementById('bgrc-placeholder-before')
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
        let cachedCategoryIds = null;

        const stripTags = (v) => (v || '').replace(/(<([^>]+)>)/gi, '').trim();
        const normalize = (v) => (v || '').toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, ' ').trim();
        const truncate = (v, n) => (v && v.length > n ? v.slice(0, n).trim() + '...' : (v || ''));
        const formatDateFromKey = (key) => {
            const m = key.match(/^(\d{4})-(\d{2})-(\d{2})$/);
            if (!m) return '';
            const months = [
                'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
                'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
            ];
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
            <article class="bgrc-news-card validacion_usuario">
                <a href="${post.link}" class="bgrc-news-link validacion_usuario">
                    <div class="bgrc-news-image">${img ? `<img src="${img}" alt="">` : ''}</div>
                    <div class="bgrc-news-meta">
                        <div class="bgrc-news-category">${tag}</div>
                        <div class="bgrc-news-date">${formatDateFromKey(dateKey)}</div>
                        <div class="bgrc-news-plus"></div>
                    </div>
                    <h3 class="bgrc-news-title">${title}</h3>
                    <p class="bgrc-news-excerpt">${excerpt}</p>
                </a>
            </article>`;
        }

        async function getDescendantCategoryIds(parentId) {
            const ids = [];
            const queue = [parentId];
            while (queue.length) {
                const currentId = queue.shift();
                const response = await fetch(`/wp-json/wp/v2/categories?parent=${currentId}&per_page=100`);
                const children = await response.json();
                if (!Array.isArray(children) || !children.length) continue;
                children.forEach((term) => {
                    ids.push(term.id);
                    queue.push(term.id);
                });
            }
            return ids;
        }

        async function getCategoryIds() {
            if (Array.isArray(cachedCategoryIds)) return cachedCategoryIds;
            try {
                if (CONFIG.taxonomy !== 'categories' || !CONFIG.includeCategoryChildren) {
                    cachedCategoryIds = [CONFIG.termId];
                    return cachedCategoryIds;
                }
                const descendants = await getDescendantCategoryIds(CONFIG.termId);
                cachedCategoryIds = [CONFIG.termId, ...descendants];
                return cachedCategoryIds;
            } catch (error) {
                cachedCategoryIds = [CONFIG.termId];
                return cachedCategoryIds;
            }
        }

        async function getPosts() {
            const termIds = await getCategoryIds();
            const taxonomyParam = CONFIG.taxonomy === 'tags' ? 'tags' : 'categories';
            let url = `/wp-json/wp/v2/posts?_embed&${taxonomyParam}=${termIds.join(',')}&per_page=${CONFIG.maxApiItems}&orderby=date&order=desc`;
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





