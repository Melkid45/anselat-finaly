<section id="request" class="request">
    <div class="container">
        <div class="request__body">
            <div class="request__image el--opacity">
                <img src="{{attachment_url($request, 'images') }}" alt="" />
            </div>
            <div class="request__content">
                <div class="request__head el--fade">
                    <h2>{{ $request->title }}</h2>
                    <p>
                        {{ $request->description }}
                    </p>
                </div>
                <form
                    action="{{ route('request.submit', ['locale' => app()->getLocale()]) }}" method="POST"
                    class="request__form"
                    id="telegramForm">
                    @csrf
                    <div class="request__form-block">
                        <label for="address">{{ __('ui.request.address') }}</label>
                        <input
                            id="address"
                            name="address"
                            placeholder="Viestura prospekts 2, Ziemeļu rajons, Rīga, LV-1005"
                            type="text"
                            required=""
                            maxlength="255" />
                        <div class="error-message" id="address-error"></div>
                    </div>
                    <div class="request__form-block">
                        <label for="phone">{{ __('ui.request.phone') }}</label>
                        <input
                            id="phone"
                            name="phone"
                            placeholder="+371 29 123 456"
                            type="tel"
                            required="" />
                        <div class="error-message" id="phone-error"></div>
                    </div>
                    <div class="request__form-whatsapp el--fade">
                        <input
                            type="checkbox"
                            id="whatsapp"
                            name="whatsapp"
                            value="1" />
                        <span></span>
                        <p>{{ __('ui.request.whatsapp_hint') }}</p>
                    </div>
                    <button
                        type="submit"
                        class="button button--green button--middle el--fade button--fade">
                        {{ __('ui.actions.submit_request') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
