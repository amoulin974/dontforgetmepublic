@extends('layouts.app')

@section('content')
<div class="step active" id="step1">
    <h2 class="mb-4">Êtes-vous un client ou souhaitez-vous créer votre entreprise ?</h2>
    <div class="row">
        <div class="col-6">
            <a class="btn btn-outline-primary w-100 py-4" data-next="step2-client" href="{{ route('register.user.register') }}">
                Je souhaite créer mon compte client
            </a>
            <!-- <a href="{{ route('register.user.register') }}">
                Je souhaite créer mon compte client
            </a> -->
        </div>
        <div class="col-6">
            <a class="btn btn-outline-primary w-100 py-4" data-next="step2-enterprise" href="{{ route('register.company.register.user') }}">
                Je souhaite créer mon compte entreprise
            </a>
           <!--  <a href="{{ route('register.company.register.user') }}">
                Je souhaite créer mon compte entreprise
            </a> -->
        </div>
    </div>
</div>
@endsection
