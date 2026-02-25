@extends('layouts.app')
@section('content')
<section class="hero hero--page">
    <div class="hero__body">
        <img src="{{attachment_url($about, 'images')}}" class="hero__image" alt="" />
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
                <span>{{ $about->title }}</span>
            </div>
            <h1 class="hero__title main-title" style="flex-direction: row;">{{ $about->title }}</h1>
        </div>
    </div>
</section>
<section class="block">
    <div class="container">
        <div class="block__body">
            <div class="block__head block__head--type-c el--fade">
                <h2>{{$company->title}}</h2>
                <p>
                    {{ $about->description }}
                </p>
            </div>
            <div class="about">
                <img
                    class="about__image el--opacity"
                    src="{{ attachment_url($company, 'images') }}"
                    alt="" />
                <div class="about__content">
                    @foreach((array) $company->items as $item)
                    <div class="el--opacity">
                        <h3 class="work__title">{{ $item['title'] ?? $item['Title'] ?? '' }}</h3>
                        <p>
                            {{ $item['description'] ?? $item['Description'] ?? '' }}
                        </p>
                    </div>
                    @endforeach
                </div>
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
