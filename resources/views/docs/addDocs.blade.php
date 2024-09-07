@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
@endif
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('New Document to project') }}</h1>
            <h2>{{ $project->name }}</h2>
            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-secondary mb-3">{{ __('Back') }}</a>
            <div class="form-group" id="search-docs">
                <input type="text" class="form-control" id="search-docs-input" name="search-docs-input" placeholder="{{ __('Search document') }}">
                <div id="search-docs-results"></div>
            </div>
        </div>
    </div>
    <div class="row" id="project_docs"></div>
    <div class="row">
        <input type="text" id="find_document" placeholder="{{ __('draft document') }}" onkeyup="DraftDocument()">
        <button type="button" class="btn border" onclick="addDraftDocument()">{{ __('Add draft') }}</button>
    </div>

    <script>
        const docs = @json($docs);
        var projectDocs = @json($project->docs);

        const searchDocsInput = document.getElementById('search-docs-input');
        const searchDocsResults = document.getElementById('search-docs-results');

        searchDocsInput.addEventListener('input', (e) => {
            const search = e.target.value;
            searchDocsResults.innerHTML = '';
            DraftDocument(); // Sync the search inputs

            if (search.length > 0) {
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

        function storeDocToProject(docId) {
            const url = '{{ route('docs.store_to_project', ['project' => $project->id, 'doc' => 'docId']) }}'.replace('docId', docId);
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
                let new_doc = docs.find(doc => doc.id == docId);
                projectDocs.push(new_doc);
                showDocs(projectDocs);
            })
            .catch((error) => {
                console.error('Error:', error);
            });
        }

        function showDocs(docs) {
            const projectDocsElement = document.getElementById('project_docs');
            projectDocsElement.innerHTML = '';
            docs.forEach(doc => {
                const docElement = document.createElement('div');
                docElement.classList.add('col-md-3');
                docElement.innerHTML = `
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">${doc.name}</h5>
                            <p class="card-text">${doc.description}</p>
                            <a href="{{ route('docs.show', '') }}/${doc.id}" class="btn btn-primary">{{ __('View') }}</a>
                        </div>
                    </div>
                `;
                projectDocsElement.appendChild(docElement);
            });
        }

        function DraftDocument() {
            const search_docs_input = document.getElementById('search-docs-input').value;
            document.getElementById('find_document').value = search_docs_input;
        }

        function addDraftDocument() {
            const draftDocName = document.getElementById('find_document').value;
            if (draftDocName.trim() === '') {
                alert('Please enter a document name');
                return;
            }
            
            const newDraftDoc = {
                id: Date.now(), // A unique ID for the draft document
                name: draftDocName,
                description: 'Draft document'
            };
            projectDocs.push(newDraftDoc);
            showDocs(projectDocs);
            document.getElementById('find_document').value = ''; // Clear the input field
        }

        showDocs(projectDocs);
    </script>
@endsection
