@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    {{__('doc')}}
                </h1>
                <a href="{{ route('docs.index') }}" class="btn btn-secondary mb-3">{{__('Back')}}</a>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h1> {{$doc->name}} </h1>
                                <a href="{{ route('docs.edit',  $doc) }}" class="btn btn-warning">{{__('Edit')}}</a>
                                <form method="POST" action="{{ route('docs.destroy', $doc) }}">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger">{{__('Delete')}}</button>
                                </form>
                            </div>
                            <div class="col-md-6">
                               {{__('path')}} : {{$doc->path}}

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <p>{{__('Created on')}}: {{ date('Y-m-d', strtotime($doc->created_at)) }}</p>
                        <p>{{__('Updated on')}}: {{ date('Y-m-d', strtotime($doc->updated_at)) }}</p>
                        <p>{!! nl2br($doc->description) !!}</p>
                          <p>{{__('Print')}}:
                            <a href="{{  $doc->path}}" target="_blank" class="btn border" >{{__('Download')}}</a>
                        </p>
                        <p> {{__('Revision Date')}}: {{ date('Y-m-d', strtotime($doc->revision_date)) }}</p>
                        <p> {{__('Publication Date')}}: {{ date('Y-m-d', strtotime($doc->publication_date)) }}</p>
                        <p> {{__('Creation Date')}}: {{ date('Y-m-d', strtotime($doc->creation_date)) }}</p>
                        <p> {{__('Deletion Date')}}: {{ date('Y-m-d', strtotime($doc->deletion_date)) }}</p>
                        <p> {{__('Last Change Date')}}: {{ date('Y-m-d', strtotime($doc->last_change_date)) }}</p>
                        <p> {{__('Last View Date')}}: {{ date('Y-m-d', strtotime($doc->last_view_date)) }}</p> 
                        <p> {{__('Status')}}: 
                            @if($doc->status == 0){{__('Draft')}}
                            @elseif($doc->status == 1){{__('Published')}}
                            @elseif($doc->status == 2){{__('Deleted')}}
                            @elseif($doc->status == 3){{__('Archived')}}
                            @endif
                        </p>


                    </div>

            </div>
        </div>
    </div>
@endsection
