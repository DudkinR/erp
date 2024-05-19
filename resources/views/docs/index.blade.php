@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Docs') }}</h1>
            <input type="text" id="search" class="form-control" placeholder="{{ __('Search') }}">
            <a href="{{ route('docs.create') }}" class="btn btn-primary mb-3 float-right">{{ __('Create Doc') }}</a>
        </div>
    </div>
    <div class="container" id="categories_show"></div>
    <div class="container" id="categories_show_without_category"></div>

</div>
<script>
    @php 
        $categories = \App\Models\Category::with('docs', 'children.docs')->get();
    @endphp 
    const categories = @json($categories);
    let var_categories = categories;
    const search = document.getElementById('search');
    const categories_show = document.getElementById('categories_show');

    search.addEventListener('keyup', function() {
        const searchValue = search.value.trim().toLowerCase();
        let searchResult = categories.reduce(function(acc, category) {
            // Check if the category itself matches the search criteria
            let matchedCategory = category.name.toLowerCase().includes(searchValue) ||
                                  (category.description && category.description.toLowerCase().includes(searchValue));
            
            // Filter the documents within the category
            let matchedDocs = category.docs.filter(doc => doc.name.toLowerCase().includes(searchValue) ||
                                                         (doc.description && doc.description.toLowerCase().includes(searchValue)));
            
            // Filter the children categories and their documents
            let matchedChildren = category.children.reduce(function(childAcc, child) {
                let matchedChild = child.name.toLowerCase().includes(searchValue) ||
                                   (child.description && child.description.toLowerCase().includes(searchValue));
                
                let matchedChildDocs = child.docs.filter(doc => doc.name.toLowerCase().includes(searchValue) ||
                                                               (doc.description && doc.description.toLowerCase().includes(searchValue)));
                
                if (matchedChild || matchedChildDocs.length > 0) {
                    childAcc.push({
                        ...child,
                        docs: matchedChildDocs
                    });
                }
                return childAcc;
            }, []);

            if (matchedCategory || matchedDocs.length > 0 || matchedChildren.length > 0) {
                acc.push({
                    ...category,
                    docs: matchedDocs,
                    children: matchedChildren
                });
            }
            return acc;
        }, []);
        
        showDocs(searchResult);
    });

    function showDocs(docs) {
        categories_show.innerHTML = '';
        if (docs.length === 0) {
            categories_show.innerHTML = '<p> {{ __('No categories or documents found.') }} </p>';
            return;
        }
        docs.forEach(function(category) {
            const categoryElement = document.createElement('div');
            categoryElement.innerHTML = `
                <div class="row mb-3 border">
                    <div class="col-12 border">
                        <h2>${category.name}</h2>
                        ${category.description ? `<p>${category.description}</p>` : ''}
                        <h6> {{ __('Add docs to this category') }} </h6>
                        <a href="/docs/create?category_id=${category.id}" class="btn border btn-primary"> {{ __('Create Doc') }} </a>
                    </div>
                    ${category.docs.map(function(doc) {
                        return `
                            <div class="col-4">
                                <div class="card mb-2">
                                    <div class="card-header">${doc.name}</div>
                                    <div class="card-body">
                                        ${doc.description ? `<p>${doc.description}</p>` : ''}
                                        <a href="/docs/${doc.id}" class="btn btn-primary">
                                            {{ __('View') }}
                                        </a>
                                            
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('')}
                    ${category.children.map(function(child) {
                        return `
                            <div class="col-12 ">
                                <h3>${child.name}</h3>
                                ${child.description ? `<p>${child.description}</p>` : ''}
                                <h6> {{ __('Add docs to this category') }} </h6>
                                <a href="/docs/create?category_id=${child.id}" class="btn border btn-primary"> {{ __('Create Doc') }} </a>
                            </div>
                            ${child.docs.map(function(doc) {
                                return `
                                    <div class="col-4">
                                        <div class="card mb-2">
                                            <div class="card-header">${doc.name}</div>
                                            <div class="card-body">
                                                ${doc.description ? `<p>${doc.description}</p>` : ''}
                                                <a href="/docs/${doc.id}" class="btn btn-primary"> {{ __('View') }} </a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                            }).join('')}
                        `;
                    }).join('')}
                </div>
            `;
            categories_show.appendChild(categoryElement);
        });
    }
    showDocs(var_categories);
    const categories_show_without_category = document.getElementById('categories_show_without_category');
    categories_show_without_category.innerHTML = `
        <div class="row mb-3 border">
            <div class="col-12 border">
                <h2> {{ __('Uncategorized') }} </h2>
                <h6> {{ __('Add docs to this category') }} </h6>
                <a href="/docs/create" class="btn border btn-primary"> {{ __('Create Doc') }} </a>
            </div>
            ${var_categories.filter(category => category.docs.length > 0).map(function(category) {
                return `
                    <div class="col-12">
                        <h3>${category.name}</h3>
                        ${category.description ? `<p>${category.description}</p>` : ''}
                        <h6> {{ __('Add docs to this category') }} </h6>
                        <a href="/docs/create?category_id=${category.id}" class="btn border"> {{ __('Create Doc') }} </a>
                    </div>
                    ${category.docs.map(function(doc) {
                        return `
                            <div class="col-4">
                                <div class="card mb-2">
                                    <div class="card-header">${doc.name}</div>
                                    <div class="card-body">
                                        ${doc.description ? `<p>${doc.description}</p>` : ''}
                                        <a href="/docs/${doc.id}" class="btn btn-primary"> {{ __('View') }} </a>
                                    </div>
                                </div>
                            </div>
                        `;
                    }).join('')}
                `;
            }).join('')}
        </div>
    `;
    
</script>
@endsection
