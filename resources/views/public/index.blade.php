@extends('public.layout.base')

@section('title', 'Tizme.kg | Тойго коноктордун тизмесин оңой башкарыңыз')
@section('description', 'Той, маараке жана башка иш-чараларга келүүчү коноктордун тизмесин түзүү, аларды папкаларга бөлүштүрүү жана башкаруу үчүн заманбап платформа.')

@section('content')
    <div class="space-y-16 md:space-y-24">

        {{-- 1. Hero Section --}}
        <div class="hero min-h-[60vh] bg-base-200">
            <div class="hero-content text-center">
                <div class="max-w-2xl">
                    <h1 class="text-4xl md:text-6xl font-bold text-primary">
                        Коноктордун тизмесин оңой башкарыңыз
                    </h1>
                    <p class="py-6 text-lg md:text-xl">
                        Той, маараке, юбилей жана башка маанилүү иш-чараларыңыз үчүн коноктордун тизмесин түзүүнүн,
                        түзөтүүнүн жана көзөмөлдөөнүн эң ыңгайлуу жолу. Кагаз менен убара болбой, баарын бир жерден башкарыңыз!
                    </p>
                    <div class="flex justify-center gap-4">
                        @guest
                            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Акысыз Катталуу</a>
                            <a href="{{ route('login') }}" class="btn btn-ghost btn-lg">Системага Кирүү</a>
                        @endguest
                        @auth
                            <a href="{{ route('client.index') }}" class="btn btn-primary btn-lg">
                                Менин тизмелерим
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 2. Features Section --}}
            <section class="text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-2">Негизги мүмкүнчүлүктөр</h2>
                <p class="text-lg text-base-content/70 mb-12 max-w-2xl mx-auto">
                    Сиздин ыңгайлуулугуңуз үчүн түзүлгөн заманбап функциялар.
                </p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- Feature 1 --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body items-center text-center">
                            <div class="p-4 bg-primary/10 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" /></svg>
                            </div>
                            <h3 class="card-title">Папкаларга бөлүү</h3>
                            <p>Конокторду "куда тарап", "күйөө тарап", "достор", "кесиптештер" сыяктуу папкаларга бөлүп, тартипке салыңыз.</p>
                        </div>
                    </div>
                    {{-- Feature 2 --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body items-center text-center">
                            <div class="p-4 bg-primary/10 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            </div>
                            <h3 class="card-title">Ыкчам издөө</h3>
                            <p>Жүздөгөн коноктордун арасынан керектүү адамды аты же телефон номери боюнча заматта табыңыз.</p>
                        </div>
                    </div>
                    {{-- Feature 3 --}}
                    <div class="card bg-base-100 shadow-xl">
                        <div class="card-body items-center text-center">
                            <div class="p-4 bg-primary/10 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <h3 class="card-title">Бардык түзмөктөрдө</h3>
                            <p>Тизмелериңизди компьютерден, планшеттен же телефондон кирип, каалаган жерде башкара аласыз.</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 3. How It Works Section --}}
            <section class="mt-24">
                <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Кантип иштейт?</h2>
                <ul class="steps steps-vertical lg:steps-horizontal w-full">
                    <li class="step step-primary">
                        <div class="text-left p-4">
                            <h3 class="font-bold text-lg">Катталыңыз</h3>
                            <p>Бир мүнөткө жетпеген убакытта акысыз аккаунт ачыңыз.</p>
                        </div>
                    </li>
                    <li class="step step-primary">
                        <div class="text-left p-4">
                            <h3 class="font-bold text-lg">Папкаларды түзүңүз</h3>
                            <p>Конокторуңузду иреттөө үчүн ыңгайлуу папкаларды түзүп алыңыз.</p>
                        </div>
                    </li>
                    <li class="step">
                        <div class="text-left p-4">
                            <h3 class="font-bold text-lg">Конокторду кошуңуз</h3>
                            <p>Аты-жөнүн, телефон номерин жана кошумча маалыматтарды киргизиңиз.</p>
                        </div>
                    </li>
                    <li class="step">
                        <div class="text-left p-4">
                            <h3 class="font-bold text-lg">Башкарыңыз!</h3>
                            <p>Тизмеңизди каалаган убакта, каалаган жерден башкарып, көзөмөлдөңүз.</p>
                        </div>
                    </li>
                </ul>
            </section>

        </div>

        {{-- 4. Final CTA Section --}}
        <section class="bg-primary text-primary-content">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 text-center">
                <h2 class="text-3xl md:text-4xl font-bold mb-4">Кагаз менен دفترден баш тартууга даярсызбы?</h2>
                <p class="text-lg mb-8 max-w-2xl mx-auto">
                    Заманбап жана ыңгайлуу ыкмага өтүп, маанилүү күнүңүздү санариптик тактык менен пландаштырыңыз.
                </p>
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-lg">Азыр Баштоо</a>
                @endguest
                @auth
                    <a href="{{ route('client.index') }}" class="btn btn-secondary btn-lg">
                        Менин тизмелерим
                    </a>
                @endauth
            </div>
        </section>

    </div>

@endsection
