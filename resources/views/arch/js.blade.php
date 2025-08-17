<script>
            const packages = @json($packages);
            const docs = @json($docTypes);
            const objects = @json($buildings);
            const develops = @json($develops);
            const adocs = @json($docs);

            const packageIdInput = document.getElementById('package_id');
            const packageNationalNameInput = document.getElementById('package_national_name');
            const packageForeignNameInput = document.getElementById('package_foreign_name');

            document.getElementById('package_search').addEventListener('keyup', function() {
                const searchValue = this.value.toLowerCase().trim();

                const resultsDiv = document.getElementById('package_results');
                resultsDiv.innerHTML = '';

                if (searchValue.length < 2) return;

                const results = packages.filter(pkg => 
                    (pkg.national_name && pkg.national_name.toLowerCase().includes(searchValue)) ||
                    (pkg.foreign_name && pkg.foreign_name.toLowerCase().includes(searchValue))
                ).slice(0, 10);

                if (results.length === 0) {
                    resultsDiv.innerHTML = '<p class="text-muted">Нічого не знайдено</p>';
                    return;
                }

                results.forEach(pkg => {
                    const a = document.createElement('a');
                    a.href = "#";
                    a.className = "d-block mb-2";
                    const mainName = pkg.national_name && pkg.national_name.trim() !== '' ? pkg.national_name : (pkg.foreign_name ?? '');
                    const secondName = (pkg.national_name && pkg.foreign_name) ? ` (${pkg.foreign_name})` : '';
                    a.textContent = `${mainName}${secondName} - #${pkg.id}`;
                    a.onclick = e => {
                        e.preventDefault();
                        packageIdInput.value = pkg.id;
                        packageNationalNameInput.value = pkg.national_name || '';
                        packageForeignNameInput.value = pkg.foreign_name || '';
                        document.getElementById('selected_package').textContent = a.textContent;
                        bootstrap.Modal.getInstance(document.getElementById('packageModal')).hide();
                    };
                    resultsDiv.appendChild(a);
                });
            });

            // Створення нового пакета
            function createPackage() {
                const nationalName = document.getElementById('create_package_national').value.trim();
                const foreignName = document.getElementById('create_package_foreign').value.trim();

                if (!nationalName && !foreignName) {
                    alert('Будь ласка, введіть хоча б одну назву пакета.');
                    return;
                }

                // Зберігаємо у hidden поля
                packageIdInput.value = 0; // новий пакет
                packageNationalNameInput.value = nationalName;
                packageForeignNameInput.value = foreignName;
                document.getElementById('selected_package').textContent = `Новий пакет: ${nationalName || foreignName}`;

                bootstrap.Modal.getInstance(document.getElementById('packageModal')).hide();
            }

 function initDevelopAutocomplete() {
        const input = document.getElementById('develop_input');
        const suggestionsDiv = document.getElementById('develop_suggestions');

        input.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            suggestionsDiv.innerHTML = '';

            if (query.length < 1) return;

            const results = develops.filter(dev => 
                dev.name.toLowerCase().includes(query) || 
                (dev.abv && dev.abv.toLowerCase().includes(query))
            ).slice(0, 8);

            if (results.length === 0) return;

            results.forEach(dev => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                item.textContent = dev.name;
                item.onclick = () => {
                    input.value = dev.name;
                    suggestionsDiv.innerHTML = ''; // сховати підказки
                };
                suggestionsDiv.appendChild(item);
            });
        });

        // Ховаємо підказки при кліку поза полем
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.innerHTML = '';
            }
        });
    }

    function initObjectAutocomplete() {
        const input = document.getElementById('object_input');
        const suggestionsDiv = document.getElementById('object_suggestions');
        console.log('Object autocomplete initialized');
        input.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            suggestionsDiv.innerHTML = '';

            if (query.length < 1) return;

            const results = objects.filter(obj =>
                (obj.name && obj.name.toLowerCase().includes(query)) ||
                (obj.abv && obj.abv.toLowerCase().includes(query)) ||
                (obj.IDBuilding && obj.IDBuilding.toString().toLowerCase().includes(query))
            ).slice(0, 8);

            if (results.length === 0) return;

            results.forEach(obj => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";

                // показуємо і назву, і абревіатуру, і IDBuilding
                item.textContent = `${obj.name} (${obj.abv}) [ID: ${obj.IDBuilding}]`;

                item.onclick = () => {
                    input.value = obj.name; // або можна вставляти `${obj.name} (${obj.abv})`
                    suggestionsDiv.innerHTML = ''; // сховати підказки
                };
                suggestionsDiv.appendChild(item);
            });
        });

        // Ховаємо підказки при кліку поза полем
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.innerHTML = '';
            }
        });
    }

    function initDocAutocomplete() {
        const foreignInput = document.getElementById('foreign_name');
        const nationalInput = document.getElementById('national_name');
        const suggestionsDiv = document.getElementById('doc_suggestions');

        function showSuggestions(query) {
            suggestionsDiv.innerHTML = '';
            if (query.length < 1) return;

            const results = docs.filter(doc =>
                (doc.foreign_name && doc.foreign_name.toLowerCase().includes(query)) ||
                (doc.national_name && doc.national_name.toLowerCase().includes(query))
            ).slice(0, 8);

            if (results.length === 0) return;

            results.forEach(doc => {
                const item = document.createElement('button');
                item.type = "button";
                item.className = "list-group-item list-group-item-action";
                item.textContent = `${doc.foreign_name || ''} ${doc.national_name ? " / " + doc.national_name : ''}`;

                item.onclick = () => {
                    foreignInput.value = doc.foreign_name || '';
                    nationalInput.value = doc.national_name || '';
                    suggestionsDiv.innerHTML = ''; // сховати
                };
                suggestionsDiv.appendChild(item);
            });
        }

        foreignInput.addEventListener('input', function () {
            showSuggestions(this.value.toLowerCase().trim());
        });

        nationalInput.addEventListener('input', function () {
            showSuggestions(this.value.toLowerCase().trim());
        });

        // закриття при кліку поза
        document.addEventListener('click', function (e) {
            if (!foreignInput.contains(e.target) &&
                !nationalInput.contains(e.target) &&
                !suggestionsDiv.contains(e.target)) {
                suggestionsDiv.innerHTML = '';
            }
        });
    }

    const statusSelect = document.getElementById('status_select');
    const searchBlock = document.getElementById('replaced_search_block');
    const searchInput = document.getElementById('doc_search');
    const resultsDiv = document.getElementById('doc_results');
    const replacedId = document.getElementById('replaced_id');
    const replacedLabel = document.getElementById('replaced_label');

    // показуємо пошук лише якщо статус = replaced
    statusSelect.addEventListener('change', function () {
        if (this.value === 'replaced') {
            searchBlock.style.display = 'block';
        } else {
            searchBlock.style.display = 'none';
            replacedId.value = '';
            replacedLabel.textContent = '';
            resultsDiv.innerHTML = '';
        }
    });

    // пошук документів
    searchInput.addEventListener('keyup', function () {
        const query = this.value.toLowerCase().trim();
        resultsDiv.innerHTML = '';

        if (query.length < 2) return;

        const matches = adocs.filter(d =>
            (d.foreign_name && d.foreign_name.toLowerCase().includes(query)) ||
            (d.national_name && d.national_name.toLowerCase().includes(query)) ||
            (d.code && d.code.toLowerCase().includes(query)) ||
            (d.inventory && d.inventory.toLowerCase().includes(query))
        );

        matches.forEach(doc => {
            const div = document.createElement('div');
            div.textContent = `${doc.id}: ${doc.foreign_name || doc.national_name || doc.code}`;
            div.style.padding = '5px';
            div.style.cursor = 'pointer';
            div.addEventListener('click', () => {
                replacedId.value = doc.id;
                replacedLabel.textContent = `Вибрано: ${doc.foreign_name || doc.national_name || doc.code} (ID: ${doc.code})`;
                resultsDiv.innerHTML = '';
                searchInput.value = '';
            });
            resultsDiv.appendChild(div);
        });
    });
</script>