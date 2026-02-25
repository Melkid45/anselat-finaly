<div class="calc" style="display: none;">
    <div class="calc__body" data-lenis-prevent="">
        <div class="calc__content">
            <div class="calc__head">
                <h2>Cenas kalkulators</h2>
                <button class="calc__close">
                    <svg
                        width="34"
                        height="34"
                        viewBox="0 0 34 34"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M0.707153 32.7072L32.7072 0.707153"
                            stroke="black"
                            stroke-width="2"></path>
                        <path
                            d="M0.707153 0.707153L32.7072 32.7072"
                            stroke="black"
                            stroke-width="2"></path>
                    </svg>
                </button>
            </div>
            <div class="calc__success">
                <div>
                    <img src="{{asset('images/dist/icon-done.svg')}}" />
                    <h1>{{ __('ui.calc.thanks_title') }}</h1>
                    <p>
                        {{ __('ui.calc.invoice_text') }}
                    </p>
                    <p>
                        {{ __('ui.calc.invoice_note') }}
                    </p>
                    <div class="calc__success-button">
                        <button
                            class="button button--green button--middle calc__close">
                            {{ __('ui.actions.close_window') }}
                        </button>
                    </div>
                </div>
            </div>
            <form
                action="{{ route('calculator.submit', ['locale' => app()->getLocale()]) }}" method="POST" id="calculate-form"
                class="calc__form">
                @csrf
                <div class="calc__form-block">
                    <span>{{ __('ui.calc.type') }}</span>
                    <div
                        class="calc__form-block-content calc__form-block-content--type">
                        @foreach($footerCategories as $index => $category)
                        <div>
                            {{ $category->name }}
                            <input
                                type="radio"
                                value="{{$category->name}}" name="category" {{ $index === 0 ? 'checked' : '' }} />
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="calc__form-block">
                    <span>{{ __('ui.calc.sizes') }}</span>
                    <div
                        class="calc__form-block-content calc__form-block-content--size">
                        <div>
                            <input name="width" class="base width-input" placeholder='Platums' type="number" min="0" step="0.1">
                        </div>
                        <div>
                            <input name="height" class="base height-input" placeholder='Augstums' type="number" min="0" step="0.1">
                        </div>
                        <div>
                            <input name="depth" class="base depth-input" placeholder='Dziļums' type="number" min="0" step="0.1">
                        </div>
                    </div>
                </div>
                <div class="calc__form-block">
                    <span>{{ __('ui.calc.your_data') }}</span>
                    <div
                        class="calc__form-block-content calc__form-block-content--user-data">
                        <div>
                            <input name="full_name" class="base" placeholder='Pilns vārds' type="text">
                        </div>
                        <div>
                            <input name="email" class="base" placeholder='E-pasts' type="email">
                        </div>
                        <div>
                            <input name="address" class="base" placeholder='Adrese' type="text">
                        </div>
                    </div>
                </div>
                <div class="calc__price">
                    <h4>{{ __('ui.calc.approx_price') }}</h4>
                    <span class="price-output">0 €</span>
                </div>
                <button
                    id="calc__button"
                    type="submit"
                    class="button button--green button--middle">
                    {{ __('ui.actions.submit_application') }}
                </button>
                <p class="calc__note">
                    {{ __('ui.calc.note') }}
                </p>
            </form>
        </div>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="footer__up">
            <div class="el--fade">
                <a
                    href="#calc"
                    class="button button--green button--big button--fade open-calculate">
                    {{ __('ui.footer.contact_us') }}
                </a>
            </div>
            <div class="footer__up-container">
                <div class="footer__contacts el--fade">
                    <span>{{ __('ui.footer.address') }}</span>
                    <p>{{ $contact->address}}</p>
                    <a
                        href="{{ $contact->{'address-link'} }}">
                        {{ __('ui.footer.get_directions') }}
                        <svg
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
                    </a>
                </div>
                <div class="footer__contacts el--fade">
                    <span>{{ __('ui.footer.phone') }}</span>
                    @php
                    $phone_link = str_replace(array(' ', '-', '_', '(', ')'), array('','','','',''), $contact->phone);
                    @endphp
                    <a href="tel:{{$phone_link}}">
                        {{$contact->phone}}
                        <svg
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
                    </a>
                </div>
                <div class="footer__contacts el--fade">
                    <span>{{ __('ui.footer.email') }}</span>
                    <a href="mailto:{{$contact->email}}">
                        {{$contact->email}}
                        <svg
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
                    </a>
                </div>
            </div>
        </div>
        <div class="footer__main">
            <a href="{{page_url('home')}}" class="logo el--fade">
                <img src="{{ asset('images/dist/logo-dark.svg') }}" alt="" />
            </a>
            <div class="footer__links">
                <div class="el--fade">
                    <span>Anselat</span>
                    <a href="{{page_url('about')}}">{{ __('ui.nav.about') }}</a>
                    <a href="{{ page_url('works') }}">{{ __('ui.nav.works') }}</a>
                    <a href="{{page_url('material')}}">{{ __('ui.nav.materials') }}</a>
                </div>
                <div class="el--fade">
                    <span>{{ __('ui.footer.sections') }}</span>
                    @foreach($footerCategories as $category)
                    <a href="{{ page_url('works', ['categorySlug' => category_route_slug($category)]) }}"> {{ $category->name }} </a>
                    @endforeach

                </div>
                <div class="el--fade">
                    <span>{{ __('ui.footer.social') }}</span>
                    <a href="{{$contact->facebook}}">Facebook</a>
                    <a href="{{$contact->instagram}}">Instagram</a>
                </div>
            </div>
            <div class="empty"></div>
        </div>
        <div class="footer__down el--fade">
            <p>{{ __('ui.footer.copyright') }}</p>
            <button class='scroll__top'>
                <svg
                    viewBox="0 0 14 14"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M6.8535 13.707L6.8535 0.707049"
                        stroke="#AFB1A7"
                        stroke-linejoin="round"></path>
                    <path
                        d="M0.353516 7.20703L6.8535 0.707049L13.3535 7.20703"
                        stroke="#AFB1A7"></path>
                </svg>
                <span>{{ __('ui.actions.back_to_top') }}</span>
            </button>
        </div>
    </div>
</footer>




<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('calculate-form');
        const buttonSubmit = document.getElementById('calc__button');
        const formSuccess = document.querySelector('.calc__success')
        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            clearErrors();

            const formData = new FormData(form);
            const originalButtonText = buttonSubmit.textContent;

            buttonSubmit.textContent = @json(__('ui.calc.waiting'));
            buttonSubmit.disabled = true;

            try {
                const res = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await res.json();

                if (data.status === 'success') {
                    form.style.display = 'none';
                    formSuccess.style.display = 'flex';
                    form.reset();
                } else {
                    if (data.errors) {
                        displayErrors(data.errors);
                    } else if (data.message) {
                        alert(data.message);
                    } else {
                        alert(@json(__('ui.calc.send_failed')));
                    }
                    buttonSubmit.textContent = originalButtonText;
                    buttonSubmit.disabled = false;
                }
            } catch (err) {
                alert(@json(__('ui.calc.send_failed')));
                buttonSubmit.textContent = originalButtonText;
                buttonSubmit.disabled = false;
            }
        });

        function getLocalizedErrorMessage(field, error) {
            return error;
        }

        function clearErrors() {

            const formElements = form.querySelectorAll('.calc__filed-error, .error-message, .calc__form-block-content input');
            form.querySelectorAll('.calc__filed-error').forEach(el => {
                el.classList.remove('calc__filed-error');
            });

            form.querySelectorAll('.calc__form-block-content input').forEach(el => {
                el.classList.remove('error');
            });

            form.querySelectorAll('.error-message, .calc__filed-error p').forEach(el => {
                el.remove();
            });
        }

        function displayErrors(errors) {

            for (let field in errors) {
                const input = form.querySelector(`[name="${field}"]`);

                if (input) {
                    const errorMessage = getLocalizedErrorMessage(field, errors[field][0]);

                    input.classList.add('error');

                    let parentDiv = input.closest('div');

                    if (parentDiv) {
                        parentDiv.classList.add('calc__filed-error');
                        const oldError = parentDiv.querySelector('p');
                        if (oldError) {
                            oldError.remove();
                        }
                        const errorText = document.createElement('p');
                        errorText.textContent = errorMessage;

                        parentDiv.appendChild(errorText);

                    }
                } else {
                    console.log(`Field ${field} not found in form`);
                }
            }
        }
        form.querySelectorAll('.calc__form-block-content input').forEach(input => {
            input.addEventListener('input', function() {

                this.classList.remove('error');

                const parentDiv = this.closest('div');
                if (parentDiv) {
                    parentDiv.classList.remove('calc__filed-error');

                    const errorText = parentDiv.querySelector('p');
                    if (errorText) {
                        errorText.remove();
                    }
                }
            });
        });

        form.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const radioGroup = form.querySelectorAll(`[name="${this.name}"]`);
                radioGroup.forEach(r => {
                    r.classList.remove('error');

                    const parentDiv = r.closest('div');
                    if (parentDiv) {
                        parentDiv.classList.remove('calc__filed-error');

                        const errorText = parentDiv.querySelector('p');
                        if (errorText) {
                            errorText.remove();
                        }
                    }
                });
            });
        });
    });
</script>
