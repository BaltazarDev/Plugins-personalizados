<!-- SWIPER CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

<div class="bg-news-wrapper">

    <div class="swiper bg-news-swiper">

        <div class="swiper-wrapper" id="bg-news-container"></div>

        <!-- CONTROLES -->
        <div class="bg-news-nav">
            <div class="bg-news-prev">‹</div>
            <div class="bg-news-next">›</div>
        </div>

    </div>

</div>

<style>
.bg-news-wrapper {
    width: 100%;
    position: relative;
}

/* SWIPER */
.bg-news-swiper {
    width: 100%;
    padding-bottom: 40px;
}

/* CARD */
.bg-news-card {
    background: transparent !important;
    position: relative;
    overflow: hidden;
    height: 100%;
}

.bg-news-card a {
    display: flex !important;
    flex-direction: column;
    height: 100%;
}

/* IMAGEN */
.bg-news-image {
    width: 100%;
    height: 220px;
    overflow: hidden;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
}

.bg-news-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}

/* CONTENIDO */
.bg-news-content {
    padding: 10px 0;
    min-height: 72px;
}

/* TITULO */
.bg-news-title {
    font-family: "Proxima Nova", Arial, sans-serif;
    font-size: 15px;
    font-weight: 500;
    color: #000;
    line-height: 1.4;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* BOTÓN + */
.bg-news-plus {
    position: absolute;
    bottom: 1px;
    right: 10px;
    width: 26px;
    height: 26px;
    background: #cc0000;
    border-radius: 50%;
}

/* + */
.bg-news-plus::before,
.bg-news-plus::after {
    content: "";
    position: absolute;
    background: #fff;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.bg-news-plus::before {
    width: 14px;
    height: 3px;
}

.bg-news-plus::after {
    width: 3px;
    height: 14px;
}

/* CONTROLES */
.bg-news-nav {
    position: absolute;
    bottom: 0;
    right: 0;

    display: flex;
    gap: 10px;

    z-index: 20;
}

/* BOTONES */
.bg-news-prev,
.bg-news-next {
    width: 28px;
    height: 28px;

    border-radius: 50%;
    background: #f0f0f0;

    display: flex;
    align-items: center;
    justify-content: center;

    cursor: pointer;

    font-size: 18px;
    color: #999;

    transition: .2s ease;
}

.bg-news-prev:hover,
.bg-news-next:hover {
    background: #ddd;
    color: #000;
}
</style>

<!-- SWIPER JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
setTimeout(async function(){

    const container = document.getElementById("bg-news-container");

    try {

       // OBTENER ID DEL POST ACTUAL
const bodyClasses = document.body.className;
const match = bodyClasses.match(/postid-(\d+)/);

const currentPostId = match ? match[1] : 0;

// FETCH EXCLUYENDO EL ACTUAL
const res = await fetch(
    `/wp-json/wp/v2/posts?categories=30&exclude=${currentPostId}&per_page=8&_embed`
);

        const posts = await res.json();

        if (!posts.length) {
            container.innerHTML = "<p>No hay posts</p>";
            return;
        }

        container.innerHTML = posts.map(post => {

            const img = post._embedded?.["wp:featuredmedia"]?.[0]?.source_url || "";
            const title = post.title.rendered;
            const link = post.link;

            return `
                <div class="swiper-slide">

                    <article class="bg-news-card">
                        <a href="${link}" style="text-decoration:none;color:inherit;">

                            <div class="bg-news-image">
                                ${img ? `<img src="${img}" alt="">` : ""}
                            </div>

                            <div class="bg-news-content">
                                <h3 class="bg-news-title">${title}</h3>
                            </div>

                            <div class="bg-news-plus"></div>

                        </a>
                    </article>

                </div>
            `;

        }).join("");

        // INIT SWIPER
        new Swiper('.bg-news-swiper', {

            slidesPerView: 4,
            spaceBetween: 20,

            navigation: {
                nextEl: '.bg-news-next',
                prevEl: '.bg-news-prev',
            },

            breakpoints: {

                0: {
                    slidesPerView: 1
                },

                768: {
                    slidesPerView: 2
                },

                1025: {
                    slidesPerView: 4
                }

            }

        });

    } catch (error) {

        container.innerHTML = "<p>Error cargando noticias</p>";
        console.error(error);

    }

}, 500);
</script>
