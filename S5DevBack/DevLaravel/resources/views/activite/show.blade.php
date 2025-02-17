@extends('layouts.app')

@include('base')

@section('title', __('Activities proposed by ') . $entreprise->libelle)

@section('content')
<div class="container">
    <h2 class="mb-4">{{__('Available services')}}</h2>

    @if($services->isEmpty())
        <p>Aucun service n'a été créé pour {{ $entreprise->libelle }}.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>{{__('Name')}}</th>
                    <th>{{__('Duration')}}</th>
                    <th>{{__('Actions')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($services as $service)
                <tr>
                    <td>{{ $service->libelle }}</td>
                    <td>{{ $service->duree }}</td>
                    <td>
                        <a href="{{ route('reservation.create', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-primary">{{ __('Book') }}</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <a href="{{ route('reserver.index') }}" class="btn btn-secondary">{{__('Back')}}</a>
</div>
@endsection
