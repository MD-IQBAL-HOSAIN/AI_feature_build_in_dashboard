@extends('backend.master')

@section('title', 'V2 Preview | System User')

@push('styles')
    <style>
        .v2-coming-soon {
            position: relative;
            min-height: calc(100vh - 180px);
            overflow: hidden;
            border-radius: 32px;
            background:
                radial-gradient(circle at 16% 88%, rgba(0, 255, 179, 0.26), transparent 20rem),
                radial-gradient(circle at 80% 16%, rgba(255, 107, 214, 0.45), transparent 24rem),
                radial-gradient(circle at 92% 12%, rgba(182, 92, 255, 0.35), transparent 30rem),
                radial-gradient(circle at 48% 18%, rgba(122, 45, 255, 0.14), transparent 20rem),
                linear-gradient(135deg, #031221 0%, #140320 42%, #220333 72%, #120118 100%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 24px 70px rgba(3, 7, 18, 0.45);
            isolation: isolate;
        }

        .v2-coming-soon::before {
            content: "";
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(2px 2px at 12% 18%, rgba(255, 255, 255, 0.95), transparent 60%),
                radial-gradient(1.5px 1.5px at 24% 64%, rgba(255, 255, 255, 0.85), transparent 60%),
                radial-gradient(2px 2px at 35% 30%, rgba(155, 214, 255, 0.9), transparent 60%),
                radial-gradient(1.5px 1.5px at 48% 78%, rgba(255, 255, 255, 0.75), transparent 60%),
                radial-gradient(2px 2px at 58% 14%, rgba(255, 255, 255, 0.9), transparent 60%),
                radial-gradient(1.5px 1.5px at 68% 52%, rgba(196, 181, 253, 0.8), transparent 60%),
                radial-gradient(2px 2px at 78% 34%, rgba(255, 255, 255, 0.92), transparent 60%),
                radial-gradient(1.5px 1.5px at 88% 68%, rgba(255, 255, 255, 0.8), transparent 60%),
                radial-gradient(3px 3px at 20% 46%, rgba(100, 190, 255, 0.95), transparent 65%),
                radial-gradient(3px 3px at 73% 22%, rgba(255, 160, 236, 0.92), transparent 65%);
            opacity: 0.88;
            pointer-events: none;
        }

        .v2-coming-soon::after {
            content: "";
            position: absolute;
            top: -6%;
            right: -10%;
            width: 34rem;
            height: 34rem;
            border-radius: 50%;
            background:
                radial-gradient(circle at 35% 35%, rgba(255, 179, 245, 0.95), rgba(198, 79, 255, 0.88) 42%, rgba(117, 36, 183, 0.62) 65%, rgba(77, 12, 102, 0.06) 78%, transparent 79%);
            box-shadow:
                inset -35px -35px 60px rgba(64, 6, 88, 0.35),
                0 0 100px rgba(231, 86, 255, 0.25);
            pointer-events: none;
            opacity: 0.95;
        }

        .v2-coming-soon__inner {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: calc(100vh - 180px);
            padding: 3rem 1.25rem;
        }

        .v2-coming-soon__content {
            width: 100%;
            max-width: 54rem;
            text-align: center;
            color: #fff;
        }

        .v2-coming-soon__eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            padding: 0.55rem 0.95rem;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.06);
            color: rgba(255, 255, 255, 0.88);
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            backdrop-filter: blur(10px);
        }

        .v2-coming-soon__eyebrow i {
            color: #8dd7ff;
        }

        .v2-coming-soon__lead {
            margin: 2rem 0 0.9rem;
            font-size: clamp(1.35rem, 2vw, 2rem);
            font-weight: 500;
            color: rgba(255, 255, 255, 0.92);
        }

        .v2-coming-soon__title {
            margin: 0;
            font-size: clamp(3rem, 8vw, 5.9rem);
            line-height: 0.96;
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #fffdf6;
            text-shadow: 0 10px 40px rgba(0, 0, 0, 0.35);
        }

        .v2-coming-soon__text {
            max-width: 42rem;
            margin: 1.8rem auto 0;
            font-size: 1.1rem;
            line-height: 1.9;
            color: rgba(255, 255, 255, 0.76);
        }

        .v2-coming-soon__stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 2.3rem;
        }

        .v2-coming-soon__stat {
            padding: 1rem 1.1rem;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(9, 10, 28, 0.36);
            backdrop-filter: blur(12px);
            text-align: left;
        }

        .v2-coming-soon__stat span {
            display: block;
            margin-bottom: 0.35rem;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: rgba(255, 255, 255, 0.58);
        }

        .v2-coming-soon__stat strong {
            display: block;
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
        }

        .v2-coming-soon__phases {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .v2-coming-soon__phase {
            padding: 1.15rem;
            border-radius: 20px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.09), rgba(255, 255, 255, 0.03));
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(12px);
            text-align: left;
        }

        .v2-coming-soon__phase h4 {
            margin-bottom: 0.55rem;
            font-size: 1rem;
            font-weight: 800;
            color: #fff;
        }

        .v2-coming-soon__phase p {
            margin-bottom: 0;
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.7;
            font-size: 0.95rem;
        }

        .v2-coming-soon__footnote {
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            margin-top: 2rem;
            padding: 0.75rem 1rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.82);
            font-size: 0.92rem;
        }

        .v2-coming-soon__footnote code {
            padding: 0.15rem 0.45rem;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.12);
            color: #fff;
        }

        @media (max-width: 991.98px) {
            .v2-coming-soon {
                min-height: auto;
                border-radius: 24px;
            }

            .v2-coming-soon::after {
                width: 22rem;
                height: 22rem;
                top: -3rem;
                right: -7rem;
            }

            .v2-coming-soon__inner {
                min-height: auto;
                padding: 2.2rem 1rem;
            }

            .v2-coming-soon__stats,
            .v2-coming-soon__phases {
                grid-template-columns: 1fr;
            }

            .v2-coming-soon__text {
                font-size: 1rem;
            }
        }
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-12">
            <section class="v2-coming-soon">
                <div class="v2-coming-soon__inner">
                    <div class="v2-coming-soon__content">
                        <span class="v2-coming-soon__eyebrow">
                            <i class="ri-rocket-2-line"></i>
                            Backend {{ $requestedVersion ?? 'V2' }} Preview
                        </span>

                        <p class="v2-coming-soon__lead">Our backend refresh is</p>

                        <h1 class="v2-coming-soon__title">Coming Soon</h1>

                        <p class="v2-coming-soon__text">
                            A new admin experience is under construction. The route separation is already ready, and we can
                            now build {{ $requestedVersion ?? 'V2' }} modules independently without touching the stable V1 panel.
                        </p>

                        <div class="v2-coming-soon__stats">
                            @foreach ($previewStats as $stat)
                                <div class="v2-coming-soon__stat">
                                    <span>{{ $stat['label'] }}</span>
                                    <strong>{{ $stat['value'] }}</strong>
                                </div>
                            @endforeach
                        </div>

                        <div class="v2-coming-soon__phases">
                            @foreach ($launchPhases as $phase)
                                <article class="v2-coming-soon__phase">
                                    <h4>{{ $phase['title'] }}</h4>
                                    <p>{{ $phase['description'] }}</p>
                                </article>
                            @endforeach
                        </div>

                        <div class="v2-coming-soon__footnote">
                            <i class="ri-links-line"></i>
                            Preview route:
                            <code>/admin/{{ strtolower($requestedVersion ?? 'v2') }}{{ $requestedPath ?? '/system-user' }}</code>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
