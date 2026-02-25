@extends('layouts.app')

@section('content')
<section class="hero hero--page">
    <div class="hero__body">
        <img src="{{ attachment_url($work, 'preview') }}" class="hero__image" alt="{{ $work->name }}" />
        <div class="hero__header">
            <a href="{{ page_url('home') }}" class="hero__logo">
                <img class="light" src="{{ asset('images/dist/logo-light.svg') }}" alt="" />
            </a>

            <div class="hero__menu">
                <li><a href="{{ page_url('home') }}">{{ __('ui.nav.home') }}</a></li>
                <li><a href="{{ page_url('about') }}">{{ __('ui.nav.about') }}</a></li>
                <li><a href="{{ page_url('works') }}">{{ __('ui.nav.works') }}</a></li>
                <li><a href="{{ page_url('material') }}">{{ __('ui.nav.materials') }}</a></li>
            </div>

            <div class="hero__actions">
                @php
                $currentLocale = app()->getLocale();
                $locales = [
                'lv' => 'LV',
                'en' => 'EN',
                'ru' => 'RU'
                ];
                @endphp
                <div class="language">
                    <a
                        href="{{ localized_current_url($currentLocale) }}"
                        class="item"
                        data-lang
                        data-barba-prevent="self">
                        {{ $locales[$currentLocale] ?? strtoupper($currentLocale) }}
                        <svg
                            viewBox="0 0 9 6"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M0.353516 0.353554L4.35352 4.35355L8.35352 0.353554"
                                stroke="#363B23"
                                stroke-opacity="0.4"></path>
                        </svg>
                    </a>
                    <div class="language__list">
                        @foreach($locales as $code => $label)
                        @if($code !== $currentLocale)
                                <a
                                    href="{{ localized_current_url($code) }}"
                                    class="item"
                                    data-lang
                                    data-barba-prevent="self">
                                    {{ $label }}
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>

                <a
                    href="#calc"
                    class="button button--green button--middle button--fade open-calculate">
                    {{ __('ui.actions.calculate_price') }}
                </a>
            </div>

            <a href="" class="hero__mobile-menu">
                <span></span>
            </a>
        </div>

        <div class="hero__footer">
            <div class="hero__breadcrumbs el--left">
                <a href="{{ page_url('home') }}">{{ __('ui.common.home_dash') }}</a>
                <a href="{{ page_url('works', ['categorySlug' => category_route_slug($work->workCategory)]) }}">{{ $work->workCategory?->name ?? 'Works' }} -</a>
                <span>{{ $work->name }}</span>
            </div>
            <h1 class="hero__title main-title" style="flex-direction: row;">{{ $work->name }}</h1>
            <div class="hero__data-list el--fade">
                @if(!empty($work->client))
                    <div>
                        <span>{{ __('ui.work.client') }}</span>
                        <p>{{ $work->client }}</p>
                    </div>
                @endif
                @if(!empty($work->date))
                    <div>
                        <span>{{ __('ui.work.date') }}</span>
                        <p>{{ $work->date }}</p>
                    </div>
                @endif
                @if(!empty($work->place))
                    <div>
                        <span>{{ __('ui.work.place') }}</span>
                        <p>{{ $work->place }}</p>
                    </div>
                @endif
                @if($work->workCategory)
                    <div>
                        <span>{{ __('ui.work.type') }}</span>
                        <p>{{ $work->workCategory->name }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

<section class="block">
    <div class="container">
        <div class="block-body">
            <div class="block__head block__head--type-c el--fade">
                @if(!empty($work->about_title))
                    <h2>{{ $work->about_title }}</h2>
                @endif
                @if(!empty($work->description))
                    <p>{{ $work->description }}</p>
                @endif
            </div>

            <div class="work">
                @php
                $galleryUrls = attachment_urls($work, 'gallery');
                $topGallery = array_slice($galleryUrls, 0, 2);
                $bottomGallery = array_slice($galleryUrls, 2);
                @endphp

                @if(count($topGallery) > 0)
                    <div class="work-gallery" id="workGalleryTop">
                        @foreach($topGallery as $url)
                            <div class="work-gallery__item">
                                <img src="{{ $url }}" alt="{{ $work->name }}" />
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(is_array($work->info) && count($work->info) > 0)
                    <div class="work__blocks">
                        @foreach($work->info as $item)
                            <div>
                                <span>{{ $item['title'] ?? $item['Title'] ?? '' }}</span>
                                <p>{{ $item['description'] ?? $item['Description'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(count($bottomGallery) > 0)
                    <div class="work-gallery" id="workGalleryBottom">
                        @foreach($bottomGallery as $url)
                            <div class="work-gallery__item">
                                <img src="{{ $url }}" alt="{{ $work->name }}" />
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="share">
                    <p class="share__text">{{ __('ui.common.share_with_friends') }}</p>
                    <div class="share__buttons">
                        <a class="share__item" href="#" target="_blank" rel="noopener" aria-label="Share on Facebook">
                            <img src="{{ asset('images/dist/icon-facebook.svg') }}" />
                        </a>
                        <a class="share__item" href="#" target="_blank" rel="noopener" aria-label="Share on WhatsApp">
                            <img src="{{ asset('images/dist/icon-whatsapp.svg') }}" />
                        </a>
                        <button class="share__item share__item--copy" type="button" aria-label="Copy link">
                            <img src="{{ asset('images/dist/icon-link.svg') }}" />
                        </button>
                    </div>
                    <span class="share__hint" role="status" aria-live="polite"></span>
                </div>
            </div>
        </div>
    </div>
</section>

@if($totalWorks > 3 && $suggestedWorks->count() === 2)
<section class="block">
    <div class="container">
        <div class="block__body">
            <div class="block__head block__head--type-b el--fade">
                <h2>{{ __('ui.work.explore_other_projects') }}</h2>
                <a class="button button--green button--middle" href="{{ page_url('works') }}">
                    <svg
                        width="15"
                        height="15"
                        viewBox="0 0 15 15"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.530273 13.75L13.5303 0.750001"
                            stroke="#363B23"
                            stroke-width="1.5"
                            stroke-linejoin="round"></path>
                        <path
                            d="M0.530273 0.75L13.5303 0.750001L13.5303 13.75"
                            stroke="#363B23"
                            stroke-width="1.5"></path>
                    </svg>
                    {{ __('ui.actions.view_works') }}
                </a>
            </div>
            <div class="objects">
                @foreach($suggestedWorks as $item)
                    <a
                        href="{{ work_url($item) }}"
                        class="object object--page splide__slide">
                        <img src="{{ attachment_url($item, 'preview') }}" class="object__image" alt="{{ $item->name }}" />
                        <div class="object__info">
                            <h3>{{ $item->name }}</h3>
                            <p>{{ $item->description }}</p>
                        </div>
                        <div class="circle" style="position: absolute; left: 0px; top: 0px">
                            <svg
                                width="18"
                                height="18"
                                viewBox="0 0 18 18"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M0.70703 17L16.707 1"
                                    stroke="#363B23"
                                    stroke-width="2"
                                    stroke-linejoin="round"></path>
                                <path
                                    d="M0.707031 1L16.707 1L16.707 17"
                                    stroke="#363B23"
                                    stroke-width="2"></path>
                            </svg>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif

<script>
    (function() {
        const btn = document.querySelector('.share__item--copy');
        if (!btn) return;

        const hint = document.querySelector('.share__hint');

        const showHint = (text) => {
            if (!hint) return;
            hint.textContent = text;
            hint.classList.add('is-visible');
            window.clearTimeout(showHint._t);
            showHint._t = window.setTimeout(() => hint.classList.remove('is-visible'), 1200);
        };

        btn.addEventListener('click', async () => {
            const url = window.location.href;

            try {
                await navigator.clipboard.writeText(url);
                showHint('Link copied');
            } catch (e) {
                const temp = document.createElement('input');
                temp.value = url;
                document.body.appendChild(temp);
                temp.select();
                document.execCommand('copy');
                document.body.removeChild(temp);
                showHint('Link copied');
            }
        });
    })();
</script>
@endsection
