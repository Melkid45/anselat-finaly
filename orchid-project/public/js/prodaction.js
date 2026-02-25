let windowWidth = window.innerWidth;
console.log(windowWidth)
let titles = document.querySelectorAll('.main-title')
let isMobile = window.innerWidth <= 780;
let lenis = null;
let rafId = null;

try {
    lenis = new Lenis({
        lerp: 0.1,
        smooth: true,
        direction: 'vertical',
        smoothWheel: true,
        wheelMultiplier: 1.2,
        touchMultiplier: 1.5,
    });

    function raf(time) {
        lenis.raf(time);
        rafId = requestAnimationFrame(raf);
    }

    rafId = requestAnimationFrame(raf);

} catch (error) {
    fallbackScroll();
}

function fallbackScroll() {
    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
    console.log('Fallback to native scroll');
}

// Works
class WorksFilter {
    constructor() {
        this.buttons = document.querySelectorAll('.category-btn');
        this.path = window.location.pathname;
        this.init();
    }

    init() {
        if (!this.buttons.length) {
            return;
        }

        this.buttons.forEach((button) => {
            if (!button.classList.contains('is-active')) {
                button.classList.remove('current');
            } else {
                button.classList.add('current');
            }
        });
    }
}
document.addEventListener('DOMContentLoaded', () => {
    new WorksFilter();
    Titles();
    ScrollTop();
    OpenCalculate();
    buttonAnimatedFunc();
    updateMarqueeAnimation();
    WorkContainerAnimated();
    IndicatorsAnimate();
    burgerMenu();
    initAnchorLinks();
    navigate();
});


function Titles() {
    titles = document.querySelectorAll('.main-title');
    if (titles.length != 0) {
        titles.forEach((el) => {
            if (el.classList.contains('animated')) return;

            let splitTitle = el.textContent.split('');
            el.textContent = '';
            splitTitle.forEach((letter, index) => {
                let span = document.createElement('span')
                span.style.minWidth = '20px';
                span.textContent = letter;
                if (letter == ' ') {
                    span.classList.add('walker')
                }
                el.appendChild(span)
            })

            let spans = el.querySelectorAll('span')
            spans.forEach((span) => {
                gsap.set(span, {
                    opacity: 0,
                    yPercent: 15,
                    rotate: 5,
                })
            })

            el.classList.add('animated');
        });
    }
}

// Menu Header
function buttonAnimatedFunc() {
    if (windowWidth <= 780) return;
    let linkElements = document.querySelectorAll('.hero__menu li a')
    if (linkElements.length != 0) {
        linkElements.forEach((element) => {
            if (element.classList.contains('splited')) return;
            let word = element.textContent;
            element.textContent = '';
            for (let index = 0; index < 2; index++) {
                let div = document.createElement('div')
                div.textContent = word;
                element.appendChild(div)
            }
            let height = element.querySelector('div').clientHeight;
            element.parentNode.style.maxHeight = `${height}px`;
            element.classList.add('splited');
        })
    }
    let buttonAnimated = document.querySelectorAll('.button--fade')
    if (buttonAnimated.length != 0) {
        buttonAnimated.forEach((element, index) => {
            if (element.classList.contains('splited')) return;
            let text = element.textContent
            element.textContent = '';
            let divWrap = document.createElement('div')
            divWrap.classList.add('wrap-animation')
            let div = document.createElement('div')
            div.classList.add('animated-fade')
            for (let index = 0; index < 2; index++) {
                let span = document.createElement('span')
                span.textContent = text
                div.appendChild(span)
            }
            divWrap.appendChild(div)
            element.appendChild(divWrap);
            let height = divWrap.querySelector('div span').clientHeight;
            divWrap.style.maxHeight = `${height}px`;
            element.classList.add('splited')
        })
    }
}
const container = document.querySelector('header .container');
function navigate() {
    const stripLocalePrefix = (value) => (value || '/').replace(/^\/(lv|en|ru)(?=\/|$)/, '') || '/';
    const path = stripLocalePrefix(window.location.pathname);
    const body = document.body;

    const pagePaths = {
        home: stripLocalePrefix(body?.dataset.homePath || '/'),
        about: stripLocalePrefix(body?.dataset.aboutPath || '/about'),
        works: stripLocalePrefix(body?.dataset.worksPath || '/works'),
        material: stripLocalePrefix(body?.dataset.materialPath || '/material'),
        contact: stripLocalePrefix(body?.dataset.contactsPath || '/contacts'),
    };

    let currentRoute = null;
    if (path === pagePaths.home) currentRoute = 'home';
    if (path === pagePaths.about) currentRoute = 'about';
    if (path === pagePaths.material) currentRoute = 'material';
    if (path === pagePaths.contact) currentRoute = 'contact';
    if (path === pagePaths.works || path.startsWith(`${pagePaths.works}/`)) currentRoute = 'works';

    document.querySelectorAll('[data-route]').forEach(item => {
        item.classList.remove('current');
        if (item.dataset.route === currentRoute) {
            item.classList.add('current');
        }
    });
    if (currentRoute === 'home' && container) {
        container.classList.add('home-container');
    } else if (container) {
        container.classList.remove('home-container');
    }
}

function syncHeadFromHtml(nextHtml) {
    if (!nextHtml) return;

    const parser = new DOMParser();
    const doc = parser.parseFromString(nextHtml, 'text/html');
    const nextHead = doc.head;
    if (!nextHead) return;

    const nextTitle = nextHead.querySelector('title');
    if (nextTitle) {
        document.title = nextTitle.textContent || '';
    }

    const managedSelectors = [
        'meta[name="description"]',
        'meta[name="keywords"]',
        'meta[name="twitter:card"]',
        'meta[name="twitter:title"]',
        'meta[name="twitter:description"]',
        'meta[name="twitter:image"]',
        'meta[property="og:type"]',
        'meta[property="og:title"]',
        'meta[property="og:description"]',
        'meta[property="og:url"]',
        'meta[property="og:image"]',
        'link[rel="canonical"]',
    ];

    managedSelectors.forEach((selector) => {
        document.head.querySelectorAll(selector).forEach((el) => el.remove());
        nextHead.querySelectorAll(selector).forEach((el) => {
            document.head.appendChild(el.cloneNode(true));
        });
    });
}

barba.init({
    transitions: [{
        name: 'page-transition',

        async leave(data) {
            const done = this.async();
            lenis.stop();
            const darkOverlay = document.createElement('div');
            darkOverlay.className = 'overlay-dark';
            document.body.appendChild(darkOverlay);

            const whiteOverlay = document.createElement('div');
            whiteOverlay.className = 'overlay-white';
            document.body.appendChild(whiteOverlay);

            gsap.set(whiteOverlay, { yPercent: 100 });

            await gsap.to(darkOverlay, {
                opacity: 0.6,
                duration: 0.4,
                ease: "power1.inOut"
            });

            await gsap.to(whiteOverlay, {
                yPercent: 0,
                duration: 0.6,
                ease: "power1.inOut"
            });

            done();
        },

        async enter(data) {
            await new Promise(resolve => setTimeout(resolve, 100));
            syncHeadFromHtml(data.next.html);
            lenis.start();
            let name = data.next.namespace;
            gsap.fromTo(data.next.container,
                { opacity: 0, },
                { opacity: 1, duration: 0.6, ease: "power1.out" }
            );
            Titles();

            gsap.to('.overlay-white', {
                opacity: 0,
                duration: 0.4,
                ease: "power1.inOut",
                onComplete: () => {
                    const white = document.querySelector('.overlay-white');
                    if (white) white.remove();
                }
            });

            gsap.to('.overlay-dark', {
                opacity: 0,
                duration: 0.4,
                ease: "power1.inOut",
                onComplete: () => {
                    const dark = document.querySelector('.overlay-dark');
                    if (dark) dark.remove();
                }
            });
        },
        afterEnter(data) {
            setTimeout(() => {
                Titles();
                buttonAnimatedFunc();
                new WorksFilter();
                navigate();
            }, 200);
        }

    }]
});
function ScrollTop() {
    let buttonTop = document.querySelector('.scroll__top')
    if (!buttonTop) return;
    buttonTop.addEventListener('click', function () {
        lenis.scrollTo(0, {
            duration: 0.5,
        });
    })
}
barba.hooks.after(() => {
    if (lenis) {
        if (rafId) {
            cancelAnimationFrame(rafId);
        }

        function raf(time) {
            lenis.raf(time);
            rafId = requestAnimationFrame(raf);
        }

        rafId = requestAnimationFrame(raf);
        lenis.resize();
        lenis.raf();
        lenis.scrollTo(0, {
            duration: 0.1,
        });
    }
    SwitchLang();
    burgerMenu();
    OpenCalculate();
    setTimeout(() => {
        Titles();
        buttonAnimatedFunc();
        WorkContainerAnimated();
        new WorksFilter();
        animateHero();
        updateMarqueeAnimation();
        IndicatorsAnimate();
    }, 500);
    setTimeout(() => {
        observerFadeIn();
    }, 1000);
    initGalleries();
});

barba.hooks.enter(() => {
    Titles();
    animateHero();
    buttonAnimatedFunc();
    setTimeout(() => {
        WorkContainerAnimated();
    }, 300);
    updateMarqueeAnimation();
    IndicatorsAnimate();
    new WorksFilter();
    setTimeout(() => {
        observerFadeIn();
    }, 1000);
});

function observerFadeIn() {
    function onEntry(e) {
        e.forEach((e => {
            e.isIntersecting && e.target.classList.add("show")
        }))
    }

    let options = { threshold: [.5] },
        observer = new IntersectionObserver(onEntry, options),
        elements = document.querySelectorAll(".el--fade, .el--opacity, .el--left");
    for (let e of elements) observer.observe(e);
}

function animateHero() {
    let spans = document.querySelectorAll('.hero__title span')
    gsap.to(spans, {
        opacity: 1,
        rotate: 0,
        yPercent: 0,
        duration: 0.4,
        ease: 'power1.inOut',
        stagger: {
            from: 'start',
            each: 0.15
        }
    })
}


// Works
function WorkContainerAnimated() {
    let workContainer = document.querySelector('.works-container')
    if (workContainer) {
        let works = new Splide('.works-container', {
            perMove: 1,
            arrows: false,
            gap: '24px',
            pagination: false,
            live: true,
            autoplay: true,
            interval: 5000,
        }).mount()
    }


    let works = document.querySelectorAll('.object')
    works.forEach((work) => {
        let circle = work.querySelector('.circle');
        if (!circle) return;

        circle.style.position = 'absolute';
        circle.style.left = '0';
        circle.style.top = '0';

        work.addEventListener('mouseover', () => {
            circle.classList.add('animated');
        });

        work.addEventListener('mouseout', () => {
            circle.classList.remove('animated');
        });

        work.addEventListener('mousemove', (e) => {
            const rect = work.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            gsap.to(circle, {
                left: x,
                top: y,
                duration: 0.3,
                ease: "power2.out"
            });
        });
    });
}


// Margue


function updateMarqueeAnimation() {
    const marqueeContainer = document.getElementById('marqueeContainer');
    const strokeItems = document.querySelectorAll('.partners__body-frame');

    if (!marqueeContainer || strokeItems.length === 0) return;

    const itemWidth = strokeItems[0].scrollWidth;
    const containerWidth = marqueeContainer.parentElement.offsetWidth;

    const gap = parseFloat(getComputedStyle(marqueeContainer).gap) || 0;
    const totalItemWidth = itemWidth + gap;

    const translateValue = -((totalItemWidth / containerWidth) * 100);

    let style = document.getElementById('dynamic-marquee');
    if (!style) {
        style = document.createElement('style');
        style.id = 'dynamic-marquee';
        document.head.appendChild(style);
    }

    style.innerHTML = `
        @keyframes animPartners {
            0% { 
                transform: translateX(0);
                opacity: 1;
            }
            95% {
                opacity: 1;
            }
            100% { 
                transform: translateX(${translateValue}%);
                opacity: 1;
            }
        }
        
        .stroke {
            animation-fill-mode: both;
        }
    `;

    marqueeContainer.style.animation = 'none';
    setTimeout(() => {
        marqueeContainer.style.animation = '';
    }, 10);
}

document
    .getElementById('marqueeContainer')
    ?.addEventListener('animationiteration', function () {
        this.style.opacity = '1';
    });

let resizeTimeout;

function debouncedUpdate() {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(updateMarqueeAnimation, 250);
}

setTimeout(updateMarqueeAnimation, 1000);

window.addEventListener('resize', debouncedUpdate);


// Indicators
function IndicatorsAnimate() {
    let Indititles = document.querySelectorAll('.counters__item > div');
    if (Indititles.length != 0) {
        Indititles.forEach((item) => {
            if (!item.querySelector('.animate-number')) {
                const originalText = item.textContent.trim();
                item.textContent = '';

                const digits = originalText.split('');

                digits.forEach((char) => {
                    let div = document.createElement('div');

                    if (char === '+' || char === ' ') {
                        div.textContent = char;
                        item.appendChild(div);
                        return;
                    }

                    if (!isNaN(char)) {
                        div.classList.add('animate-number');

                        div.style.overflow = 'visible';
                        div.dataset.target = char;

                        for (let i = 0; i < 10; i++) {
                            let span = document.createElement('span');
                            span.textContent = i;
                            div.appendChild(span);
                        }
                        item.appendChild(div);
                        let height = div.querySelector('span').clientHeight;
                        div.style.maxHeight = `${height}px`;
                    } else {
                        div.textContent = char;
                        item.appendChild(div);
                    }
                });
            }
            const numbers = item.querySelectorAll('.animate-number');

            numbers.forEach((num, index) => {
                const targetDigit = parseInt(num.dataset.target);
                if (isNaN(targetDigit)) return;

                gsap.fromTo(
                    num,
                    { yPercent: 0 },
                    {
                        yPercent: -(targetDigit * 100),
                        duration: 2,
                        ease: 'power2.out',
                        scrollTrigger: {
                            trigger: item,
                            start: 'top 80%',
                        },
                        delay: index * 0.15,
                    }
                );
            });
            item.classList.add('splited')
        });
    }
}


// Preloader
const preloader = document.getElementsByClassName('preloader');
gsap.registerPlugin(DrawSVGPlugin);

const letters = document.querySelectorAll(".letters");
const greenLines = document.querySelectorAll(".green-lines");

gsap.set([letters], { drawSVG: "0%" });
gsap.set(greenLines, { opacity: 0 });
const tl = gsap.timeline({
    defaults: { duration: 1.2, ease: "power2.out" },
    onComplete: () => {
        gsap.to('.preloader', {
            opacity: 0,
            pointerEvents: 'none',
            duration: 0.5,
            onComplete: () => {
                if (titles) {
                    animateHero();
                }
                setTimeout(() => {
                    observerFadeIn();
                }, 1000);
            }
        });
    }
});

tl.to(letters, {
    drawSVG: "100%",
    stagger: {
        each: 0.08,
        from: 'center',
    }
}).to(letters, {
    fill: '#000',
    duration: 1,
    ease: 'power1.inOut',
    stagger: {
        each: 0.05,
        from: 'center'
    }
})

tl.to(greenLines, {
    fill: '#B8F13C',
    duration: 1,
    opacity: 1,
    ease: 'power1.inOut',
    stagger: {
        each: 0.05,
        from: 'center'
    }
})

if (document.getElementById('telegramForm')) {
    document.getElementById('telegramForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const form = e.target;
        const button = form.querySelector('button[type="submit"]');
        const originalText = button.textContent;

        // Показываем loading
        button.textContent = 'Nosūta...';
        button.disabled = true;

        // Скрываем ошибки
        document.querySelectorAll('.error-message').forEach(el => el.textContent = '');

        try {
            const formData = new FormData(form);

            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                // Успех
                form.reset();
                showNotification(data.message || 'Pieprasījums nosūtīts veiksmīgi!', 'success');
            } else {
                // Показываем ошибки валидации
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}-error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                        }
                    });
                }
                showNotification(data.message || 'Lūdzu izlabojiet kļūdas formā', 'error');
            }

        } catch (error) {
            console.error('Error:', error);
            showNotification('Radās kļūda, mēģiniet vēlreiz', 'error');
        } finally {
            button.textContent = originalText;
            button.disabled = false;
        }
    });

    function showNotification(message, type) {
        // Простая нотификация
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 5px;
        color: white;
        z-index: 10000;
        font-weight: bold;
        background: ${type === 'success' ? '#B8F13C' : '#f44336'};
    `;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 5000);
    }
}


// Mobile

function burgerMenu() {
    let burger = document.querySelector('.hero__mobile-menu')
    let close = document.querySelector('.mobile-menu__close')
    let headerMenu = document.querySelector('.mobile-menu')
    let header = document.querySelector('.header')
    let headerList = document.querySelectorAll('.mobile-menu__menu a')
    if (!burger || !close || !headerMenu) return;
    burger.addEventListener('click', function (e) {
        burger.classList.add('active')
        headerMenu.classList.add('active')
    })
    close.addEventListener('click', function (e) {
        burger.classList.remove('active')
        headerMenu.classList.remove('active')
    })

    if (headerList.length != 0) {
        headerList.forEach((el) => {
            el.addEventListener('click', function () {
                burger.classList.remove('active')
                headerMenu.classList.remove('active')
            })
        })
    }
}


function OpenCalculate() {
    $('.open-calculate').on('click', function (e) {
        e.preventDefault();
        lenis.stop()
        document.querySelector('body').style.overflow = 'hidden';
        $('.calc').fadeIn(500);
    });

    $('.calc__close').on('click', function () {
        lenis.start();
        document.querySelector('body').style.overflow = 'auto';
        $('.calc').fadeOut(500);
    });
}




document.addEventListener('DOMContentLoaded', function () {
    const widthInput = document.querySelector('.width-input');
    const heightInput = document.querySelector('.height-input');
    const depthInput = document.querySelector('.depth-input');
    const priceOutput = document.querySelector('.price-output');
    const categoryRadios = document.querySelectorAll('input[name="category"]');
    let pricePerMeter = 700;
    if (!widthInput || !heightInput || !depthInput) return;
    function calculatePrice() {
        const width = parseFloat(widthInput.value) || 0;
        const height = parseFloat(heightInput.value) || 0;
        const depth = parseFloat(depthInput.value) || 0;
        const area = width * height * depth;
        const totalPrice = ((width / 1000) * pricePerMeter).toFixed(2);
        const formattedPrice = totalPrice;
        if (width != 0 && height != 0 && depth != 0) {
            priceOutput.textContent = `${formattedPrice} €`;
        } else {
            priceOutput.textContent = `${0} €`;
        }
    }
    function formatPrice(price) {
        return new Intl.NumberFormat('lv-LV', {
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(price);
    }
    [widthInput, heightInput, depthInput].forEach(input => {
        input.addEventListener('input', calculatePrice);
    });
    calculatePrice();
});

document.querySelectorAll('.material-item').forEach((el, index) => {
    el.addEventListener('click', function (e) {
        if (index == 0) return;
        let TextFirst = document.querySelectorAll('.material-item')[0].textContent
        let TextChoose = el.textContent
        document.querySelectorAll('.material-item')[0].textContent = TextChoose
        el.textContent = TextFirst
    })
})



function initAnchorLinks() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            e.preventDefault();

            const href = anchor.getAttribute('href');
            if (!href || href === '#') return;
            const currentPath = window.location.pathname;
            const anchorPart = href.split('?')[0];
            scrollToAnchor(anchorPart);
        });
    });
}

function scrollToAnchor(anchorId) {
    const cleanAnchorId = anchorId.split('?')[0];

    const target = document.querySelector(cleanAnchorId);
    if (!target) {
        return;
    }

    const parent = target.parentNode;
    let scrollTarget = target;

    if (parent.classList.contains('pin-spacer')) {
        const previous = parent.previousElementSibling;
        if (previous) {
            const height = previous.clientHeight || 0;
            scrollTarget = previous.offsetTop + height;
        }
    }

    lenis.scrollTo(scrollTarget, {
        lerp: 0.1,
        duration: 1.5,
        easing: (t) => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t,
    });
}


function SwitchLang() {
    if (document.body.dataset.langSwitchBound === '1') {
        return;
    }
    document.body.dataset.langSwitchBound = '1';

    document.addEventListener('click', (e) => {
        const link = e.target.closest('[data-lang]');
        if (!link) return;

        link.setAttribute('data-barba-prevent', 'self');

        e.preventDefault();

        gsap.to('body', {
            opacity: 0,
            duration: 0.3,
            onComplete: () => {
                window.location.href = link.href;
            }
        });
    });
}





(() => {
    const GALLERY = ".work-gallery";
    const ITEM = ".work-gallery__item";
    const IMG = "img";

    const inited = new WeakSet();

    const px = v => parseFloat(v) || 0;

    function waitImages(imgs) {
        return Promise.all([...imgs].map(img => {
            if (img.decode) return img.decode().catch(() => { });
            if (img.complete && img.naturalWidth) return Promise.resolve();
            return new Promise(res => img.addEventListener("load", res, { once: true }));
        }));
    }

    function waitForVisible(gallery) {
        return new Promise(res => {
            const check = () => {
                if (gallery.clientWidth > 0) return res();
                requestAnimationFrame(check);
            };
            check();
        });
    }

    function getConfig() {
        const w = window.innerWidth;
        if (w >= 1200) return { minItems: 2, maxItems: 3, preferItems: 2, targetH: 420, minH: 280, maxH: 700, lonelyPenalty: 1e9, preferPenalty: 2600, heightPenalty: 1 };
        if (w >= 768) return { minItems: 2, maxItems: 2, preferItems: 2, targetH: 320, minH: 220, maxH: 600, lonelyPenalty: 1e9, preferPenalty: 0, heightPenalty: 1 };
        return { minItems: 1, maxItems: 1, preferItems: 1, targetH: 260, minH: 160, maxH: 700, lonelyPenalty: 0, preferPenalty: 0, heightPenalty: 0.6 };
    }

    function layoutOne(gallery) {
        const W = gallery.clientWidth;
        if (!W) return;

        const cs = getComputedStyle(gallery);
        const gap = px(cs.gap || cs.columnGap || "0px");
        const cfg = getConfig();

        const nodes = [...gallery.querySelectorAll(ITEM)];
        const items = nodes.map(it => {
            it.style.flex = "0 0 auto";
            it.style.minWidth = "0";
            const img = it.querySelector(IMG);
            const r = img && img.naturalWidth && img.naturalHeight ? img.naturalWidth / img.naturalHeight : 1;
            return { it, r };
        });

        let row = [];
        let sumR = 0;

        items.forEach((item, i) => {
            row.push(item);
            sumR += item.r;

            if (row.length >= cfg.maxItems || i === items.length - 1) {
                const h = (W - gap * (row.length - 1)) / sumR;
                row.forEach((x, j) => x.it.style.flexBasis = (x.r * h) + "px");
                row = [];
                sumR = 0;
            }
        });
    }

    async function initGallery(gallery) {
        if (inited.has(gallery)) return;
        inited.add(gallery);

        const imgs = gallery.querySelectorAll(`${ITEM} ${IMG}`);
        await waitImages(imgs);
        await waitForVisible(gallery);

        layoutOne(gallery);

        new ResizeObserver(() => layoutOne(gallery)).observe(gallery);
    }

    function initGalleries() {
        document.querySelectorAll(GALLERY).forEach(initGallery);
    }

    if (document.readyState !== "loading") initGalleries();
    else document.addEventListener("DOMContentLoaded", initGalleries);

    const mo = new MutationObserver(initGalleries);
    mo.observe(document.body, { childList: true, subtree: true });

    window.addEventListener("resize", initGalleries, { passive: true });

    window.initGalleries = initGalleries;

})();
