@extends('backend.master')

@section('title', 'FAQ Details')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">FAQ</a></li>
                        <li class="breadcrumb-item active">Details</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card box-shadow-0">
                <div class="card-body">
                    <h1 class="text-center">Details Page</h1>
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th colspan="3" class="bg-light">Translations</th>
                            </tr>
                            <tr>
                                <th>Language</th>
                                <th>Question</th>
                                <th>Answer</th>
                            </tr>
                            @forelse ($data->translations as $translation)
                                @php
                                    $languageCode = strtolower($translation->language?->code ?? '');
                                    $isRtl = in_array($languageCode, ['ar', 'fa', 'ur', 'he']);
                                @endphp
                                <tr>
                                    <td>
                                        {{ $translation->language?->name ?? 'N/A' }}
                                        ({{ strtolower($translation->language?->code ?? 'n/a') }})
                                    </td>
                                    <td>
                                        <div dir="{{ $isRtl ? 'rtl' : 'ltr' }}" class="{{ $isRtl ? 'text-end' : 'text-start' }}">
                                            {!! $translation->question ?? 'N/A' !!}
                                        </div>
                                    </td>
                                    <td>
                                        <div dir="{{ $isRtl ? 'rtl' : 'ltr' }}" class="{{ $isRtl ? 'text-end' : 'text-start' }}">
                                            {!! $translation->answer ?? 'N/A' !!}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No translations found.</td>
                                </tr>
                            @endforelse

                            <tr>
                                <th>Status</th>
                                <td colspan="2">{{ ucfirst($data->status ?? 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>Sort Order</th>
                                <td colspan="2">{{ $data->sort_order ?? 0 }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('faq.edit', $data->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('faq.index') }}" class="btn btn-primary">Back</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
