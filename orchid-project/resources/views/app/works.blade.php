@extends('layouts.app')

@section('content')
<section class="hero hero--page">
    <div class="hero__body">
        <img src="{{ attachment_url($worksPage, 'images') }}" class="hero__image" alt="" />
        <div class="hero__header">
            <a href="{{ page_url('home') }}" class="hero__logo">
                <img class="light" src="{{ asset('images/dist/logo-light.svg') }}" alt="" />
            </a>

            <div class="hero__menu">
                <li>
                    <a href="{{ page_url('home') }}">{{ __('ui.nav.home') }}</a>
                </li>
                <li>
                    <a href="{{ page_url('about') }}">{{ __('ui.nav.about') }}</a>
                </li>
                <li>
                    <a href="{{ page_url('works') }}">{{ __('ui.nav.works') }}</a>
                </li>
                <li>
                    <a href="{{ page_url('material') }}">{{ __('ui.nav.materials') }}</a>
                </li>
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
                <span>{{ $worksPage->title }}</span>
            </div>
            <h1 class="hero__title main-title" style="flex-direction: row;">{{ $worksPage->title }}</h1>
            <div class="hero__tabs el--fade">
                <a
                    href="{{ page_url('works') }}"
                    class="category-btn {{ $selectedCategory ? '' : 'is-active' }}">
                    {{ __('ui.works.all') }}
                </a>
                @foreach($categories as $category)
                <a
                    href="{{ page_url('works', ['categorySlug' => category_route_slug($category)]) }}"
                    class="category-btn {{ $selectedCategory?->id === $category->id ? 'is-active' : '' }}">
                    {{ $category->name }}
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>

<section class="block">
    <div class="container">
        @if($selectedCategory)
            <div class="block__body">
                <div class="block__head block__head--type-c el--fade show">
                    <h2>{{ $selectedCategory->name }}</h2>
                    @if($selectedCategory->description)
                        <p>{{ $selectedCategory->description }}</p>
                    @endif
                </div>
                <div class="objects">
                    @forelse($selectedCategory->works as $item)
                        <a
                            href="{{ work_url($item) }}"
                            class="object object--page splide__slide">
                            <img src="{{ attachment_url($item, 'preview') }}" class="object__image" alt="{{ $item->name }}" />
                            <div class="object__info">
                                <h3>{{ $item->name }}</h3>
                                <p>{{ $item->description }}</p>
                            </div>
                            <div
                                class="circle"
                                style="position: absolute; left: 0px; top: 0px">
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
                    @empty
                        <p>{{ __('ui.works.empty_category') }}</p>
                    @endforelse
                </div>
            </div>
        @else
            @foreach($categories as $category)
                <div class="block__body">
                    <div class="block__head block__head--type-c el--fade show">
                        <h2>{{ $category->name }}</h2>
                        @if($category->description)
                            <p>{{ $category->description }}</p>
                        @endif
                    </div>
                    <div class="objects">
                        @foreach($category->works as $item)
                            <a
                                href="{{ work_url($item) }}"
                                class="object object--page splide__slide">
                                <img src="{{ attachment_url($item, 'preview') }}" class="object__image" alt="{{ $item->name }}" />
                                <div class="object__info">
                                    <h3>{{ $item->name }}</h3>
                                    <p>{{ $item->description }}</p>
                                </div>
                                <div
                                    class="circle"
                                    style="position: absolute; left: 0px; top: 0px">
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
            @endforeach
        @endif
    </div>
</section>
@endsection
