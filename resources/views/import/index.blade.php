@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>{{__('Import')}}  </h1>
               <ul>
                    <li><a href="{{ route('structure.import') }}">{{__('Structure import')}}</a></li>
                    <li><a href="{{ route('personal.import') }}"> {{__('Personal import')}}</a></li>
                    <li><a href="{{ route('types.import') }}"> {{__('Types import')}}</a></li>
                    <li><a href="{{ route('projects.import') }}"> {{__('Projects import')}}</a></li>
                    <li><a href="{{ route('dimensions.import') }}"> {{__('Dimensions import')}}</a></li>
                    <li><a href="{{ route('nomenclaturs.import') }}"> {{__('Nomenclatures import')}}</a></li>

               </ul>
               
        </div>
    </div>
@endsection