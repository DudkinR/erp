@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>
                    {{__('New Document to project')}}
                </h1>
                <h2>
                    {{ $project->name }}
                </h2>
                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary mb-3">{{__('Back')}}</a>
                <div class="form-group" id="search-docs">
                <input type="text" class="form-control" id="search-docs-input" name="search-docs-input" placeholder="{{__('Search document')}}">
                <div id="search-docs-results"></div>
             </div>
        </div>
    </div>
    <script>
        const docs = @json($docs);
        //search-docs-input
        const searchDocsInput = document.getElementById('search-docs-input');
        const searchDocsResults = document.getElementById('search-docs-results');
        searchDocsInput.addEventListener('input', (e) => {
            const search = e.target.value;
            searchDocsResults.innerHTML = '';
            if(search.length > 0){
                const results = docs.filter(doc => doc.name.toLowerCase().includes(search.toLowerCase()));
                results.forEach(doc => {
                    const docElement = document.createElement('div');
                    docElement.classList.add('search-docs-result');
                    docElement.innerHTML = doc.name;
                    docElement.addEventListener('click', () => {
                        storeDocToProject(doc.id);
                        docElement.remove();
                    });
                    searchDocsResults.appendChild(docElement);
                    

                });
            }
        }); 

        // post ajax store_to_project
        function storeDocToProject(docId){
            const url = '{{ route('docs.store_to_project', ['project' => $project->id, 'doc' => 'docId']) }}';
            const data = {
                _token: '{{ csrf_token() }}',
                project_id: '{{ $project->id }}',
                doc_id: docId
            };
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                console.log('Success:', data);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }

    </script>
@endsection


                