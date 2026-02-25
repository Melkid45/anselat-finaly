@extends('layouts.app')
@section('content')
<section class="hero hero--main">
    <div class="hero__body">
        <img src="{{ attachment_url($hero, 'image') }}" class="hero__image" alt="" />
        <div class="hero__header">
            <a href="{{page_url('home')}}" class="hero__logo">
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

            <button class="hero__mobile-menu">
                <span></span>
            </button>
        </div>

        <h1 class='hero__title'>
            <p class="main-title" style='flex-direction:row;'>{{ $hero->first_title }}</p>
            <p class="main-title" style='flex-direction:row;'>{{$hero->second_title}}</p>
        </h1>

        <div class="hero__footer el--fade">
            <p class="hero__about">
                @php
                $description = $hero->description;
                echo nl2br($description);
                @endphp
            </p>
            <div class="hero__actions">
                <a
                    href="#calc"
                    class="button button--green button--middle button--fade open-calculate">
                    {{ __('ui.actions.calculate_price') }}
                </a>
                <a
                    class="button button--white button--middle"
                    href="{{ page_url('works') }}">
                    <svg
                        width="16"
                        height="16"
                        viewBox="0 0 16 16"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.530273 14.75L14.5303 0.750001"
                            stroke="white"
                            stroke-width="1.5"
                            stroke-linejoin="round"></path>
                        <path
                            d="M0.530273 0.75L14.5303 0.750001L14.5303 14.75"
                            stroke="white"
                            stroke-width="1.5"></path>
                    </svg>
                    {{ __('ui.actions.view_works') }}
                </a>
            </div>
        </div>
    </div>
</section>
<section class="partners">
    <div class="container">
        <div class="partners__body">
            <h2 class="el--fade">{{ $partners->title }}</h2>
            <div class="partners__stroke-wrap el--fade">
                <div class="partners__stroke" id="marqueeContainer">
                    <div class="partners__body-frame">
                        @foreach(attachment_urls($partners, 'logos') as $url)
                        <img src="{{ $url }}" alt="">
                        @endforeach
                    </div>
                    <div class="partners__body-frame">
                        @foreach(attachment_urls($partners, 'logos') as $url)
                        <img src="{{ $url }}" alt="">
                        @endforeach
                    </div>
                    <div class="partners__body-frame">
                        @foreach(attachment_urls($partners, 'logos') as $url)
                        <img src="{{ $url }}" alt="">
                        @endforeach
                    </div>
                    <div class="partners__body-frame">
                        @foreach(attachment_urls($partners, 'logos') as $url)
                        <img src="{{ $url }}" alt="">
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="block block--carousel">
    <div class="block__body">
        <div class="block__head block__head--type-a el--fade">
            <h2>{{ $blockWorks->title}}</h2>
            <a
                class="button button--green button--middle"
                href="{{ page_url('works') }}">
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
        <div
            class="splide works-container"
            role="group"
            aria-label="Splide Basic HTML Example">
            <div class="splide__track">
                <ul class="splide__list">
                    @foreach($works as $item)
                    <a
                        href="{{ work_url($item) }}"
                        class="object object--carousel splide__slide">
                        <img
                            src="{{ attachment_url($item, 'preview') }}"
                            class="object__image"
                            alt="{{ $item->name }}" />
                        <div class="object__info">
                            <h3>{{ $item->name }}</h3>
                            <p>
                                {{ $item->description }}
                            </p>
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
                </ul>
            </div>
        </div>
    </div>
</section>
<section class="counters">
    <div class="container">
        <div class="counters__body">
            @foreach((array) $counters->items as $item)
            <div class="counters__item el--fade">
                <div>{{ $item['title'] ?? $item['Title'] ?? '' }}</div>
                <p>{{ $item['description'] ?? $item['Description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

@include('partials.request')
@endsection
