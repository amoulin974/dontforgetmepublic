@extends('layouts.app')

@include('base')

@section('title', __('Activity for ') . $entreprise->libelle)

@section('content')
<div class="container">
    @if($services->isEmpty())
        <h1 style="text-align:center;">{{__('Create your first service')}}</h1>
    @endif
    <h2 class="mb-4">{{__('My Services')}}</h2>

    @php
        $isAdmin = $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('statut','Admin')->first() != null;
    @endphp

    @if($services->isEmpty())
        <p>{{__('No service was created for')}} {{ $entreprise->libelle }}.</p>
        <a href="{{ route('entreprise.services.create', ['entreprise' => $entreprise->id]) }}" class="btn btn-dark">{{__('Create service')}}</a>
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
                @php
                    $isWorkedByUser = $entreprise->travailler_users()->wherePivot('idUser',Auth::user()->id)->wherePivot('idActivite',$service->id)->first() != null;
                @endphp
                @if($isWorkedByUser || $isAdmin)
                <tr>
                    <td>{{ $service->libelle }}</td>
                    <td>{{ $service->formatted_duree }}</td>
                    <td>
                        @if($isAdmin)
                        <a href="{{ route('entreprise.services.edit', ['entreprise' => $entreprise->id, 'id' => $service->id]) }}" class="btn btn-link">
                            <i class="fa fa-pencil-alt"></i> {{__('Edit2')}}
                        </a>
                        <form action="{{ route('entreprise.services.destroy', ['entreprise' => $entreprise->id, 'id' => $service->id]) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-link text-danger">
                                <i class="fa fa-trash"></i> {{__('Delete')}}
                            </button>
                        </form>
                        <a href="{{ route('entreprise.services.createPlage', ['entreprise' => $entreprise->id, 'id' => $service->id]) }}" class="btn btn-link">
                            <i class="fa fa-calendar"></i> {{__('Manage slots')}}
                        </a>
                        @if($isWorkedByUser)
                        <a href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-link">
                            <i class="fa fa-eye"></i> {{__('See your slots')}}
                        </a>
                        @endif
                        @else
                        <a href="{{ route('parametrage.plage.idEntrepriseAsEmploye', ['entreprise' => $entreprise->id, 'activite' => $service->id]) }}" class="btn btn-link">
                            <i class="fa fa-calendar"></i> {{__('See slots')}}
                        </a>
                        @endif
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    @endif

    @if(!$services->isEmpty() && $isAdmin)
        <div class="mt-4">
            <a href="{{ route('entreprise.services.create', ['entreprise' => $entreprise->id]) }}" class="btn btn-primary">{{__('Add service')}}</a>
        </div>
    @endif
    <a href="{{ route('entreprise.show', ['entreprise' => $entreprise->id]) }}" class="btn btn-secondary">{{__('Back')}}</a>
</div>
@endsection
